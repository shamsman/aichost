@extends('layouts.app')

@section('title', 'AICHost — AI Cloud Hosting & Google VM with CWP')

@section('content')
<!-- Hero Section -->
<section style="padding: 60px 0 80px; position: relative;">
    <div class="container" style="text-align: center;">
        <div style="display: inline-flex; margin-bottom: 24px;">
            <div class="badge-ai">
                <span style="color: var(--accent-cyan);">●</span> Powered by Google Cloud & CentOS Web Panel
            </div>
        </div>

        <h1 style="font-size: clamp(38px, 6vw, 68px); font-weight: 800; line-height: 1.1; margin-bottom: 24px; max-width: 960px; margin-left: auto; margin-right: auto;">
            Enterprise <span class="text-gradient-ai">AI Cloud Hosting</span> Built on Google Infrastructure
        </h1>

        <p style="font-size: clamp(16px, 2vw, 20px); color: var(--text-muted); max-width: 680px; margin: 0 auto 40px; line-height: 1.6;">
            Deploy high-velocity websites on CWP shared hosting or launch dedicated Google Cloud VMs pre-loaded with CWP and AI accelerators.
        </p>

        <!-- Live Domain Search Bar (Connected to InternetBS API) -->
        <div class="glass-panel" style="max-width: 780px; margin: 0 auto 50px; padding: 12px; box-shadow: var(--shadow-card);">
            <form id="domainSearchForm" onsubmit="searchDomain(event)" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 260px; position: relative;">
                    <input type="text" id="domainInput" placeholder="Search your AI domain (e.g. neuroflow.ai, deepmind.cloud)" style="width: 100%; height: 52px; font-size: 16px; padding-left: 20px; border-radius: 12px;" required>
                </div>
                <button type="submit" id="searchBtn" class="btn btn-cyan" style="height: 52px; min-width: 160px; font-size: 15px;">
                    <span id="btnText">Check Domain</span>
                    <span id="btnSpinner" style="display: none;">Checking...</span>
                </button>
            </form>

            <!-- Domain Search Result Banner (AJAX Live) -->
            <div id="domainResultBox" style="display: none; margin-top: 16px; padding: 16px 20px; border-radius: 12px; text-align: left; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;"></div>

            <!-- Fast TLD Pills -->
            <div style="display: flex; justify-content: center; gap: 12px; margin-top: 14px; flex-wrap: wrap;">
                @foreach($domainPlans->take(5) as $plan)
                    <div style="font-size: 12px; color: var(--text-muted); background: var(--surface-hover); padding: 4px 12px; border-radius: 20px; border: 1px solid var(--card-border); cursor: pointer;" onclick="document.getElementById('domainInput').value = 'mybrand{{ $plan->specs['tld'] ?? '.ai' }}'; searchDomain(event);">
                        <strong style="color: var(--text-main);">{{ $plan->specs['tld'] ?? '.com' }}</strong> ${{ number_format($plan->price_monthly, 2) }}/yr
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Infrastructure Trust Badges -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; max-width: 1000px; margin: 0 auto;">
            <div class="glass-panel" style="padding: 24px; text-align: left;">
                <div style="color: var(--accent-cyan); font-size: 24px; margin-bottom: 8px;">🚀</div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 16px;">Google Cloud Core</div>
                <div style="color: var(--text-muted); font-size: 13px;">Compute Engine VMs with direct peering and global VPC networks.</div>
            </div>
            <div class="glass-panel" style="padding: 24px; text-align: left;">
                <div style="color: var(--primary); font-size: 24px; margin-bottom: 8px;">⚙️</div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 16px;">CWP Control Panel</div>
                <div style="color: var(--text-muted); font-size: 13px;">Instant SSO at srv.shamsman.com:2083 with full MariaDB, SSL & PHP 8.3.</div>
            </div>
            <div class="glass-panel" style="padding: 24px; text-align: left;">
                <div style="color: var(--accent-purple); font-size: 24px; margin-bottom: 8px;">🌐</div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 16px;">InternetBS Reseller API</div>
                <div style="color: var(--text-muted); font-size: 13px;">Automated instant domain registration and DNS records management.</div>
            </div>
            <div class="glass-panel" style="padding: 24px; text-align: left;">
                <div style="color: var(--accent-emerald); font-size: 24px; margin-bottom: 8px;">🛡️</div>
                <div style="font-weight: 700; color: var(--text-main); font-size: 16px;">Google Cloud Run Ready</div>
                <div style="color: var(--text-muted); font-size: 13px;">Stateless microservice container architecture with zero SSH dependency.</div>
            </div>
        </div>
    </div>
