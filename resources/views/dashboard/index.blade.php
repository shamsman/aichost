@extends('layouts.app')

@section('title', 'Client Portal — AICHost')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <!-- Welcome Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Welcome back, {{ $user->name }}!</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Manage your Google Cloud infrastructure, CWP accounts, and registered domains.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('hosting.index') }}" class="btn btn-outline btn-sm">+ New CWP Account</a>
            <a href="{{ route('vps.index') }}" class="btn btn-cyan btn-sm">+ Launch Google VM</a>
            <a href="{{ route('domains.index') }}" class="btn btn-primary btn-sm">+ Register Domain</a>
        </div>
    </div>

    <!-- Stat Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 36px;">
        <div class="glass-panel" style="padding: 24px;">
            <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 6px;">Active Services</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif;">{{ $activeServicesCount }}</div>
            <div style="font-size: 12px; color: var(--accent-cyan); margin-top: 4px;">CWP & Google VMs</div>
        </div>

        <div class="glass-panel" style="padding: 24px;">
            <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 6px;">Domains Registered</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif;">{{ $domainsCount }}</div>
            <div style="font-size: 12px; color: var(--primary); margin-top: 4px;">InternetBS Managed</div>
        </div>

        <div class="glass-panel" style="padding: 24px;">
            <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 6px;">Account Credits</div>
            <div style="font-size: 32px; font-weight: 800; color: var(--accent-emerald); font-family: 'Space Grotesk', sans-serif;">${{ number_format($user->balance, 2) }}</div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Available for renewals</div>
        </div>

        <div class="glass-panel" style="padding: 24px;">
            <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 6px;">CWP Cluster Status</div>
            <div style="font-size: 18px; font-weight: 700; color: var(--accent-emerald); display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                <span class="status-dot"></span>
                <span>srv.shamsman.com</span>
            </div>
            <div style="font-size: 12px; color: var(--text-muted); margin-top: 8px;">Port 2083 Online</div>
        </div>
    </div>

    <!-- Active Cloud Services Section -->
    <div class="glass-panel" style="padding: 32px; margin-bottom: 40px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h3 style="font-size: 20px; font-weight: 700; color: var(--text-main);">Your Active Cloud Services</h3>
            <a href="{{ route('dashboard.services') }}" style="color: var(--accent-cyan); text-decoration: none; font-size: 14px;">View All &rarr;</a>
        </div>

        @if($services->isEmpty())
            <div style="text-align: center; padding: 40px 16px; color: var(--text-muted);">
                <p style="margin-bottom: 16px;">You don't have any active hosting or VPS instances yet.</p>
                <a href="{{ route('hosting.index') }}" class="btn btn-primary btn-sm">Provision CWP Hosting</a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($services as $svc)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; flex-wrap: wrap; gap: 16px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                                <span style="font-weight: 700; color: var(--text-main); font-size: 16px;">{{ $svc->label }}</span>
                                <span style="font-size: 11px; padding: 2px 8px; border-radius: 12px; background: rgba(5, 150, 105, 0.1); color: var(--accent-emerald); font-weight: 700; text-transform: uppercase;">{{ $svc->status }}</span>
                            </div>
                            <div style="font-size: 13px; color: var(--text-muted);">
                                @if($svc->hostingAccount)
                                    CWP User: <strong style="color: var(--text-main);">{{ $svc->hostingAccount->cwp_username }}</strong> • 
                                    IP: <strong>{{ $svc->hostingAccount->ip_address }}</strong> • 
                                    Package: <strong>{{ ucfirst($svc->hostingAccount->package_name) }}</strong>
                                @elseif($svc->vpsInstance)
                                    Google VM: <strong style="color: var(--text-main);">{{ $svc->vpsInstance->instance_name }}</strong> • 
                                    IP: <strong>{{ $svc->vpsInstance->external_ip }}</strong> • 
                                    Machine: <strong>{{ $svc->vpsInstance->machine_type }}</strong>
                                @else
                                    Billed {{ ucfirst($svc->billing_cycle) }} • Next due: {{ $svc->next_due_at?->format('M d, Y') ?? 'N/A' }}
                                @endif
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if($svc->hostingAccount)
                                <a href="{{ route('dashboard.services.cwp-sso', $svc->id) }}" target="_blank" class="btn btn-cyan btn-sm">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    1-Click CWP Login
                                </a>
                            @elseif($svc->vpsInstance)
                                <a href="https://{{ $svc->vpsInstance->external_ip }}:2083/" target="_blank" class="btn btn-cyan btn-sm">
                                    Open CWP Panel
                                </a>
                                <button type="button" class="btn btn-outline btn-sm" onclick="vpsAction('{{ $svc->vpsInstance->id }}', 'restart')">
                                    Reboot VM
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Domains & Invoices Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
        <!-- Domains -->
        <div class="glass-panel" style="padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">My Registered Domains</h3>
                <a href="{{ route('dashboard.domains') }}" style="color: var(--accent-cyan); font-size: 13px; text-decoration: none;">View all &rarr;</a>
            </div>

            @if($domains->isEmpty())
                <div style="color: var(--text-muted); font-size: 13px; text-align: center; padding: 24px;">No domains registered yet.</div>
            @else
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($domains->take(4) as $dom)
                        <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 8px; font-size: 13px;">
                            <div>
                                <strong style="color: var(--text-main);">{{ $dom->domain_name }}</strong>
                                <span style="font-size: 11px; color: var(--text-muted); display: block;">Expires {{ $dom->expires_at?->format('M d, Y') }}</span>
                            </div>
                            <span style="color: var(--accent-emerald); font-weight: 600;">Active</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Invoices -->
        <div class="glass-panel" style="padding: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-main);">Recent Invoices</h3>
                <a href="{{ route('dashboard.invoices') }}" style="color: var(--accent-cyan); font-size: 13px; text-decoration: none;">View all &rarr;</a>
            </div>

            @if($invoices->isEmpty())
                <div style="color: var(--text-muted); font-size: 13px; text-align: center; padding: 24px;">No invoices generated yet.</div>
            @else
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($invoices->take(4) as $inv)
                        <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 8px; font-size: 13px;">
                            <div>
                                <strong style="color: var(--text-main);">{{ $inv->invoice_number }}</strong>
                                <span style="font-size: 11px; color: var(--text-muted); display: block;">{{ $inv->paid_at?->format('M d, Y') ?? $inv->created_at->format('M d, Y') }}</span>
                            </div>
                            <div style="text-align: right;">
                                <strong style="color: var(--text-main);">${{ number_format($inv->total, 2) }}</strong>
                                <span style="font-size: 11px; color: var(--accent-emerald); display: block; font-weight: 700;">PAID</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function vpsAction(instanceId, action) {
        if (!confirm(`Are you sure you want to ${action} this Google Cloud VM?`)) return;

        try {
            const res = await fetch(`/dashboard/vps/${instanceId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            const data = await res.json();
            alert(data.message || `Action ${action} executed.`);
            location.reload();
        } catch (e) {
            alert('Operation failed: ' + e.message);
        }
    }
</script>
@endsection
