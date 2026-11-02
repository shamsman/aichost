@extends('layouts.app')

@section('title', 'Manage Users — Super Admin — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span class="badge-ai" style="background: rgba(99, 102, 241, 0.15); color: var(--primary); border-color: rgba(99, 102, 241, 0.3);">
                    Super Admin Module
                </span>
                <span style="font-size: 13px; color: var(--text-muted);">User Directory & Access Control</span>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Add, Edit & Manage Users</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Create client or admin accounts, update credentials & credit balances, suspend access, or delete users.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">
                &larr; Admin Dashboard
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm">
                📑 Manage Invoices
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm" style="font-weight: 700; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Add New User
            </a>
        </div>
    </div>

    <!-- User KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Registered Users</div>
            <div style="font-size: 30px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                {{ $counts['total'] }}
            </div>
            <div style="font-size: 12px; color: var(--accent-cyan); margin-top: 4px;">{{ $counts['customers'] }} Customers • {{ $counts['admins'] }} Admins</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Active Accounts</div>
            <div style="font-size: 30px; font-weight: 800; color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                {{ $counts['active'] }}
            </div>
            <div style="font-size: 12px; color: var(--accent-emerald); margin-top: 4px;">Full Portal Access</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Suspended Accounts</div>
            <div style="font-size: 30px; font-weight: 800; color: #ef4444; font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                {{ $counts['suspended'] }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Restricted Access</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Client Credits</div>
            <div style="font-size: 30px; font-weight: 800; color: var(--primary); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($counts['total_balance'], 2) }}
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Combined Wallet Balance</div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="glass-panel" style="padding: 20px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 2; min-width: 240px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, company, or phone..." style="width: 100%;">
            </div>

            <div style="flex: 1; min-width: 160px;">
                <select name="role" style="width: 100%;" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customers</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Super Admins</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 160px;">
                <select name="status" style="width: 100%;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm" style="color: var(--text-muted);">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    @if($users->isEmpty())
        <div class="glass-panel" style="padding: 60px; text-align: center;">
            <div style="font-size: 44px; margin-bottom: 16px;">👥</div>
            <h3 style="font-size: 18px; margin-bottom: 8px; color: var(--text-main);">No users found</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">No user accounts match your search filters.</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Create New User</a>
        </div>
    @else
        <div class="glass-panel" style="padding: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                        <th style="padding: 12px 16px;">ID / User</th>
                        <th style="padding: 12px 16px;">Company & Contact</th>
                        <th style="padding: 12px 16px;">Role</th>
                        <th style="padding: 12px 16px;">Status</th>
                        <th style="padding: 12px 16px;">Credit Balance</th>
                        <th style="padding: 12px 16px;">Services / Invoices</th>
                        <th style="padding: 12px 16px; text-align: right;">Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 16px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--text-main);">
                                            {{ $u->name }}
                                            @if($u->id === auth()->id())
                                                <span style="font-size: 11px; color: var(--primary); font-weight: 600;">(You)</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-muted);">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 13px;">{{ $u->company ?: 'Personal Account' }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">
                                    {{ $u->phone ?: 'No phone' }} @if($u->country) • {{ $u->country }} @endif
                                </div>
                            </td>
                            <td style="padding: 16px;">
                                @if($u->role === 'admin')
                                    <span style="background: rgba(99, 102, 241, 0.14); color: var(--primary); border: 1px solid rgba(99, 102, 241, 0.3); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        Super Admin
                                    </span>
                                @else
                                    <span style="background: rgba(2, 132, 199, 0.12); color: var(--accent-cyan); border: 1px solid rgba(2, 132, 199, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                @if($u->status === 'active')
                                    <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        ACTIVE
                                    </span>
                                @else
                                    <span style="background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        SUSPENDED
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                <strong style="font-size: 15px; color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif;">
                                    ${{ number_format((float) $u->balance, 2) }}
                                </strong>
                            </td>
                            <td style="padding: 16px; font-size: 12px; color: var(--text-muted);">
                                <div><strong>{{ $u->services_count }}</strong> Services • <strong>{{ $u->domains_count }}</strong> Domains</div>
                                <div>
                                    <a href="{{ route('admin.invoices.index', ['user_id' => $u->id]) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                        {{ $u->invoices_count }} Invoices &rarr;
                                    </a>
                                </div>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
                                    <a href="{{ route('admin.invoices.create', ['user_id' => $u->id]) }}" class="btn btn-outline btn-sm" title="Issue Invoice">
                                        + Invoice
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-outline btn-sm" title="Edit User">
                                        Edit
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $u->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline btn-sm" style="color: {{ $u->status === 'active' ? '#d97706' : '#059669' }};">
                                                {{ $u->status === 'active' ? 'Suspend' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete user {{ $u->name }} ({{ $u->email }})?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: rgba(239,68,68,0.3);" title="Delete User">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
