@extends('layouts.app')

@section('title', 'Billing & Invoices — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Invoices & Billing History</h1>
            <p style="color: var(--text-muted); font-size: 14px;">All order invoices, payment receipts, and renewal records.</p>
        </div>
    </div>

    @if($invoices->isEmpty())
        <div class="glass-panel" style="padding: 60px; text-align: center;">
            <div style="font-size: 44px; margin-bottom: 16px;">📄</div>
            <h3 style="font-size: 18px; margin-bottom: 8px; color: var(--text-main);">No invoices generated</h3>
            <p style="color: var(--text-muted); font-size: 14px;">Your paid invoices will appear here after orders are placed.</p>
        </div>
    @else
        <div class="glass-panel" style="padding: 24px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                        <th style="padding: 14px 16px;">Invoice #</th>
                        <th style="padding: 14px 16px;">Date</th>
                        <th style="padding: 14px 16px;">Amount</th>
                        <th style="padding: 14px 16px;">Status</th>
                        <th style="padding: 14px 16px; text-align: right;">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $inv)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 16px;">
                                <strong style="color: var(--text-main); font-family: 'Space Grotesk', sans-serif;">{{ $inv->invoice_number }}</strong>
                            </td>
                            <td style="padding: 16px; color: var(--text-muted);">
                                {{ $inv->paid_at?->format('M d, Y') ?? $inv->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 16px;">
                                <strong style="color: var(--text-main);">${{ number_format($inv->total, 2) }} {{ $inv->currency }}</strong>
                            </td>
                            <td style="padding: 16px;">
                                <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">
                                    {{ $inv->status }}
                                </span>
                            </td>
                            <td style="padding: 16px; text-align: right;">
                                <a href="{{ route('dashboard.invoices.show', $inv->id) }}" class="btn btn-outline btn-sm">
                                    View Receipt &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $invoices->links() }}
        </div>
    @endif
</div>
@endsection