</section>

<!-- Hosting Options (Shared on CWP vs Google VM VPS) -->
<section id="pricing" style="padding: 80px 0; border-top: 1px solid var(--card-border);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 50px;">
            <div class="badge-ai" style="margin-bottom: 16px;">Transparent Pricing</div>
            <h2 style="font-size: clamp(30px, 4vw, 44px); font-weight: 800; margin-bottom: 16px;">
                Choose Your AI Hosting Architecture
            </h2>
            <p style="color: var(--text-muted); font-size: 16px; max-width: 600px; margin: 0 auto;">
                Scale from shared web hosting on CWP to dedicated Google Cloud instances with CWP pre-configured.
            </p>

            <!-- Billing Cycle Switcher -->
            <div style="display: inline-flex; align-items: center; gap: 12px; background: var(--surface-hover); padding: 6px; border-radius: 30px; border: 1px solid var(--card-border); margin-top: 28px;">
                <button type="button" id="btnMonthly" onclick="setBillingCycle('monthly')" style="padding: 8px 20px; border-radius: 20px; border: none; font-weight: 600; font-size: 13px; cursor: pointer; background: var(--primary); color: #fff;">Monthly Billing</button>
                <button type="button" id="btnAnnually" onclick="setBillingCycle('annually')" style="padding: 8px 20px; border-radius: 20px; border: none; font-weight: 600; font-size: 13px; cursor: pointer; background: transparent; color: var(--text-muted);">
                    Annual (Save 20% + Free Domain)
                </button>
            </div>
        </div>

        <!-- Section 1: Shared Hosting Plans (CWP) -->
        <div style="margin-bottom: 60px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <div>
                    <h3 style="font-size: 22px; font-weight: 700; color: var(--text-main);">1. Shared Hosting on CWP</h3>
                    <p style="color: var(--text-muted); font-size: 14px;">Hosted on high-speed server cluster at <strong style="color: var(--accent-cyan);">srv.shamsman.com:2083</strong></p>
                </div>
                <a href="{{ route('hosting.index') }}" class="btn btn-outline btn-sm">Compare All Shared Plans &rarr;</a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                @foreach($hostingPlans as $plan)
                    <div class="glass-panel" style="padding: 32px; position: relative; {{ ($plan->specs['popular'] ?? false) ? 'border-color: var(--primary-light); box-shadow: var(--glow-primary);' : '' }}">
                        @if($plan->specs['popular'] ?? false)
                            <div style="position: absolute; top: -12px; right: 24px; background: linear-gradient(135deg, var(--primary), var(--accent-cyan)); color: #fff; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; letter-spacing: 0.05em;">
                                Highly Recommended
                            </div>
                        @endif

                        <div style="font-size: 18px; font-weight: 700; color: var(--text-main); margin-bottom: 6px;">{{ $plan->name }}</div>
                        <div style="color: var(--text-muted); font-size: 13px; margin-bottom: 20px;">{{ $plan->specs['badge'] ?? 'Cloud Hosting' }}</div>

                        <div style="margin-bottom: 24px;">
                            <span style="font-size: 38px; font-weight: 800; font-family: 'Space Grotesk', sans-serif;" class="plan-price" data-monthly="{{ $plan->price_monthly }}" data-annually="{{ $plan->price_annually }}">
                                ${{ number_format($plan->price_monthly, 2) }}
                            </span>
                            <span style="color: var(--text-muted); font-size: 14px;" class="billing-period">/month</span>
                        </div>

                        <ul style="list-style: none; margin-bottom: 28px; display: flex; flex-direction: column; gap: 12px; font-size: 14px; color: var(--text-main);">
                            @foreach($plan->specs['features'] ?? [] as $feat)
                                <li style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: var(--accent-cyan);">✓</span>
                                    <span>{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="type" value="hosting">
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="billing_cycle" class="input-cycle" value="monthly">
                            <button type="submit" class="btn {{ ($plan->specs['popular'] ?? false) ? 'btn-primary' : 'btn-outline' }}" style="width: 100%;">
                                Order with CWP
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Google Cloud VPS (with CWP pre-installed) -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <div>
                    <h3 style="font-size: 22px; font-weight: 700; color: var(--text-main);">2. Dedicated Google Cloud VM with CWP</h3>
                    <p style="color: var(--text-muted); font-size: 14px;">Compute Engine VMs provisioned on Google Cloud infrastructure with CWP pre-installed.</p>
                </div>
                <a href="{{ route('vps.index') }}" class="btn btn-outline btn-sm">Explore VPS Hardware &rarr;</a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
                @foreach($vpsPlans as $plan)
                    <div class="glass-panel" style="padding: 32px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                            <div>
                                <span class="badge-ai" style="font-size: 10px; margin-bottom: 8px;">{{ $plan->specs['cpu_family'] ?? 'Google VM' }}</span>
                                <h4 style="font-size: 20px; font-weight: 700; color: var(--text-main);">{{ $plan->name }}</h4>
                            </div>
                            <div style="background: rgba(2, 132, 199, 0.1); border: 1px solid rgba(2, 132, 199, 0.25); color: var(--accent-cyan); padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700;">
                                {{ $plan->specs['cwp_status'] ?? 'CWP Included' }}
                            </div>
                        </div>

                        <div style="margin-bottom: 24px;">
                            <span style="font-size: 38px; font-weight: 800; font-family: 'Space Grotesk', sans-serif;" class="plan-price" data-monthly="{{ $plan->price_monthly }}" data-annually="{{ $plan->price_annually }}">
                                ${{ number_format($plan->price_monthly, 2) }}
                            </span>
                            <span style="color: var(--text-muted); font-size: 14px;" class="billing-period">/month</span>
                        </div>

                        <!-- Hardware Breakdown Specs -->
                        <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 10px; padding: 14px; margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 13px;">
                            <div><strong style="color: var(--text-muted);">vCPU:</strong> {{ $plan->specs['vcpu'] }} Core</div>
                            <div><strong style="color: var(--text-muted);">RAM:</strong> {{ $plan->specs['ram'] }}</div>
                            <div><strong style="color: var(--text-muted);">Disk:</strong> {{ $plan->specs['disk'] }}</div>
                            <div><strong style="color: var(--text-muted);">Network:</strong> {{ $plan->specs['network'] }}</div>
                        </div>

                        <ul style="list-style: none; margin-bottom: 28px; display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: var(--text-muted);">
                            @foreach($plan->specs['features'] ?? [] as $feat)
                                <li style="display: flex; align-items: center; gap: 8px;">
                                    <span style="color: var(--accent-cyan);">✓</span>
                                    <span style="color: var(--text-main);">{{ $feat }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="type" value="vps">
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="billing_cycle" class="input-cycle" value="monthly">
                            <button type="submit" class="btn btn-cyan" style="width: 100%;">
                                Deploy Google VM VPS
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- CWP & Google Cloud Architecture Spotlight -->
<section style="padding: 80px 0; background: var(--surface-hover); border-top: 1px solid var(--card-border);">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;">
            <div>
                <div class="badge-ai" style="margin-bottom: 16px;">Zero Complexity Panel</div>
                <h2 style="font-size: clamp(28px, 3.5vw, 40px); font-weight: 800; margin-bottom: 20px; color: var(--text-main);">
                    CentOS Web Panel (CWP) Integrated at <span class="text-gradient-ai">srv.shamsman.com:2083</span>
                </h2>
                <p style="color: var(--text-muted); font-size: 16px; margin-bottom: 24px; line-height: 1.7;">
                    Experience complete freedom with automated account creation, package synchronization, and one-click SSO login into CWP directly from your AI Cloud Host customer portal.
                </p>

                <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 30px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="background: rgba(79, 70, 229, 0.12); color: var(--primary); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                        <div>
                            <strong style="color: var(--text-main);">Instant Account Creation:</strong>
                            <p style="color: var(--text-muted); font-size: 14px;">Zero waiting time. Once an order is completed, our API calls CWP automatically to set up domains, quota, and login credentials.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="background: rgba(2, 132, 199, 0.12); color: var(--accent-cyan); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                        <div>
                            <strong style="color: var(--text-main);">Single Sign-On (SSO):</strong>
                            <p style="color: var(--text-muted); font-size: 14px;">Access your CWP panel at <code>https://srv.shamsman.com:2083/</code> with 1 click without needing to retype passwords.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="background: rgba(147, 51, 234, 0.12); color: var(--accent-purple); width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                        <div>
                            <strong style="color: var(--text-main);">Google Cloud Reliability:</strong>
                            <p style="color: var(--text-muted); font-size: 14px;">Every hosting account runs on Google Cloud Compute with tier-1 network latency and persistent SSD storage.</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('hosting.index') }}" class="btn btn-primary">Get Started with CWP Hosting</a>
            </div>

            <!-- Terminal / Code Preview Mockup -->
            <div class="glass-panel terminal-preview" style="padding: 24px; border: 1px solid #1e293b;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 12px;">
                    <div style="display: flex; gap: 8px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></div>
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;"></div>
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: #10b981;"></div>
                    </div>
                    <span style="font-size: 12px; color: #94a3b8; font-family: monospace;">cwp-provisioning-stream.log</span>
                </div>
                <div style="font-family: monospace; font-size: 13px; line-height: 1.7; color: #93c5fd;">
                    <p style="color: #34d399;">$ aichost provision --service=cwp-starter --domain=myproject.ai</p>
                    <p style="color: #94a3b8;">[22:30:12] Contacting CWP Node at https://srv.shamsman.com:2083/api/ ...</p>
                    <p style="color: #60a5fa;">[22:30:13] Hostname verified: srv.shamsman.com [185.193.64.1]</p>
                    <p style="color: #60a5fa;">[22:30:14] Generating user 'u849204' with 20GB NVMe storage...</p>
                    <p style="color: #34d399;">[22:30:15] CWP Account created successfully (Account ID: 4192)</p>
                    <p style="color: #a78bfa;">[22:30:15] Configuring DNS on InternetBS Reseller API...</p>
                    <p style="color: #34d399;">[22:30:16] Nameservers set: ns1.aichost.com, ns2.aichost.com</p>
                    <p style="color: #38bdf8;">[22:30:17] One-click SSO link ready: https://srv.shamsman.com:2083/cpanel/?session=active</p>
                    <p style="color: #10b981; font-weight: bold; margin-top: 8px;">✓ Service fully active on Google Cloud infrastructure.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Frequently Asked Questions -->
