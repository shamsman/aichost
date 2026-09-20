<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\HostingPanelInterface;
use App\Models\HostingAccount;
use App\Models\Product;
use App\Models\Server;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HostingController extends Controller
{
    public function __construct(
        protected HostingPanelInterface $hostingPanel
    ) {}

    public function index(): View
    {
        $hostingProduct = Product::where('type', Product::TYPE_SHARED_HOSTING)->first();
        $hostingPlans = $hostingProduct 
            ? $hostingProduct->activePlans()->orderBy('sort_order')->get() 
            : collect();

        $cwpServer = Server::where('provider', 'cwp')->first();

        return view('hosting.index', compact('hostingPlans', 'cwpServer'));
    }

    public function sso(Service $service): RedirectResponse
    {
        abort_unless($service->user_id === auth()->id(), 403, 'Unauthorized access to this hosting account.');
        
        $hostingAccount = $service->hostingAccount;
        abort_unless($hostingAccount !== null, 404, 'Hosting account not found for this service.');

        $result = $this->hostingPanel->generateSsoUrl($hostingAccount->cwp_username);

        return redirect()->away($result->url);
    }
}
