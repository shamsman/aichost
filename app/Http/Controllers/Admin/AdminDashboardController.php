<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Server;
use App\Models\Service;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminDashboardController extends Controller implements HasMiddleware
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

    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'total_balance' => (float) User::sum('balance'),
            'active_services' => Service::where('status', 'active')->count(),
            'total_services' => Service::count(),
            'total_domains' => Domain::count(),
            'total_invoiced' => (float) Invoice::sum('total'),
            'total_paid' => (float) Invoice::where('status', 'paid')->sum('total'),
            'total_unpaid' => (float) Invoice::where('status', 'unpaid')->sum('total'),
            'unpaid_count' => Invoice::where('status', 'unpaid')->count(),
        ];

        $recentUsers = User::withCount(['services', 'domains', 'invoices'])
            ->latest()
            ->take(8)
            ->get();

        $recentInvoices = Invoice::with('user')
            ->latest()
            ->take(6)
            ->get();

        $cwpServer = Server::where('provider', 'cwp')->first();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentInvoices', 'cwpServer'));
    }
}
