@extends('layouts.app')

@section('title', 'Shopping Cart — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px; max-width: 900px;">
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 24px;">Your Shopping Cart</h1>

    @if(empty($cart))
        <div class="glass-panel" style="padding: 60px 24px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 16px;">🛒</div>
            <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">Your cart is currently empty</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px; font-size: 14px;">Select a shared hosting plan on CWP, configure a Google Cloud VPS, or register an AI domain.</p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('hosting.index') }}" class="btn btn-primary">Browse CWP Hosting</a>
                <a href="{{ route('vps.index') }}" class="btn btn-cyan">Explore Google Cloud VPS</a>
                <a href="{{ route('domains.index') }}" class="btn btn-outline">Find Domains</a>
            </div>
        </div>
    @else
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: flex-start;">
            <div class="glass-panel" style="padding: 24px;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--card-border); padding-bottom: 12px; color: var(--text-main);">Order Items</h3>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($cart as $item)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px;">
                            <div>
                                <div style="font-weight: 700; color: var(--text-main); font-size: 15px;">{{ $item['name'] }}</div>
                                <div style="color: var(--text-muted); font-size: 13px;">
                                    {{ ucfirst(str_replace('_', ' ', $item['type'])) }} • {{ ucfirst($item['billing_cycle'] ?? 'monthly') }}
                                    @if(!empty($item['domain_name']))
                                        • Domain: <strong style="color: var(--accent-cyan);">{{ $item['domain_name'] }}</strong>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <span style="font-size: 18px; font-weight: 700; font-family: 'Space Grotesk', sans-serif; color: var(--text-main);">
                                    ${{ number_format($item['price'], 2) }}
                                </span>
                                <a href="{{ route('cart.remove', $item['id']) }}" style="color: #ef4444; text-decoration: none; font-size: 18px;" title="Remove Item">&times;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--text-main);">Order Summary</h3>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; color: var(--text-muted);">
                    <span>Subtotal:</span>
                    <span style="color: var(--text-main); font-weight: 600;">${{ number_format($subtotal, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 14px; color: var(--text-muted);">
                    <span>Setup Fee / Taxes:</span>
                    <span style="color: var(--accent-emerald); font-weight: 600;">$0.00 (Free)</span>
                </div>
                <div style="border-top: 1px solid var(--card-border); padding-top: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 700; font-size: 16px; color: var(--text-main);">Total Due:</span>
                    <span style="font-size: 26px; font-weight: 800; color: var(--accent-cyan); font-family: 'Space Grotesk', sans-serif;">${{ number_format($total, 2) }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-primary" style="width: 100%; font-size: 15px;">
                    Proceed to Checkout &rarr;
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
