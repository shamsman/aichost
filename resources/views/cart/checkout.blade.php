@extends('layouts.app')

@section('title', 'Complete Order — AICHost')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px; max-width: 960px;">
    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 24px;">Checkout & Service Provisioning</h1>

    <form method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: flex-start;">
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Account Info -->
                <div class="glass-panel" style="padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 18px; color: #fff;">1. Customer Information</h3>

                    @auth
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.25); padding: 14px 18px; border-radius: 10px; font-size: 14px; color: #34d399;">
                            Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}). Services will be provisioned directly to your account.
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                            <div>
                                <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase;">Full Name</label>
                                <input type="text" name="name" style="width: 100%;" placeholder="e.g. Alex Morgan" required value="{{ old('name') }}">
                            </div>
                            <div>
                                <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase;">Email Address</label>
                                <input type="email" name="email" style="width: 100%;" placeholder="e.g. alex@company.com" required value="{{ old('email') }}">
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase;">Account Password</label>
                            <input type="password" name="password" style="width: 100%;" placeholder="Min. 8 characters" required>
                        </div>
                    @endauth
                </div>

                <!-- Payment Selection -->
                <div class="glass-panel" style="padding: 28px;">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 18px; color: #fff;">2. Payment Method</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <label style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; background: rgba(0,0,0,0.3); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 10px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="credit_card" checked style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 700; color: #fff; font-size: 14px;">Instant Credit Card / Google Pay</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Zero latency automated provisioning via Google Cloud Run worker</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; gap: 12px; padding: 14px 18px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; cursor: pointer;">
                            <input type="radio" name="payment_method" value="account_balance" style="accent-color: var(--primary);">
                            <div>
                                <div style="font-weight: 700; color: #fff; font-size: 14px;">Account Credits / Pre-Paid Balance</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Current Balance: ${{ number_format(auth()->user()->balance ?? 0, 2) }}</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">Order Summary</h3>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                    @foreach($cart as $item)
                        <div style="display: flex; justify-content: space-between; font-size: 13px;">
                            <span style="color: var(--text-muted);">{{ Str::limit($item['name'], 24) }}:</span>
                            <strong style="color: #fff;">${{ number_format($item['price'], 2) }}</strong>
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 14px; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 700;">
                        <span>Total Due:</span>
                        <span style="color: var(--accent-cyan); font-size: 24px; font-family: 'Space Grotesk', sans-serif;">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 50px; font-size: 16px;">
                    Complete Order & Provision &rarr;
                </button>

                <div style="font-size: 12px; color: var(--text-muted); margin-top: 16px; text-align: center; line-height: 1.5;">
                    🔒 256-bit encrypted checkout. By placing this order, your service will be automatically configured on CWP / Google Cloud.
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
