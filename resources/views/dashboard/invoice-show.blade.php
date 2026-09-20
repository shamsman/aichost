@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px; max-width: 800px;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('dashboard.invoices') }}" style="color: var(--accent-cyan); text-decoration: none; font-size: 14px;">&larr; Back to Invoices</a>
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
            </div>
        </div>

        <!-- Billed to -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 32px; font-size: 14px;">
            <div>
                <strong style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; display: block; margin-bottom: 4px;">Billed To:</strong>
                <div style="font-weight: 700; color: var(--text-main);">{{ $invoice->user->name }}</div>
                <div style="color: var(--text-muted);">{{ $invoice->user->email }}</div>
                @if($invoice->user->company)
                    <div style="color: var(--text-muted);">{{ $invoice->user->company }}</div>
                @endif
            </div>
            <div style="text-align: right;">
                <strong style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; display: block; margin-bottom: 4px;">Payment Status:</strong>
                <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-weight: 700; font-size: 12px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase;">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>

        <!-- Amount Box -->
        <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; padding: 24px; margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: var(--text-muted);">Order Reference:</span>
                <strong style="color: var(--text-main);">{{ $invoice->order?->order_number ?? 'Auto-Renewal' }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: var(--text-muted);">Subtotal:</span>
                <strong style="color: var(--text-main);">${{ number_format($invoice->subtotal, 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                <span style="color: var(--text-muted);">Taxes & Fees:</span>
                <strong style="color: #059669;">$0.00</strong>
            </div>
            <div style="border-top: 1px solid var(--card-border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; font-size: 16px; color: var(--text-main);">Total Paid:</span>
                <span style="font-size: 26px; font-weight: 800; color: var(--accent-cyan); font-family: 'Space Grotesk', sans-serif;">
                    ${{ number_format($invoice->total, 2) }} {{ $invoice->currency }}
                </span>
            </div>
        </div>

        <div style="text-align: center; color: var(--text-muted); font-size: 13px;">
            Thank you for choosing AICHost.com for your AI Cloud infrastructure.
        </div>
    </div>
</div>
@endsection
