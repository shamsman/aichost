@extends('layouts.app')

@section('title', 'My Cloud Services — AICHost')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">My Hosting & Cloud Services</h1>
            <p style="color: var(--text-muted); font-size: 14px;">CentOS Web Panel (CWP) shared accounts and Google Cloud VMs.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('hosting.index') }}" class="btn btn-outline btn-sm">+ Add CWP Hosting</a>
            <a href="{{ route('vps.index') }}" class="btn btn-primary btn-sm">+ Deploy Google VM</a>
        </div>
    </div>

    @if($services->isEmpty())
        <div class="glass-panel" style="padding: 60px; text-align: center;">
            <h3 style="font-size: 18px; margin-bottom: 8px; color: var(--text-main);">No cloud services active</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">Deploy your first website on CWP or create a Google VM.</p>
            <a href="{{ route('hosting.index') }}" class="btn btn-primary">Choose a Hosting Plan</a>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($services as $svc)
                <div class="glass-panel" style="padding: 28px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 6px;">
                                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">{{ $svc->label }}</h3>
                                <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 12px; text-transform: uppercase;">
                                    {{ $svc->status }}
                                </span>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                Billing: <strong style="color: var(--text-main);">${{ number_format($svc->amount, 2) }} / {{ $svc->billing_cycle }}</strong> • Next due: <strong style="color: var(--text-main);">{{ $svc->next_due_at?->format('M d, Y') ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        <div>
                            @if($svc->hostingAccount)
                                <a href="{{ route('dashboard.services.cwp-sso', $svc->id) }}" target="_blank" class="btn btn-cyan btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    One-Click CWP SSO Login
                                </a>
                            @elseif($svc->vpsInstance)
                                <a href="{{ $svc->vpsInstance->cwp_url }}" target="_blank" class="btn btn-cyan btn-sm">
                                    Open CWP ({{ $svc->vpsInstance->external_ip }}:2083)
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Technical Credentials Box -->
                    <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 10px; padding: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; font-size: 13px;">
                        @if($svc->hostingAccount)
                            <div><span style="color: var(--text-muted);">CWP Server:</span> <strong style="color: var(--text-main);">srv.shamsman.com:2083</strong></div>
                            <div><span style="color: var(--text-muted);">CWP Username:</span> <code style="color: var(--accent-cyan); font-weight: 600;">{{ $svc->hostingAccount->cwp_username }}</code></div>
                            <div><span style="color: var(--text-muted);">Assigned IP:</span> <strong style="color: var(--text-main);">{{ $svc->hostingAccount->ip_address }}</strong></div>
                            <div><span style="color: var(--text-muted);">Package:</span> <strong style="color: var(--text-main);">{{ ucfirst($svc->hostingAccount->package_name) }}</strong></div>
                        @elseif($svc->vpsInstance)
                            <div><span style="color: var(--text-muted);">Instance:</span> <strong style="color: var(--text-main);">{{ $svc->vpsInstance->instance_name }}</strong></div>
                            <div><span style="color: var(--text-muted);">Google Zone:</span> <strong style="color: var(--text-main);">{{ $svc->vpsInstance->gcp_zone }}</strong></div>
                            <div><span style="color: var(--text-muted);">External Static IP:</span> <code style="color: var(--accent-cyan); font-weight: 600;">{{ $svc->vpsInstance->external_ip }}</code></div>
                            <div><span style="color: var(--text-muted);">Machine Type:</span> <strong style="color: var(--text-main);">{{ $svc->vpsInstance->machine_type }}</strong></div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
