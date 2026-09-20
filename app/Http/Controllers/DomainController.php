<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\DomainRegistrarInterface;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function __construct(
        protected DomainRegistrarInterface $domainRegistrar
    ) {}

    public function index(): View
    {
        $domainProduct = Product::where('type', Product::TYPE_DOMAIN)->first();
        $domainPlans = $domainProduct 
            ? $domainProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        return view('domains.index', compact('domainPlans'));
    }

    public function check(Request $request): JsonResponse
    {
        $domain = trim($request->input('domain', ''));

        if (empty($domain)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid domain name.',
            ], 422);
        }

        // Auto-append .ai if no extension provided
        if (!str_contains($domain, '.')) {
            $domain .= '.ai';
        }

        $result = $this->domainRegistrar->checkAvailability($domain);

        return response()->json([
            'success'   => true,
            'domain'    => $result->domain,
            'available' => $result->available,
            'premium'   => $result->premium,
            'price'     => $result->price ?? 11.99,
            'currency'  => $result->currency ?? 'USD',
            'reason'    => $result->reason,
            'mock'      => $result->mock,
        ]);
    }
}
