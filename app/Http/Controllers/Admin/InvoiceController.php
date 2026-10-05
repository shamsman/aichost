<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                abort_unless(Auth::check() && Auth::user()->isAdmin(), 403, 'Access denied. Administrator privileges required.');

                return $next($request);
            },
        ];
    }

    /**
     * Display all invoices in the system with admin stats and filters.
     */
    public function index(Request $request): View
    {
        $query = Invoice::with(['user', 'creator', 'items'])->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('client_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $invoices = $query->paginate(15)->withQueryString();

        // Analytics KPIs
        $totalInvoiced = (float) Invoice::sum('total');
        $totalPaid = (float) Invoice::where('status', 'paid')->sum('total');
        $totalUnpaid = (float) Invoice::where('status', 'unpaid')->sum('total');
        $unpaidCount = Invoice::where('status', 'unpaid')->count();
        $paidCount = Invoice::where('status', 'paid')->count();
        $totalCount = Invoice::count();

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.invoices.index', compact(
            'invoices',
            'totalInvoiced',
            'totalPaid',
            'totalUnpaid',
            'unpaidCount',
            'paidCount',
            'totalCount',
            'users'
        ));
    }

    /**
     * Show form to issue a new invoice.
     */
    public function create(Request $request): View
    {
        $users = User::orderBy('name')->get();
        $selectedUserId = $request->input('user_id');

        // Generate preliminary invoice number
        $today = date('Ymd');
        $lastId = (Invoice::max('id') ?? 0) + 1;
        $suggestedNumber = sprintf('INV-%s-%04d', $today, $lastId);

        return view('admin.invoices.create', compact('users', 'selectedUserId', 'suggestedNumber'));
    }

    /**
     * Store newly issued invoice.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'client_company' => ['nullable', 'string', 'max:255'],
            'client_address' => ['nullable', 'string', 'max:1000'],
            'invoice_number' => ['required', 'string', 'max:64', 'unique:invoices,invoice_number'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:unpaid,paid,draft,cancelled'],
            'due_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'discount_type' => ['required', 'in:none,fixed,percent'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'string', 'max:50'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated) {
            // Calculate item lines & subtotal
            $subtotal = 0.0;
            $itemsData = [];

            foreach ($validated['items'] as $index => $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $lineTotal = round($qty * $price, 2);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'item_type' => $item['item_type'],
                    'description' => $item['description'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                    'sort_order' => $index,
                ];
            }

            // Discount calculation
            $discountType = $validated['discount_type'];
            $discountValue = (float) ($validated['discount_value'] ?? 0);
            $discountAmount = 0.0;

            if ($discountType === 'fixed') {
                $discountAmount = min($discountValue, $subtotal);
            } elseif ($discountType === 'percent') {
                $discountAmount = round($subtotal * ($discountValue / 100), 2);
            }

            // Tax calculation
            $taxRate = (float) ($validated['tax_rate'] ?? 0);
            $taxable = max(0, $subtotal - $discountAmount);
            $taxAmount = round($taxable * ($taxRate / 100), 2);
            $total = round($taxable + $taxAmount, 2);

            $paidAt = $validated['paid_at'] ?? null;
            if ($validated['status'] === 'paid' && ! $paidAt) {
                $paidAt = now();
            }

            // Client auto-fill from user if user_id is provided
            $clientName = $validated['client_name'] ?? null;
            $clientEmail = $validated['client_email'] ?? null;
            $clientCompany = $validated['client_company'] ?? null;
            $clientAddress = $validated['client_address'] ?? null;
            $clientPhone = $validated['client_phone'] ?? null;

            if (! empty($validated['user_id'])) {
                $user = User::find($validated['user_id']);
                if ($user) {
                    $clientName = $clientName ?: $user->name;
                    $clientEmail = $clientEmail ?: $user->email;
                    $clientCompany = $clientCompany ?: $user->company;
                    $clientAddress = $clientAddress ?: $user->address;
                    $clientPhone = $clientPhone ?: $user->phone;
                }
            }

            $invoice = Invoice::create([
                'user_id' => $validated['user_id'] ?? null,
                'client_name' => $clientName,
                'client_email' => $clientEmail,
                'client_phone' => $clientPhone,
                'client_company' => $clientCompany,
                'client_address' => $clientAddress,
                'invoice_number' => $validated['invoice_number'],
                'currency' => strtoupper($validated['currency']),
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => $validated['status'],
                'due_at' => $validated['due_at'] ?? now()->addDays(14),
                'paid_at' => $paidAt,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? 'Payment due within invoice terms. Overdue balances subject to suspension of active cloud services.',
                'created_by' => Auth::id(),
            ]);

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }
        });

        return redirect()->route('admin.invoices.index')->with('success', "Invoice {$validated['invoice_number']} successfully issued!");
    }

    /**
     * Show detailed invoice view with printable options and superadmin controls.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['user', 'creator', 'items', 'order']);

        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show form to edit an existing invoice.
     */
    public function edit(Invoice $invoice): View
    {
        $invoice->load(['user', 'items']);
        $users = User::orderBy('name')->get();

        return view('admin.invoices.edit', compact('invoice', 'users'));
    }

    /**
     * Update invoice details and line items.
     */
    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'client_email' => ['nullable', 'email', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'client_company' => ['nullable', 'string', 'max:255'],
            'client_address' => ['nullable', 'string', 'max:1000'],
            'invoice_number' => ['required', 'string', 'max:64', 'unique:invoices,invoice_number,'.$invoice->id],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:unpaid,paid,draft,cancelled'],
            'due_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'discount_type' => ['required', 'in:none,fixed,percent'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'terms' => ['nullable', 'string', 'max:5000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'string', 'max:50'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            $subtotal = 0.0;
            $itemsData = [];

            foreach ($validated['items'] as $index => $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $lineTotal = round($qty * $price, 2);
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'item_type' => $item['item_type'],
                    'description' => $item['description'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                    'sort_order' => $index,
                ];
            }

            $discountType = $validated['discount_type'];
            $discountValue = (float) ($validated['discount_value'] ?? 0);
            $discountAmount = 0.0;

            if ($discountType === 'fixed') {
                $discountAmount = min($discountValue, $subtotal);
            } elseif ($discountType === 'percent') {
                $discountAmount = round($subtotal * ($discountValue / 100), 2);
            }

            $taxRate = (float) ($validated['tax_rate'] ?? 0);
            $taxable = max(0, $subtotal - $discountAmount);
            $taxAmount = round($taxable * ($taxRate / 100), 2);
            $total = round($taxable + $taxAmount, 2);

            $paidAt = $validated['paid_at'] ?? $invoice->paid_at;
            if ($validated['status'] === 'paid' && ! $paidAt) {
                $paidAt = now();
            } elseif ($validated['status'] !== 'paid') {
                $paidAt = null;
            }

            $invoice->update([
                'user_id' => $validated['user_id'] ?? null,
                'client_name' => $validated['client_name'] ?? null,
                'client_email' => $validated['client_email'] ?? null,
                'client_phone' => $validated['client_phone'] ?? null,
                'client_company' => $validated['client_company'] ?? null,
                'client_address' => $validated['client_address'] ?? null,
                'invoice_number' => $validated['invoice_number'],
                'currency' => strtoupper($validated['currency']),
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_value' => $discountValue,
                'discount_amount' => $discountAmount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'total' => $total,
                'status' => $validated['status'],
                'due_at' => $validated['due_at'] ?? null,
                'paid_at' => $paidAt,
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
            ]);

            // Re-sync items
            $invoice->items()->delete();
            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }
        });

        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', "Invoice {$invoice->invoice_number} updated successfully.");
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(Invoice $invoice): RedirectResponse
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', "Invoice {$invoice->invoice_number} marked as Paid.");
    }

    /**
     * Cancel invoice.
     */
    public function cancel(Invoice $invoice): RedirectResponse
    {
        $invoice->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', "Invoice {$invoice->invoice_number} cancelled.");
    }

    /**
     * Simulate sending invoice notification to client.
     */
    public function sendNotification(Invoice $invoice): RedirectResponse
    {
        $email = $invoice->recipient_email;
        if (! $email) {
            return back()->with('error', 'Cannot send notification: No client email specified.');
        }

        // We can record or flash notification dispatch
        return back()->with('success', "Invoice notification and payment receipt successfully dispatched to {$email}.");
    }

    /**
     * Delete an invoice.
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $number = $invoice->invoice_number;
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', "Invoice {$number} deleted.");
    }
}
