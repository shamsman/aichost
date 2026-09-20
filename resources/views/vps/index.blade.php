@extends('layouts.app')

@section('title', 'Google Cloud VPS with CWP — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 40px; padding-bottom: 80px;">
    <!-- Header -->
    <div style="text-align: center; max-width: 840px; margin: 0 auto 50px;">
        <div class="badge-ai" style="margin-bottom: 16px;">Google Cloud Compute Engine</div>
        <h1 style="font-size: clamp(34px, 5vw, 52px); font-weight: 800; margin-bottom: 18px;">
            Dedicated <span class="text-gradient-ai">Google VM VPS</span> with CWP Pre-Installed
        </h1>
        <p style="color: var(--text-muted); font-size: 17px; line-height: 1.6;">
            Deploy scalable virtual servers powered by Google Cloud Platform. Each instance automatically installs CentOS Web Panel on first boot, giving you a turnkey hosting powerhouse.
        </p>
    </div>

    <!-- Interactive Hardware Configurator Slider Box -->
    <div class="glass-panel" style="max-width: 900px; margin: 0 auto 60px; padding: 40px; border-color: rgba(79, 70, 229, 0.25);">
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 8px; color: var(--text-main);">Interactive VPS Hardware Configurator</h3>
        <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 30px;">
            Customize your Google Cloud virtual machine. Our provisioning service will generate the configuration and launch the instance with CWP automated startup.
        </p>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 36px; align-items: center;">
            <div>
                <!-- vCPU Slider -->
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: var(--text-muted);">Compute vCPU:</span>
                        <strong id="cpuVal" style="color: var(--accent-cyan);">2 vCPU (Intel Xeon / AMD EPYC)</strong>
                    </div>
                    <input type="range" id="cpuRange" min="2" max="16" step="2" value="2" oninput="updateConfig()" style="width: 100%; accent-color: var(--primary);">
                </div>

                <!-- RAM Slider -->
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: var(--text-muted);">Dedicated Memory:</span>
                        <strong id="ramVal" style="color: var(--accent-cyan);">8 GB DDR4</strong>
                    </div>
                    <input type="range" id="ramRange" min="4" max="64" step="4" value="8" oninput="updateConfig()" style="width: 100%; accent-color: var(--accent-cyan);">
                </div>

                <!-- SSD Storage Slider -->
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px;">
                        <span style="color: var(--text-muted);">Balanced Persistent NVMe:</span>
                        <strong id="diskVal" style="color: var(--accent-cyan);">80 GB NVMe</strong>
                    </div>
                    <input type="range" id="diskRange" min="50" max="500" step="25" value="80" oninput="updateConfig()" style="width: 100%; accent-color: var(--accent-purple);">
                </div>

                <!-- Datacenter Region Select -->
                <div>
                    <label style="display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; font-weight: 600;">Google Cloud Region</label>
                    <select id="regionSelect" style="width: 100%;" onchange="updateConfig()">
                        <option value="us-central1-a">us-central1 (Iowa, USA) - Recommended</option>
                        <option value="europe-west1-b">europe-west1 (Belgium, EU)</option>
                        <option value="asia-east1-a">asia-east1 (Taiwan, Asia)</option>
                    </select>
                </div>
            </div>

            <!-- Price Output & Deploy -->
            <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 16px; padding: 28px; text-align: center;">
                <div style="color: var(--text-muted); font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Estimated Cost</div>
                <div style="font-size: 44px; font-weight: 800; color: var(--text-main); font-family: 'Space Grotesk', sans-serif; margin-bottom: 4px;" id="totalPrice">
                    $28.00
                </div>
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">/month • billed monthly</div>

                <div style="font-size: 12px; color: var(--accent-emerald); margin-bottom: 20px; background: rgba(5, 150, 105, 0.1); padding: 8px; border-radius: 8px; font-weight: 600;">
                    ✓ CWP Included & Ready
                </div>

                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="type" value="vps">
                    <input type="hidden" id="selectedPlanId" name="plan_id" value="{{ $vpsPlans->first()->id ?? 1 }}">
                    <input type="hidden" name="billing_cycle" value="monthly">
                    <button type="submit" class="btn btn-cyan" style="width: 100%;">
                        Order This Build
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- VPS Standard Tiers Comparison -->
    <h3 style="font-size: 26px; font-weight: 800; text-align: center; margin-bottom: 36px; color: var(--text-main);">Pre-Configured Google Cloud Instances</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px; max-width: 1100px; margin: 0 auto;">
        @foreach($vpsPlans as $plan)
            <div class="glass-panel" style="padding: 36px; position: relative;">
                <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span class="badge-ai" style="font-size: 10px;">{{ $plan->specs['badge'] ?? 'Standard' }}</span>
                        <h4 style="font-size: 20px; font-weight: 700; color: var(--text-main); margin-top: 6px;">{{ $plan->name }}</h4>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <span style="font-size: 40px; font-weight: 800; font-family: 'Space Grotesk', sans-serif;">
                        ${{ number_format($plan->price_monthly, 2) }}
                    </span>
                    <span style="color: var(--text-muted); font-size: 14px;">/month</span>
                </div>

                <div style="background: var(--surface-hover); border: 1px solid var(--card-border); border-radius: 12px; padding: 16px; margin-bottom: 24px; font-size: 13px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div><strong>vCPU:</strong> {{ $plan->specs['vcpu'] }} vCPUs</div>
                    <div><strong>RAM:</strong> {{ $plan->specs['ram'] }}</div>
                    <div><strong>NVMe:</strong> {{ $plan->specs['disk'] }}</div>
                    <div><strong>GPU:</strong> {{ $plan->specs['gpu'] ?? 'None' }}</div>
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
                    <input type="hidden" name="billing_cycle" value="monthly">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Deploy Instance
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    const plans = @json($vpsPlans);

    function updateConfig() {
        const cpu = parseInt(document.getElementById('cpuRange').value);
        const ram = parseInt(document.getElementById('ramRange').value);
        const disk = parseInt(document.getElementById('diskRange').value);

        document.getElementById('cpuVal').textContent = `${cpu} vCPU (Intel / AMD)`;
        document.getElementById('ramVal').textContent = `${ram} GB High-Speed RAM`;
        document.getElementById('diskVal').textContent = `${disk} GB Persistent SSD`;

        // Calculate dynamic Google Cloud estimation
        const baseCost = (cpu * 8.5) + (ram * 1.5) + (disk * 0.08);
        document.getElementById('totalPrice').textContent = '$' + baseCost.toFixed(2);

        // Map to closest plan
        if (cpu >= 12) {
            document.getElementById('selectedPlanId').value = plans[2]?.id || plans[0]?.id;
        } else if (cpu >= 4) {
            document.getElementById('selectedPlanId').value = plans[1]?.id || plans[0]?.id;
        } else {
            document.getElementById('selectedPlanId').value = plans[0]?.id;
        }
    }
</script>
@endsection
