<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $services = $user->services()->with(['productPlan', 'hostingAccount', 'vpsInstance'])->latest()->get();
        $domains = $user->domains()->latest()->get();
        $invoices = $user->invoices()->latest()->take(5)->get();
        $cwpServer = Server::where('provider', 'cwp')->first();

        $activeServicesCount = $services->where('status', 'active')->count();
        $domainsCount = $domains->count();
        $unpaidInvoicesCount = $invoices->where('status', 'unpaid')->count();

        return view('dashboard.index', compact(
            'user',
            'services',
            'domains',
            'invoices',
            'cwpServer',
            'activeServicesCount',
            'domainsCount',
            'unpaidInvoicesCount'
        ));
    }

    public function services(Request $request): View
    {
        $user = Auth::user();
        $services = $user->services()->with(['productPlan', 'hostingAccount', 'vpsInstance'])->latest()->paginate(10);
        $cwpServer = Server::where('provider', 'cwp')->first();

        return view('dashboard.services', compact('services', 'cwpServer'));
    }

    public function domains(Request $request): View
    {
        $user = Auth::user();
        $domains = $user->domains()->latest()->paginate(10);

        return view('dashboard.domains', compact('domains'));
    }

    public function invoices(Request $request): View
    {
        $user = Auth::user();
        $invoices = $user->invoices()->latest()->paginate(10);

        return view('dashboard.invoices', compact('invoices'));
    }

    public function invoiceShow(Invoice $invoice): View
    {
        abort_unless($invoice->user_id === auth()->id() || auth()->user()->isAdmin(), 403);

        return view('dashboard.invoice-show', compact('invoice'));
    }
}
