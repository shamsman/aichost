@extends('layouts.app')

@section('title', 'Domain Registration & Transfer — AICHost')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <!-- Header -->
    <div style="text-align: center; max-width: 800px; margin: 0 auto 50px;">
        <div class="badge-ai" style="margin-bottom: 16px;">Powered by InternetBS Reseller API</div>
        <h1 style="font-size: clamp(34px, 5vw, 52px); font-weight: 800; margin-bottom: 18px;">
            Claim Your Identity in the <span class="text-gradient-ai">AI Era</span>
        </h1>
        <p style="color: var(--text-muted); font-size: 17px;">
            Instant domain registration with automated DNS propagation, free WHOIS privacy, and direct integration into your CWP hosting accounts.
        </p>
    </div>

    <!-- Domain Search Box -->
    <div class="glass-panel" style="max-width: 840px; margin: 0 auto 60px; padding: 20px;">
        <form id="domainSearchForm" onsubmit="searchDomain(event)" style="display: flex; gap: 12px; flex-wrap: wrap;">
            <input type="text" id="domainInput" placeholder="Type your desired domain name (e.g. artificial.ai)" style="flex: 1; min-width: 280px; height: 56px; font-size: 16px; padding: 0 20px; border-radius: 12px;" required>
            <button type="submit" id="searchBtn" class="btn btn-cyan" style="height: 56px; padding: 0 32px; font-size: 16px;">
                <span id="btnText">Search Domain</span>
                <span id="btnSpinner" style="display: none;">Searching...</span>
            </button>
        </form>

        <div id="domainResultBox" style="display: none; margin-top: 20px; padding: 20px; border-radius: 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;"></div>
    </div>

    <!-- TLD Pricing Table -->
    <div class="glass-panel" style="padding: 36px; max-width: 960px; margin: 0 auto;">
        <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 24px; color: #ffffff;">Popular Domain Extensions (TLDs)</h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: var(--text-muted);">
                        <th style="padding: 14px 16px;">Extension</th>
                        <th style="padding: 14px 16px;">Ideal For</th>
                        <th style="padding: 14px 16px;">Register / 1st Year</th>
                        <th style="padding: 14px 16px;">Renewal</th>
                        <th style="padding: 14px 16px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domainPlans as $plan)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s;">
                            <td style="padding: 18px 16px;">
                                <span style="font-size: 18px; font-weight: 800; color: #ffffff; font-family: 'Space Grotesk', sans-serif;">
                                    {{ $plan->specs['tld'] ?? '.com' }}
                                </span>
                            </td>
                            <td style="padding: 18px 16px; color: var(--text-muted);">
                                @if(($plan->specs['tld'] ?? '') === '.ai')
                                    Artificial Intelligence, ML, LLM startups
                                @elseif(($plan->specs['tld'] ?? '') === '.cloud')
                                    SaaS, Cloud Infrastructure & DevOps
                                @elseif(($plan->specs['tld'] ?? '') === '.io')
                                    Tech, APIs & Developer tools
                                @else
                                    Global commercial brand identity
                                @endif
                            </td>
                            <td style="padding: 18px 16px;">
                                <strong style="color: var(--accent-cyan); font-size: 16px;">${{ number_format($plan->price_monthly, 2) }}</strong>
                            </td>
                            <td style="padding: 18px 16px; color: var(--text-muted);">
                                ${{ number_format($plan->price_annually ?? $plan->price_monthly, 2) }}/yr
                            </td>
                            <td style="padding: 18px 16px; text-align: right;">
                                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('domainInput').value = 'mybrand{{ $plan->specs['tld'] ?? '.com' }}'; searchDomain();">
                                    Check Availability
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function searchDomain(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('domainInput');
        const domain = input.value.trim();
        const box = document.getElementById('domainResultBox');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');

        if (!domain) return;

        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline';

        try {
            const res = await fetch(`/api/v1/domains/check?domain=${encodeURIComponent(domain)}`);
            const data = await res.json();

            box.style.display = 'flex';

            if (data.available) {
                box.style.background = 'rgba(16, 185, 129, 0.12)';
                box.style.border = '1px solid rgba(16, 185, 129, 0.35)';
                box.innerHTML = `
                    <div>
                        <div style="font-weight: 700; color: #34d399; font-size: 17px;">✓ <strong>${data.domain}</strong> is available for registration!</div>
                        <div style="font-size: 13px; color: var(--text-muted);">Instant DNS provisioning via InternetBS API with WHOIS privacy protection.</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-size: 24px; font-weight: 800; color: #ffffff;">$${parseFloat(data.price).toFixed(2)}<span style="font-size: 13px; color: var(--text-muted);">/yr</span></span>
                        <form method="POST" action="{{ route('cart.add') }}" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="type" value="domain">
                            <input type="hidden" name="domain" value="${data.domain}">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                `;
            } else {
                box.style.background = 'rgba(239, 68, 68, 0.12)';
                box.style.border = '1px solid rgba(239, 68, 68, 0.35)';
                box.innerHTML = `
                    <div>
                        <div style="font-weight: 700; color: #f87171; font-size: 17px;">✗ <strong>${data.domain}</strong> is taken.</div>
                        <div style="font-size: 13px; color: var(--text-muted);">${data.reason || 'Try a different variation or choose an alternative extension.'}</div>
                    </div>
                `;
            }
        } catch (err) {
            box.style.display = 'flex';
            box.style.background = 'rgba(239, 68, 68, 0.12)';
            box.style.border = '1px solid rgba(239, 68, 68, 0.3)';
            box.innerHTML = `<div style="color: #f87171;">Failed to connect to registrar service. Please try again.</div>`;
        } finally {
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
        }
    }
</script>
@endsection
