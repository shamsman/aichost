@extends('layouts.app')

@section('title', 'Super Admin Dashboard — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span class="badge-ai" style="background: rgba(99, 102, 241, 0.15); color: var(--primary); border-color: rgba(99, 102, 241, 0.3);">
                    ⚡ Super Admin Control Center
                </span>
                <span style="font-size: 13px; color: var(--text-muted);">System Overview & User Management</span>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Super Admin Dashboard</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Manage registered users, cloud infrastructure, domains, and client billing from a single panel.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('dashboard.index') }}" class="btn btn-outline btn-sm">
                &larr; Client View
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="font-weight: 700;">
                👥 Manage Users ({{ $stats['total_users'] }})
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-cyan btn-sm" style="font-weight: 700;">
                + Add New User
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
                📑 All Invoices
            </a>
            <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm" style="font-weight: 700; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                + Issue Invoice
            </a>
        </div>
    </div>

    <!-- Primary KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="glass-panel" style="padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Users</div>
                <span style="font-size: 18px;">👥</span>
            </div>
            <div style="font-size: 30px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                {{ $stats['total_users'] }}
            </div>
            <div style="font-size: 12px; color: var(--accent-cyan); margin-top: 4px;">
                {{ $stats['total_customers'] }} Clients • {{ $stats['total_admins'] }} Admins
                @if($stats['suspended_users'] > 0)
                    • <span style="color: #ef4444;">{{ $stats['suspended_users'] }} Suspended</span>
                @endif
            </div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Revenue Collected</div>
                <span style="font-size: 18px;">💰</span>
            </div>
            <div style="font-size: 30px; font-weight: 800; color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($stats['total_paid'], 2) }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                Total Invoiced: ${{ number_format($stats['total_invoiced'], 2) }}
            </div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Unpaid Receivables</div>
                <span style="font-size: 18px;">⏳</span>
            </div>
            <div style="font-size: 30px; font-weight: 800; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($stats['total_unpaid'], 2) }}
            </div>
            <div style="font-size: 12px; color: #f59e0b; margin-top: 4px;">
                {{ $stats['unpaid_count'] }} Unpaid Invoices
            </div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Active Infrastructure</div>
                <span style="font-size: 18px;">☁️</span>
            </div>
            <div style="font-size: 30px; font-weight: 800; color: var(--primary); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                {{ $stats['active_services'] }} <span style="font-size: 14px; font-weight: 600; color: var(--text-muted);">Services</span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">
                {{ $stats['total_domains'] }} Domains • Credits: ${{ number_format($stats['total_balance'], 2) }}
            </div>
        </div>
    </div>

    <!-- Quick Admin Modules Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 36px;">
        <div class="glass-panel" style="padding: 26px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.06) 0%, rgba(2, 132, 199, 0.06) 100%); border: 1px solid rgba(99, 102, 241, 0.25);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">
                        👥 User Management (Add / Edit / Delete)
                    </h3>
                    <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">
                        Create new customer or admin accounts, update passwords, modify account credit balances, suspend/activate accounts, or remove users.
                    </p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" style="font-weight: 700;">
                            + Add New User
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">
                            View All Users ({{ $stats['total_users'] }}) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-panel" style="padding: 26px; background: linear-gradient(135deg, rgba(5, 150, 105, 0.06) 0%, rgba(99, 102, 241, 0.06) 100%); border: 1px solid rgba(5, 150, 105, 0.25);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                <div>
                    <h3 style="font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 6px;">
                        💳 Financial & Invoice Center
                    </h3>
                    <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">
                        Issue custom itemized invoices for any user or guest client, record payments, apply discounts/taxes, and export printable PDF receipts.
                    </p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('admin.invoices.create') }}" class="btn btn-cyan btn-sm" style="font-weight: 700;">
                            + Issue New Invoice
                        </a>
                        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
                            Manage Invoices &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="glass-panel" style="padding: 28px; margin-bottom: 36px; overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 19px; font-weight: 800; color: var(--text-main);">Registered Users</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Quickly edit, bill, suspend, or delete user accounts.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Add User</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">All Users & Search &rarr;</a>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                    <th style="padding: 12px 14px;">User / Email</th>
                    <th style="padding: 12px 14px;">Company</th>
                    <th style="padding: 12px 14px;">Role</th>
                    <th style="padding: 12px 14px;">Status</th>
                    <th style="padding: 12px 14px;">Balance</th>
                    <th style="padding: 12px 14px;">Assets</th>
                    <th style="padding: 12px 14px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $u)
                    <tr style="border-bottom: 1px solid var(--card-border);">
                        <td style="padding: 14px;">
                            <div style="font-weight: 700; color: var(--text-main);">{{ $u->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ $u->email }}</div>
                        </td>
                        <td style="padding: 14px; color: var(--text-muted); font-size: 13px;">
                            {{ $u->company ?: '—' }}
                        </td>
                        <td style="padding: 14px;">
                            @if($u->role === 'admin')
                                <span style="background: rgba(99, 102, 241, 0.14); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.3); font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                                    Super Admin
                                </span>
                            @else
                                <span style="background: rgba(2, 132, 199, 0.12); color: var(--accent-cyan); border: 1px solid rgba(2, 132, 199, 0.25); font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                                    Customer
                                </span>
                            @endif
                        </td>
                        <td style="padding: 14px;">
                            @if($u->status === 'active')
                                <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                                    Active
                                </span>
                            @else
                                <span style="background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 12px; text-transform: uppercase;">
                                    Suspended
                                </span>
                            @endif
                        </td>
                        <td style="padding: 14px; font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--accent-emerald);">
                            ${{ number_format((float) $u->balance, 2) }}
                        </td>
                        <td style="padding: 14px; font-size: 12px; color: var(--text-muted);">
                            {{ $u->services_count }} Svc • {{ $u->domains_count }} Dom • {{ $u->invoices_count }} Inv
                        </td>
                        <td style="padding: 14px; text-align: right;">
                            <div style="display: inline-flex; gap: 6px; align-items: center;">
                                <a href="{{ route('admin.invoices.create', ['user_id' => $u->id]) }}" class="btn btn-outline btn-sm" title="Issue Invoice for {{ $u->name }}">
                                    + Bill
                                </a>
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-outline btn-sm">
                                    Edit
                                </a>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" style="display: inline;" onsubmit="return confirm('Delete user {{ $u->name }} ({{ $u->email }})?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: rgba(239,68,68,0.3);" title="Delete User">
                                            &times;
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Invoices Table -->
    <div class="glass-panel" style="padding: 28px; overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 19px; font-weight: 800; color: var(--text-main);">Recent Invoices</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Latest billing activity across the platform.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-cyan btn-sm">+ Issue Invoice</a>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">All Invoices &rarr;</a>
            </div>
        </div>

        @if($recentInvoices->isEmpty())
            <div style="text-align: center; padding: 32px; color: var(--text-muted);">No invoices issued yet.</div>
        @else
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                        <th style="padding: 12px 14px;">Invoice #</th>
                        <th style="padding: 12px 14px;">Client</th>
                        <th style="padding: 12px 14px;">Date</th>
                        <th style="padding: 12px 14px;">Total</th>
                        <th style="padding: 12px 14px;">Status</th>
                        <th style="padding: 12px 14px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentInvoices as $inv)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 14px;">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" style="font-weight: 700; color: var(--primary); text-decoration: none; font-family: 'Space Grotesk', sans-serif;">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td style="padding: 14px;">
                                <div style="font-weight: 600; color: var(--text-main);">{{ $inv->recipient_name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">{{ $inv->recipient_email }}</div>
                            </td>
                            <td style="padding: 14px; font-size: 13px; color: var(--text-muted);">
                                {{ $inv->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 14px; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">
                                ${{ number_format((float) $inv->total, 2) }}
                            </td>
                            <td style="padding: 14px;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: {{ $inv->status === 'paid' ? '#059669' : ($inv->status === 'unpaid' ? '#d97706' : '#64748b') }};">
                                    {{ strtoupper($inv->status) }}
                                </span>
                            </td>
                            <td style="padding: 14px; text-align: right;">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-outline btn-sm">View</a>
                                <a href="{{ route('admin.invoices.edit', $inv->id) }}" class="btn btn-outline btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
