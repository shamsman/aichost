<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AICHost — Next-Gen AI Cloud Hosting & Google VM with CWP')</title>
    <meta name="description" content="@yield('meta_description', 'High-performance AI Cloud Hosting, automated CWP shared hosting, and custom Google Cloud Compute Engine VPS with instant domain registration.')">
    
    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg: #06080f;
            --bg-secondary: #0b101e;
            --surface: rgba(15, 23, 42, 0.65);
            --surface-hover: rgba(30, 41, 59, 0.85);
            --card-border: rgba(99, 102, 241, 0.18);
            --card-border-glow: rgba(99, 102, 241, 0.45);
            
            --primary: #6366f1;
            --primary-light: #818cf8;
            --accent-cyan: #06b6d4;
            --accent-purple: #a855f7;
            --accent-emerald: #10b981;
            
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
            
            --glow-primary: 0 0 30px rgba(99, 102, 241, 0.35);
            --glow-cyan: 0 0 30px rgba(6, 182, 212, 0.35);
            --glow-purple: 0 0 35px rgba(168, 85, 247, 0.35);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 25%, rgba(6, 182, 212, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 80%, rgba(168, 85, 247, 0.06) 0%, transparent 50%);
            background-attachment: fixed;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Ambient glowing elements */
        .glass-panel {
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-panel:hover {
            border-color: var(--card-border-glow);
            box-shadow: var(--glow-primary);
            transform: translateY(-2px);
        }

        .glass-nav {
            background: rgba(6, 8, 15, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.15);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 28px rgba(99, 102, 241, 0.6);
            color: #ffffff;
        }

        .btn-cyan {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, #0891b2 100%);
            color: #ffffff;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.35);
        }

        .btn-cyan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 28px rgba(6, 182, 212, 0.55);
            color: #ffffff;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-light);
            color: #ffffff;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #ffffff 30%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-ai {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-ai {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: var(--primary-light);
        }

        /* Container helper */
        .container {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Form Inputs */
        input, select, textarea {
            background: rgba(11, 16, 30, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #ffffff;
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        /* Alert notifications */
        .alert-banner {
            padding: 14px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .alert-success { background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
        .alert-error { background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; }
        .alert-warning { background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }

        /* Navbar Layout */
        .nav-wrapper {
            position: sticky;
            top: 0;
            z-index: 50;
            width: 100%;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
            font-size: 22px;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent-cyan) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
            font-size: 18px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: #ffffff;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .cart-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            text-decoration: none;
        }

        .cart-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--accent-cyan);
            color: #06080f;
            font-size: 11px;
            font-weight: 800;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Footer */
        footer {
            margin-top: 100px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(6, 8, 15, 0.95);
            padding: 60px 0 40px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }

        .footer-title {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--primary-light);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 20px;
            font-size: 12px;
            color: #34d399;
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.15); }
        }

        @media (max-width: 900px) {
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .nav-links { display: none; }
        }

        @media (max-width: 600px) {
            .footer-grid { grid-template-columns: 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navigation -->
    <div class="nav-wrapper">
        <nav class="glass-nav">
            <div class="container nav-inner">
                <a href="{{ route('home') }}" class="brand-logo">
                    <div class="brand-icon">⚡</div>
                    <span>AIC<span style="color: var(--accent-cyan);">Host</span></span>
                </a>

                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('domains.index') }}" class="{{ request()->routeIs('domains.*') ? 'active' : '' }}">Domains</a></li>
                    <li><a href="{{ route('hosting.index') }}" class="{{ request()->routeIs('hosting.*') ? 'active' : '' }}">CWP Hosting</a></li>
                    <li><a href="{{ route('vps.index') }}" class="{{ request()->routeIs('vps.*') ? 'active' : '' }}">Google Cloud VPS</a></li>
                    <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                </ul>

                <div class="nav-actions">
                    @php $cartCount = count(session('cart', [])); @endphp
                    <a href="{{ route('cart.index') }}" class="cart-badge" title="Shopping Cart">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @if($cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>

                    @auth
                        <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-sm">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Client Portal
                        </a>
                        <a href="{{ route('logout') }}" class="btn btn-outline btn-sm" title="Log Out">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Log In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content Area -->
    <main>
        <div class="container" style="padding-top: 24px;">
            @if(session('success'))
                <div class="alert-banner alert-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-banner alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert-banner alert-warning">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="brand-logo" style="margin-bottom: 16px;">
                        <div class="brand-icon">⚡</div>
                        <span>AIC<span style="color: var(--accent-cyan);">Host</span></span>
                    </a>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px; max-width: 320px;">
                        Enterprise Cloud Hosting powered by Google Cloud Platform and CentOS Web Panel. Ready for high-velocity web apps, AI model inference, and domain portfolios.
                    </p>
                    <div class="status-pill">
                        <span class="status-dot"></span>
                        <span>All Systems Operational (99.99% Google SLA)</span>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">Hosting Options</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('hosting.index') }}">CWP Shared Hosting</a></li>
                        <li><a href="{{ route('vps.index') }}">Google Cloud VPS</a></li>
                        <li><a href="{{ route('vps.index') }}">NVIDIA A100 GPU Servers</a></li>
                        <li><a href="{{ route('domains.index') }}">InternetBS Domain Search</a></li>
                        <li><a href="https://srv.shamsman.com:2031/" target="_blank">CWP Direct Login</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Platform</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard.index') }}">Client Portal</a></li>
                        <li><a href="{{ route('dashboard.services') }}">Active Services</a></li>
                        <li><a href="{{ route('dashboard.domains') }}">Domain Portfolio</a></li>
                        <li><a href="{{ route('dashboard.invoices') }}">Billing & Invoices</a></li>
                        <li><a href="{{ url('/migrate.php') }}">System Migrations</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">Infrastructure</h4>
                    <ul class="footer-links">
                        <li><span style="color: var(--text-muted); font-size: 13px;">Region: us-central1 (Iowa)</span></li>
                        <li><span style="color: var(--text-muted); font-size: 13px;">Panel: CentOS Web Panel</span></li>
                        <li><span style="color: var(--text-muted); font-size: 13px;">Registrar: InternetBS API</span></li>
                        <li><span style="color: var(--text-muted); font-size: 13px;">Server: srv.shamsman.com</span></li>
                        <li><span style="color: var(--text-muted); font-size: 13px;">Deploy: Google Cloud Run</span></li>
                    </ul>
                </div>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <p style="color: var(--text-dim); font-size: 13px;">
                    &copy; {{ date('Y') }} AICHost.com. All rights reserved. Powered by Google Cloud Infrastructure.
                </p>
                <div style="display: flex; gap: 16px; font-size: 13px; color: var(--text-dim);">
                    <a href="#" style="color: inherit; text-decoration: none;">Privacy Policy</a>
                    <a href="#" style="color: inherit; text-decoration: none;">Terms of Service</a>
                    <a href="#" style="color: inherit; text-decoration: none;">SLA Guarantee</a>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
