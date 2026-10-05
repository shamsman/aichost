@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' — AI Cloud Host')

@section('styles')
<style>
    @media print {
        .no-print, .nav-wrapper, footer, .theme-toggle-btn {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            padding: 0 !important;
        }
        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }
        .glass-panel {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px; max-width: 850px;">
    <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('dashboard.invoices') }}" style="color: var(--accent-cyan); text-decoration: none; font-size: 14px; font-weight: 600;">
            &larr; Back to Invoices
        </a>

        <div style="display: flex; gap: 8px;">
            <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">
                Print / Save PDF
            </button>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">
                    Edit Invoice (Admin)
                </a>
            @endif
        </div>
    </div>

    <div class="glass-panel" style="padding: 40px; background: var(--surface); border: 1px solid var(--card-border); box-shadow: var(--shadow-sm);">
        <!-- Invoice Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 36px; border-bottom: 1px solid var(--card-border); padding-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div>
                <div class="brand-logo" style="margin-bottom: 8px;">
                    <div class="brand-icon">⚡</div>
                    <span>AI Cloud <span style="color: var(--accent-cyan);">Host</span></span>
                </div>
                <div style="color: var(--text-muted); font-size: 13px;">Google Cloud Infrastructure • CWP Hosting</div>
            </div>
            <div style="text-align: right;">
                <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main);">INVOICE</h2>
                <div style="font-family: monospace; color: var(--accent-cyan); font-size: 15px; font-weight: 700;">{{ $invoice->invoice_number }}</div>
                <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Date: {{ $invoice->created_at->format('M d, Y') }}</div>
                @if($invoice->due_at)
                    <div style="font-size: 13px; color: var(--text-muted);">Due: {{ $invoice->due_at->format('M d, Y') }}</div>
                @endif
            </div>
        </div>

        <!-- Billed to -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 32px; font-size: 14px; flex-wrap: wrap; gap: 16px;">
            <div>
                <strong style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; display: block; margin-bottom: 4px;">Billed To:</strong>
                <div style="font-weight: 700; color: var(--text-main);">{{ $invoice->recipient_name }}</div>
                <div style="color: var(--text-muted);">{{ $invoice->recipient_email }}</div>
                @if($invoice->recipient_company)
                    <div style="color: var(--text-muted);">{{ $invoice->recipient_company }}</div>
                @endif
                @if($invoice->recipient_address)
                    <div style="color: var(--text-dim); font-size: 13px; margin-top: 2px;">{{ $invoice->recipient_address }}</div>
                @endif
            </div>
            <div style="text-align: right;">
                <strong style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; display: block; margin-bottom: 4px;">Payment Status:</strong>
                @if($invoice->status === 'paid')
                    <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
                        PAID
                    </span>
                @elseif($invoice->status === 'unpaid')
                    <span style="background: rgba(245, 158, 11, 0.12); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.25); font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
                        UNPAID
                    </span>
                @else
                    <span style="background: rgba(100, 116, 139, 0.12); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.25); font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
                        {{ strtoupper($invoice->status) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Line items table if available -->
        @if($invoice->items->isNotEmpty())
            <div style="margin-bottom: 28px; overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                            <th style="padding: 10px 12px;">Description</th>
                            <th style="padding: 10px 12px; text-align: center; width: 60px;">Qty</th>
                            <th style="padding: 10px 12px; text-align: right; width: 120px;">Unit Price</th>
                            <th style="padding: 10px 12px; text-align: right; width: 120px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $it)
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 12px;">
                                    <strong style="color: var(--text-main);">{{ $it->description }}</strong>
                                </td>
                                <td style="padding: 12px; text-align: center;">{{ (float) $it->quantity == (int) $it->quantity ? (int) $it->quantity : $it->quantity }}</td>
                                <td style="padding: 12px; text-align: right; color: var(--text-muted);">${{ number_format($it->unit_price, 2) }}</td>
                                <td style="padding: 12px; text-align: right; font-weight: 700; color: var(--text-main);">${{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Amount Box -->
        <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; margin-bottom: 32px;">
            @if($invoice->order)
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                    <span style="color: var(--text-muted);">Order Reference:</span>
                    <strong style="color: var(--text-main);">{{ $invoice->order->order_number }}</strong>
                </div>
            @endif
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: var(--text-muted);">Subtotal:</span>
                <strong style="color: var(--text-main);">${{ number_format($invoice->subtotal, 2) }}</strong>
            </div>
            @if($invoice->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: #ef4444;">
                    <span>Discount:</span>
                    <strong>-${{ number_format($invoice->discount_amount, 2) }}</strong>
                </div>
            @endif
            @if($invoice->tax_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                    <span style="color: var(--text-muted);">Tax ({{ $invoice->tax_rate }}%):</span>
                    <strong style="color: var(--text-main);">${{ number_format($invoice->tax_amount, 2) }}</strong>
                </div>
            @endif
            <div style="border-top: 1px solid var(--card-border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; font-size: 16px; color: var(--text-main);">Total:</span>
                <span style="font-size: 26px; font-weight: 800; color: var(--accent-cyan); font-family: 'Space Grotesk', sans-serif;">
                    ${{ number_format($invoice->total, 2) }} {{ $invoice->currency }}
                </span>
            </div>
        </div>

        @if($invoice->notes)
            <div style="margin-bottom: 20px; font-size: 13px; color: var(--text-muted);">
                <strong>Notes:</strong> {{ $invoice->notes }}
            </div>
        @endif

        <div style="text-align: center; color: var(--text-muted); font-size: 13px;">
            Thank you for choosing AICHost.com for your AI Cloud infrastructure.
        </div>
    </div>
</div>
@endsection
