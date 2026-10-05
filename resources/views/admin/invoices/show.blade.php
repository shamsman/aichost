@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number . ' — Super Admin View')

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
        .invoice-card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 90px; max-width: 900px;">
    <!-- Top Action Bar (No Print) -->
    <div class="no-print" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px;">
        <div>
            <a href="{{ route('admin.invoices.index') }}" style="color: var(--accent-cyan); text-decoration: none; font-size: 14px; font-weight: 600;">
                &larr; Back to Invoices List
            </a>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <!-- Print / PDF -->
            <button type="button" class="btn btn-outline btn-sm" onclick="window.print()">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print / PDF Receipt
            </button>

            <!-- Send Notification -->
            <form method="POST" action="{{ route('admin.invoices.send', $invoice->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm" title="Dispatch invoice notification to client">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Send to Client
                </button>
            </form>

            <!-- Mark Paid / Cancel -->
            @if($invoice->status === 'unpaid')
                <form method="POST" action="{{ route('admin.invoices.mark-paid', $invoice->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                        ✓ Mark as Paid
                    </button>
                </form>
            @endif

            <!-- Edit -->
            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-outline btn-sm">
                Edit Invoice
            </a>

            <!-- Delete -->
            <form method="POST" action="{{ route('admin.invoices.destroy', $invoice->id) }}" style="display: inline;" onsubmit="return confirm('Delete this invoice?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="color: #ef4444; border-color: rgba(239,68,68,0.3);">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Official Invoice Document Panel -->
    <div class="glass-panel invoice-card" style="padding: 44px; background: var(--surface); border: 1px solid var(--card-border); box-shadow: var(--shadow-card);">
        <!-- Invoice Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid var(--card-border); padding-bottom: 28px; flex-wrap: wrap; gap: 20px;">
            <div>
                <div class="brand-logo" style="margin-bottom: 10px;">
                    <div class="brand-icon">⚡</div>
                    <span>AI Cloud <span style="color: var(--accent-cyan);">Host</span></span>
                </div>
                <div style="color: var(--text-muted); font-size: 13px; line-height: 1.5;">
                    AI Cloud Host Inc. • Google Cloud Infrastructure Partner<br>
                    CWP Enterprise Hosting Platform<br>
                    support@aichost.com • billing@aichost.com
                </div>
            </div>

            <div style="text-align: right;">
                <h2 style="font-size: 28px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">INVOICE</h2>
                <div style="font-family: 'Space Grotesk', monospace; color: var(--primary); font-size: 17px; font-weight: 700; margin-top: 2px;">
                    {{ $invoice->invoice_number }}
                </div>
                <div style="margin-top: 10px;">
                    @if($invoice->status === 'paid')
                        <span style="background: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 12px; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            PAID
                        </span>
                    @elseif($invoice->status === 'unpaid')
                        <span style="background: rgba(245, 158, 11, 0.15); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.3); font-size: 12px; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;">
                            UNPAID
                        </span>
                    @elseif($invoice->status === 'draft')
                        <span style="background: rgba(100, 116, 139, 0.15); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.3); font-size: 12px; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase;">
                            DRAFT
                        </span>
                    @else
                        <span style="background: rgba(239, 68, 68, 0.15); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 12px; font-weight: 800; padding: 4px 14px; border-radius: 20px; text-transform: uppercase;">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Billed to & Dates -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; margin-bottom: 36px; font-size: 14px;">
            <div>
                <strong style="color: var(--text-muted); text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">Billed To:</strong>
                <div style="font-size: 17px; font-weight: 700; color: var(--text-main);">{{ $invoice->recipient_name }}</div>
                @if($invoice->recipient_company)
                    <div style="font-weight: 600; color: var(--text-muted);">{{ $invoice->recipient_company }}</div>
                @endif
                <div style="color: var(--text-muted); margin-top: 4px;">{{ $invoice->recipient_email }}</div>
                @if($invoice->client_phone)
                    <div style="color: var(--text-muted);">Phone: {{ $invoice->client_phone }}</div>
                @endif
                @if($invoice->recipient_address)
                    <div style="color: var(--text-dim); margin-top: 4px; font-size: 13px; line-height: 1.4;">{{ $invoice->recipient_address }}</div>
                @endif
            </div>

            <div style="text-align: right; line-height: 1.8;">
                <div>
                    <span style="color: var(--text-muted);">Invoice Date:</span>
                    <strong style="color: var(--text-main); margin-left: 8px;">{{ $invoice->created_at->format('M d, Y') }}</strong>
                </div>
                @if($invoice->due_at)
                    <div>
                        <span style="color: var(--text-muted);">Due Date:</span>
                        <strong style="color: var(--text-main); margin-left: 8px;">{{ $invoice->due_at->format('M d, Y') }}</strong>
                    </div>
                @endif
                @if($invoice->paid_at)
                    <div>
                        <span style="color: var(--accent-emerald);">Paid On:</span>
                        <strong style="color: var(--accent-emerald); margin-left: 8px;">{{ $invoice->paid_at->format('M d, Y') }}</strong>
                    </div>
                @endif
                @if($invoice->order_id)
                    <div>
                        <span style="color: var(--text-muted);">Order Ref:</span>
                        <span style="font-family: monospace; margin-left: 8px;">#{{ $invoice->order?->order_number }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Itemized Table -->
        <div style="margin-bottom: 32px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--card-border); background: var(--surface-hover); color: var(--text-muted);">
                        <th style="padding: 12px 16px;">Item & Service Description</th>
                        <th style="padding: 12px 16px; text-align: center; width: 80px;">Qty</th>
                        <th style="padding: 12px 16px; text-align: right; width: 130px;">Unit Price</th>
                        <th style="padding: 12px 16px; text-align: right; width: 140px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if($invoice->items->isEmpty())
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 16px;">
                                <strong style="color: var(--text-main);">Cloud Infrastructure Services</strong>
                            </td>
                            <td style="padding: 16px; text-align: center;">1</td>
                            <td style="padding: 16px; text-align: right;">${{ number_format($invoice->subtotal, 2) }}</td>
                            <td style="padding: 16px; text-align: right; font-weight: 700;">${{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                    @else
                        @foreach($invoice->items as $item)
                            <tr style="border-bottom: 1px solid var(--card-border);">
                                <td style="padding: 16px;">
                                    <div style="font-weight: 700; color: var(--text-main);">{{ $item->description }}</div>
                                    <span style="font-size: 11px; text-transform: uppercase; color: var(--accent-cyan); font-weight: 600;">
                                        {{ str_replace('_', ' ', $item->item_type) }}
                                    </span>
                                </td>
                                <td style="padding: 16px; text-align: center; color: var(--text-main);">
                                    {{ (float) $item->quantity == (int) $item->quantity ? (int) $item->quantity : $item->quantity }}
                                </td>
                                <td style="padding: 16px; text-align: right; color: var(--text-muted);">
                                    ${{ number_format($item->unit_price, 2) }}
                                </td>
                                <td style="padding: 16px; text-align: right; font-weight: 700; color: var(--text-main); font-family: 'Space Grotesk', sans-serif;">
                                    ${{ number_format($item->line_total, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Totals & Notes Grid -->
        <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 32px; margin-bottom: 32px;">
            <div>
                @if($invoice->notes)
                    <div style="margin-bottom: 16px;">
                        <strong style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Notes:</strong>
                        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">{{ $invoice->notes }}</p>
                    </div>
                @endif

                @if($invoice->terms)
                    <div>
                        <strong style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; display: block; margin-bottom: 4px;">Terms & Conditions:</strong>
                        <p style="font-size: 12px; color: var(--text-dim); line-height: 1.5;">{{ $invoice->terms }}</p>
                    </div>
                @endif
            </div>

            <!-- Totals Box -->
            <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; padding: 20px; font-size: 14px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span style="color: var(--text-muted);">Subtotal:</span>
                    <strong style="color: var(--text-main);">${{ number_format($invoice->subtotal, 2) }}</strong>
                </div>

                @if($invoice->discount_amount > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #ef4444;">
                        <span>Discount ({{ $invoice->discount_type === 'percent' ? $invoice->discount_value.'%' : 'Fixed' }}):</span>
                        <strong>-${{ number_format($invoice->discount_amount, 2) }}</strong>
                    </div>
                @endif

                @if($invoice->tax_amount > 0 || $invoice->tax_rate > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: var(--text-muted);">Tax ({{ $invoice->tax_rate }}%):</span>
                        <strong style="color: var(--text-main);">${{ number_format($invoice->tax_amount, 2) }}</strong>
                    </div>
                @endif

                <div style="border-top: 2px solid var(--card-border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 800; font-size: 16px; color: var(--text-main);">Total Amount:</span>
                    <span style="font-size: 24px; font-weight: 800; color: var(--primary); font-family: 'Space Grotesk', sans-serif;">
                        ${{ number_format($invoice->total, 2) }} {{ $invoice->currency }}
                    </span>
                </div>
            </div>
        </div>

        <div style="text-align: center; border-top: 1px solid var(--card-border); padding-top: 24px; color: var(--text-muted); font-size: 13px;">
            Thank you for choosing AICHost.com. Powered by Google Cloud Platform and CentOS Web Panel.
        </div>
    </div>
</div>
@endsection
