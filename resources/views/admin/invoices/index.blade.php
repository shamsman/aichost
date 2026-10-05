@extends('layouts.app')

@section('title', 'Admin Invoice Management — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <span class="badge-ai" style="background: rgba(99, 102, 241, 0.15); color: var(--primary); border-color: rgba(99, 102, 241, 0.3);">
                    Super Admin Module
                </span>
                <span style="font-size: 13px; color: var(--text-muted);">Financial Center</span>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Issue & Manage Invoices</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Generate custom billing invoices, track receivables, and manage client transactions.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="{{ route('dashboard.index') }}" class="btn btn-outline btn-sm">
                &larr; Back to Dashboard
            </a>
            <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm" style="font-weight: 700; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Issue New Invoice
            </a>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Invoiced</div>
            <div style="font-size: 30px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($totalInvoiced, 2) }}
            </div>
            <div style="font-size: 12px; color: var(--accent-cyan); margin-top: 4px;">{{ $totalCount }} Total Invoices</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Collected (Paid)</div>
            <div style="font-size: 30px; font-weight: 800; color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($totalPaid, 2) }}
            </div>
            <div style="font-size: 12px; color: var(--accent-emerald); margin-top: 4px;">{{ $paidCount }} Settled Invoices</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Pending Receivables</div>
            <div style="font-size: 30px; font-weight: 800; color: #f59e0b; font-family: 'Space Grotesk', sans-serif; margin-top: 4px;">
                ${{ number_format($totalUnpaid, 2) }}
            </div>
            <div style="font-size: 12px; color: #f59e0b; margin-top: 4px;">{{ $unpaidCount }} Awaiting Payment</div>
        </div>

        <div class="glass-panel" style="padding: 22px;">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Quick Action</div>
            <div style="margin-top: 10px;">
                <a href="{{ route('admin.invoices.create') }}" class="btn btn-cyan btn-sm" style="width: 100%; text-align: center;">
                    ⚡ Direct Invoice Creator
                </a>
            </div>
            <div style="font-size: 12px; color: var(--text-dim); margin-top: 8px; text-align: center;">Custom line items & instant PDF</div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="glass-panel" style="padding: 20px; margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.invoices.index') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 2; min-width: 240px;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Invoice #, client name, email..." style="width: 100%;">
            </div>

            <div style="flex: 1; min-width: 160px;">
                <select name="status" style="width: 100%;" onchange="this.form.submit()">
                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <select name="user_id" style="width: 100%;" onchange="this.form.submit()">
                    <option value="">All Registered Clients</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
                @if(request()->hasAny(['search', 'status', 'user_id']))
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline btn-sm" style="color: var(--text-muted);">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    @if($invoices->isEmpty())
        <div class="glass-panel" style="padding: 60px; text-align: center;">
            <div style="font-size: 44px; margin-bottom: 16px;">📑</div>
            <h3 style="font-size: 18px; margin-bottom: 8px; color: var(--text-main);">No invoices found</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">No issued invoices match your query filters.</p>
            <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary btn-sm">+ Issue First Invoice</a>
        </div>
    @else
        <div class="glass-panel" style="padding: 20px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                        <th style="padding: 12px 16px;">Invoice #</th>
                        <th style="padding: 12px 16px;">Billed Client</th>
                        <th style="padding: 12px 16px;">Issue / Due Date</th>
                        <th style="padding: 12px 16px;">Amount</th>
                        <th style="padding: 12px 16px;">Status</th>
                        <th style="padding: 12px 16px; text-align: right;">Admin Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 16px;">
                                <a href="{{ route('admin.invoices.show', $inv->id) }}" style="font-weight: 700; color: var(--primary); text-decoration: none; font-family: 'Space Grotesk', sans-serif;">
                                    {{ $inv->invoice_number }}
                                </a>
                                @if($inv->order_id)
                                    <div style="font-size: 11px; color: var(--text-muted);">Order #{{ $inv->order?->order_number }}</div>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                <div style="font-weight: 600; color: var(--text-main);">{{ $inv->recipient_name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">{{ $inv->recipient_email }}</div>
                                @if($inv->recipient_company)
                                    <div style="font-size: 11px; color: var(--text-dim);">{{ $inv->recipient_company }}</div>
                                @endif
                            </td>
                            <td style="padding: 16px; color: var(--text-muted); font-size: 13px;">
                                <div>Issued: <strong>{{ $inv->created_at->format('M d, Y') }}</strong></div>
                                @if($inv->due_at)
                                    <div style="{{ $inv->isOverdue() ? 'color: #ef4444; font-weight: 700;' : 'color: var(--text-dim);' }}">
                                        Due: {{ $inv->due_at->format('M d, Y') }}
                                        @if($inv->isOverdue()) (Overdue) @endif
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                <strong style="font-size: 16px; color: var(--text-main); font-family: 'Space Grotesk', sans-serif;">
                                    ${{ number_format($inv->total, 2) }}
                                </strong>
                                <span style="font-size: 11px; color: var(--text-muted);">{{ $inv->currency }}</span>
                            </td>
                            <td style="padding: 16px;">
                                @if($inv->status === 'paid')
                                    <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        PAID
                                    </span>
                                @elseif($inv->status === 'unpaid')
                                    <span style="background: rgba(245, 158, 11, 0.12); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        UNPAID
                                    </span>
                                @elseif($inv->status === 'draft')
                                    <span style="background: rgba(100, 116, 139, 0.12); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        DRAFT
                                    </span>
                                @else
                                    <span style="background: rgba(239, 68, 68, 0.12); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.25); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 12px; text-transform: uppercase;">
                                        {{ strtoupper($inv->status) }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px; align-items: center;">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-outline btn-sm" title="View Details">
                                        View
                                    </a>
                                    <a href="{{ route('admin.invoices.edit', $inv->id) }}" class="btn btn-outline btn-sm" title="Edit Invoice">
                                        Edit
                                    </a>
                                    @if($inv->status === 'unpaid')
                                        <form method="POST" action="{{ route('admin.invoices.mark-paid', $inv->id) }}" style="display: inline;" onsubmit="return confirm('Mark this invoice as Paid?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 600;">
                                                Mark Paid
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.invoices.destroy', $inv->id) }}" style="display: inline;" onsubmit="return confirm('Delete invoice {{ $inv->invoice_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: rgba(239,68,68,0.3);" title="Delete">
                                            &times;
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 20px;">
                {{ $invoices->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
