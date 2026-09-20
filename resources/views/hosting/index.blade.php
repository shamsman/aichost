@extends('layouts.app')

@section('title', 'CWP Shared Web Hosting on Google Cloud — AICHost')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <!-- Header -->
    <div style="text-align: center; max-width: 840px; margin: 0 auto 50px;">
        <div class="badge-ai" style="margin-bottom: 16px;">Node: srv.shamsman.com:2083</div>
        <h1 style="font-size: clamp(34px, 5vw, 52px); font-weight: 800; margin-bottom: 18px;">
            High-Performance <span class="text-gradient-ai">CWP Shared Hosting</span>
        </h1>
        <p style="color: var(--text-muted); font-size: 17px; line-height: 1.6;">
            Optimized CentOS Web Panel hosting running on high-speed Google Cloud instances with automated SSL, Nginx reverse caching, MariaDB 10.11, and PHP 8.2 / 8.3 selector.
        </p>
    </div>

    <!-- Active CWP Node Status Banner -->
    <div class="glass-panel" style="max-width: 900px; margin: 0 auto 50px; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; border-color: rgba(2, 132, 199, 0.25);">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(2, 132, 199, 0.12); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                🖥️
            </div>
            <div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 15px;">Primary Production Server: srv.shamsman.com</div>
                <div style="font-size: 13px; color: var(--text-muted);">
                    Port: <strong>2083</strong> (SSL User Panel) • IP: <strong>{{ $cwpServer->ip_address ?? '185.193.64.1' }}</strong> • Region: <strong>us-central1</strong>
                </div>
            </div>
        </div>
        <a href="https://srv.shamsman.com:2083/" target="_blank" class="btn btn-outline btn-sm">
            Visit CWP Directly &rarr;
        </a>
    </div>

    <!-- Hosting Plans Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px; max-width: 1100px; margin: 0 auto 60px;">
        @foreach($hostingPlans as $plan)
            <div class="glass-panel" style="padding: 36px; position: relative; {{ ($plan->specs['popular'] ?? false) ? 'border-color: var(--primary-light); box-shadow: var(--glow-primary);' : '' }}">
                @if($plan->specs['popular'] ?? false)
                    <div style="position: absolute; top: -12px; right: 28px; background: linear-gradient(135deg, var(--primary), var(--accent-cyan)); color: #fff; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 4px 14px; border-radius: 20px;">
                        Recommended
                    </div>
                @endif

                <h3 style="font-size: 22px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">{{ $plan->name }}</h3>
                <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 24px;">{{ $plan->specs['badge'] ?? 'Shared Hosting' }}</p>

                <div style="margin-bottom: 24px;">
                    <span style="font-size: 42px; font-weight: 800; font-family: 'Space Grotesk', sans-serif;">
                        ${{ number_format($plan->price_monthly, 2) }}
                    </span>
                    <span style="color: var(--text-muted); font-size: 14px;">/month</span>
                </div>

                <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                        <span style="color: var(--text-muted);">Storage:</span>
                        <strong>{{ $plan->specs['disk'] ?? '20 GB NVMe' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                        <span style="color: var(--text-muted);">Websites:</span>
                        <strong>{{ $plan->specs['websites'] ?? '1 Website' }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px;">
                        <span style="color: var(--text-muted);">Control Panel:</span>
                        <strong style="color: var(--accent-cyan);">CWP Pro</strong>
                    </div>
                </div>

                <ul style="list-style: none; margin-bottom: 32px; display: flex; flex-direction: column; gap: 12px; font-size: 14px; color: var(--text-main);">
                    @foreach($plan->specs['features'] ?? [] as $feature)
                        <li style="display: flex; align-items: center; gap: 10px;">
                            <span style="color: var(--accent-cyan);">✓</span>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="type" value="hosting">
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="billing_cycle" value="monthly">
                    <button type="submit" class="btn {{ ($plan->specs['popular'] ?? false) ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;">
                        Order {{ $plan->name }}
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <!-- CWP Feature Stack Highlights -->
    <div class="glass-panel" style="padding: 40px; max-width: 1100px; margin: 0 auto;">
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 24px; text-align: center; color: var(--text-main);">Standard Stack Included in All CWP Plans</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; text-align: center;">
            <div>
                <div style="font-size: 28px; margin-bottom: 10px;">🔒</div>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">Free AutoSSL</h4>
                <p style="color: var(--text-muted); font-size: 13px;">Automated Let's Encrypt certificates provisioned on every domain.</p>
            </div>
            <div>
                <div style="font-size: 28px; margin-bottom: 10px;">⚡</div>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">Nginx + Varnish Cache</h4>
                <p style="color: var(--text-muted); font-size: 13px;">Reverse proxy acceleration delivering sub-millisecond TTFB response times.</p>
            </div>
            <div>
                <div style="font-size: 28px; margin-bottom: 10px;">🐘</div>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">PHP Multi-Version</h4>
                <p style="color: var(--text-muted); font-size: 13px;">Switch between PHP 7.4, 8.1, 8.2, and 8.3 instantly per directory.</p>
            </div>
            <div>
                <div style="font-size: 28px; margin-bottom: 10px;">💾</div>
                <h4 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">MariaDB 10.11</h4>
                <p style="color: var(--text-muted); font-size: 13px;">High-concurrency SQL database engines with phpMyAdmin management.</p>
            </div>
        </div>
    </div>
</div>
@endsection