<section style="padding: 80px 0;">
    <div class="container" style="max-width: 860px;">
        <div style="text-align: center; margin-bottom: 48px;">
            <div class="badge-ai" style="margin-bottom: 14px;">Got Questions?</div>
            <h2 style="font-size: 36px; font-weight: 800; color: var(--text-main);">Frequently Asked Questions</h2>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div class="glass-panel" style="padding: 24px;">
                <h4 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">How does domain registration work with InternetBS?</h4>
                <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">
                    Our platform connects directly to our InternetBS reseller account via API. When you register a domain (.com, .ai, .io, etc.), it is instantly registered in your name, configured with DNS records, and protected with free WHOIS privacy.
                </p>
            </div>

            <div class="glass-panel" style="padding: 24px;">
                <h4 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Where is the CWP control panel located?</h4>
                <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">
                    Our primary CentOS Web Panel server is hosted at <a href="https://srv.shamsman.com:2083/" target="_blank" style="color: var(--accent-cyan); text-decoration: underline;">https://srv.shamsman.com:2083/</a>. You can manage files, databases, mailboxes, and SSL directly through CWP or using our 1-click SSO button inside your AI Cloud Host dashboard.
                </p>
            </div>

            <div class="glass-panel" style="padding: 24px;">
                <h4 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Can I get a custom Google Cloud VM with CWP pre-installed?</h4>
                <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">
                    Yes! Our VPS options run on Google Cloud Compute Engine. Upon provisioning, our automated startup script installs CentOS Stream / AlmaLinux with CWP and secures the firewall rules so your server is ready immediately upon first boot.
                </p>
            </div>

            <div class="glass-panel" style="padding: 24px;">
                <h4 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 8px;">How are live database migrations performed?</h4>
                <p style="color: var(--text-muted); font-size: 14px; line-height: 1.6;">
                    To keep the system completely decoupled from SSH, live server migrations are executed securely via our web runner endpoint at <code>https://aichost.com/migrate.php?token=YOUR_MIGRATE_SECRET</code> after code is deployed from GitHub.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Live AJAX Domain Search
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
                box.style.background = 'rgba(5, 150, 105, 0.08)';
                box.style.border = '1px solid rgba(5, 150, 105, 0.3)';
                box.innerHTML = `
                    <div>
                        <div style="font-weight: 700; color: #059669; font-size: 16px;">✓ <strong>${data.domain}</strong> is available!</div>
                        <div style="font-size: 13px; color: var(--text-muted);">Instant registration via InternetBS API with WHOIS privacy included.</div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <span style="font-size: 22px; font-weight: 800; color: var(--text-main);">$${parseFloat(data.price).toFixed(2)}<span style="font-size: 13px; color: var(--text-muted);">/yr</span></span>
                        <form method="POST" action="{{ route('cart.add') }}" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="type" value="domain">
                            <input type="hidden" name="domain" value="${data.domain}">
                            <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                        </form>
                    </div>
                `;
            } else {
                box.style.background = 'rgba(239, 68, 68, 0.08)';
                box.style.border = '1px solid rgba(239, 68, 68, 0.3)';
                box.innerHTML = `
                    <div>
                        <div style="font-weight: 700; color: #dc2626; font-size: 16px;">✗ <strong>${data.domain}</strong> is already taken.</div>
                        <div style="font-size: 13px; color: var(--text-muted);">${data.reason || 'Try a different keyword or choose another TLD like .ai, .io, or .cloud'}</div>
                    </div>
                    <button class="btn btn-outline btn-sm" onclick="document.getElementById('domainInput').value = '${data.domain.split('.')[0]}.ai'; searchDomain();">Try with .ai</button>
                `;
            }
        } catch (err) {
            box.style.display = 'flex';
            box.style.background = 'rgba(239, 68, 68, 0.08)';
            box.style.border = '1px solid rgba(239, 68, 68, 0.25)';
            box.innerHTML = `<div style="color: #dc2626;">Failed to connect to domain availability service. Please try again.</div>`;
        } finally {
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
        }
    }

    // Billing Cycle Switcher (Monthly vs Annually)
    function setBillingCycle(cycle) {
        const btnM = document.getElementById('btnMonthly');
        const btnA = document.getElementById('btnAnnually');
        const prices = document.querySelectorAll('.plan-price');
        const periods = document.querySelectorAll('.billing-period');
        const inputs = document.querySelectorAll('.input-cycle');

        if (cycle === 'annually') {
            btnA.style.background = 'var(--primary)';
            btnA.style.color = '#fff';
            btnM.style.background = 'transparent';
            btnM.style.color = 'var(--text-muted)';

            prices.forEach(el => {
                const annually = el.getAttribute('data-annually');
                if (annually) {
                    el.textContent = '$' + parseFloat(annually).toFixed(2);
                }
            });
            periods.forEach(el => el.textContent = '/year');
            inputs.forEach(el => el.value = 'annually');
        } else {
            btnM.style.background = 'var(--primary)';
            btnM.style.color = '#fff';
            btnA.style.background = 'transparent';
            btnA.style.color = 'var(--text-muted)';

            prices.forEach(el => {
                const monthly = el.getAttribute('data-monthly');
                if (monthly) {
                    el.textContent = '$' + parseFloat(monthly).toFixed(2);
                }
            });
            periods.forEach(el => el.textContent = '/month');
            inputs.forEach(el => el.value = 'monthly');
        }
    }
</script>
@endsection
