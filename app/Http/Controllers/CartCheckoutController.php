<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductPlan;
use App\Models\Service;
use App\Models\User;
use App\Services\Provisioning\ProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartCheckoutController extends Controller
{
    public function __construct(
        protected ProvisioningService $provisioningService
    ) {}

    public function cart(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $subtotal = collect($cart)->sum('price');
        $tax = 0.00;
        $total = $subtotal + $tax;

        return view('cart.index', compact('cart', 'subtotal', 'tax', 'total'));
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $type = $request->input('type'); // 'domain', 'shared_hosting', 'vps'
        $cart = $request->session()->get('cart', []);

        if ($type === 'domain') {
            $domain = strtolower(trim($request->input('domain', '')));
            $planId = $request->input('plan_id');
            $plan = ProductPlan::find($planId);
            $price = $plan ? (float) $plan->price_monthly : 11.99;

            $cart[] = [
                'id'            => 'dom_' . uniqid(),
                'type'          => 'domain',
                'name'          => "Domain Registration: {$domain}",
                'domain_name'   => $domain,
                'plan_id'       => $plan?->id,
                'billing_cycle' => 'annually',
                'price'         => $price,
            ];
        } else {
            $planId = $request->input('plan_id');
            $plan = ProductPlan::findOrFail($planId);
            $cycle = $request->input('billing_cycle', 'monthly');
            $domainName = $request->input('domain_name');

            $price = $cycle === 'annually' 
                ? ($plan->price_annually ?? $plan->price_monthly * 10) 
                : $plan->price_monthly;

            $cart[] = [
                'id'            => 'plan_' . uniqid(),
                'type'          => $plan->isSharedHosting() ? 'shared_hosting' : 'vps',
                'name'          => $plan->name,
                'domain_name'   => $domainName ?: 'ai-' . Str::random(5) . '.aichost.com',
                'plan_id'       => $plan->id,
                'billing_cycle' => $cycle,
                'price'         => (float) $price,
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to your cart!');
    }

    public function removeFromCart(Request $request, string $id): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $cart = array_values(array_filter($cart, fn ($item) => ($item['id'] ?? '') !== $id));
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Your cart is empty. Add a hosting plan or domain to proceed.');
        }

        $subtotal = collect($cart)->sum('price');
        $tax = 0.00;
        $total = $subtotal + $tax;

        return view('cart.checkout', compact('cart', 'subtotal', 'tax', 'total'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $user = Auth::user();

        if (!$user) {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'password' => 'required|string|min:8',
            ]);

            $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name'     => $request->name,
                    'password' => Hash::make($request->password),
                    'country'  => 'US',
                ]
            );

            Auth::login($user);
        }

        $paymentMethod = $request->input('payment_method', 'credit_card');

        // Execute Order creation & Auto-Provisioning inside transaction
        $order = DB::transaction(function () use ($user, $cart, $paymentMethod) {
            $subtotal = collect($cart)->sum('price');
            $tax = 0.00;
            $total = $subtotal + $tax;

            $order = Order::create([
                'user_id'        => $user->id,
                'order_number'   => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'subtotal'       => $subtotal,
                'tax_amount'     => $tax,
                'total'          => $total,
                'currency'       => 'USD',
                'status'         => 'active',
                'payment_status' => 'paid',
                'payment_method' => $paymentMethod,
                'notes'          => 'Instant checkout via Google Cloud Run infrastructure',
            ]);

            $invoice = Invoice::create([
                'user_id'        => $user->id,
                'order_id'       => $order->id,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999),
                'subtotal'       => $subtotal,
                'tax_amount'     => $tax,
                'total'          => $total,
                'currency'       => 'USD',
                'status'         => 'paid',
                'due_at'         => now(),
                'paid_at'        => now(),
            ]);

            foreach ($cart as $item) {
                $plan = !empty($item['plan_id']) ? ProductPlan::find($item['plan_id']) : null;

                $orderItem = OrderItem::create([
                    'order_id'        => $order->id,
                    'product_plan_id' => $plan?->id,
                    'item_type'       => $item['type'],
                    'domain_name'     => $item['domain_name'] ?? null,
                    'config'          => ['billing_cycle' => $item['billing_cycle'] ?? 'monthly'],
                    'unit_price'      => $item['price'],
                    'quantity'        => 1,
                    'line_total'      => $item['price'],
                ]);

                $service = Service::create([
                    'user_id'         => $user->id,
                    'order_id'        => $order->id,
                    'product_plan_id' => $plan?->id,
                    'label'           => $item['name'],
                    'status'          => 'active',
                    'billing_cycle'   => $item['billing_cycle'] ?? 'monthly',
                    'amount'          => $item['price'],
                    'currency'        => 'USD',
                    'next_due_at'     => ($item['billing_cycle'] ?? 'monthly') === 'annually' ? now()->addYear() : now()->addMonth(),
                ]);

                // Auto-provision via our dedicated service orchestrator
                $this->provisioningService->provisionItem($orderItem, $service);
            }

            return $order;
        });

        // Clear cart
        $request->session()->forget('cart');

        return redirect()->route('dashboard.index')->with('success', "Order #{$order->order_number} successfully completed and your cloud hosting service is active!");
    }
}
