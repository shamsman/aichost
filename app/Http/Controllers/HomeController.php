<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPlan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $domainProduct  = Product::where('type', Product::TYPE_DOMAIN)->first();
        $hostingProduct = Product::where('type', Product::TYPE_SHARED_HOSTING)->first();
        $vpsProduct     = Product::where('type', Product::TYPE_VPS)->first();

        $domainPlans = $domainProduct 
            ? $domainProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        $hostingPlans = $hostingProduct 
            ? $hostingProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        $vpsPlans = $vpsProduct 
            ? $vpsProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        return view('home', compact('domainPlans', 'hostingPlans', 'vpsPlans'));
    }
}
