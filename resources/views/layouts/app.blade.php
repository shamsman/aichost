<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Cloud Host — Cloud Hosting & Google VM with CWP')</title>
    <meta name="description" content="@yield('meta_description', 'High-performance AI Cloud Hosting, automated CWP shared hosting, and custom Google Cloud Compute Engine VPS with instant domain registration.')">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('aichost_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root, [data-theme="light"] {
            color-scheme: light;
            --bg: #f8fafc;
            --bg-secondary: #ffffff;
            --surface: #ffffff;
            --surface-glass: rgba(255, 255, 255, 0.88);
            --surface-hover: #f1f5f9;
            --card-border: #e2e8f0;
            --card-border-glow: rgba(79, 70, 229, 0.3);
            --border: #e2e8f0;
            --border-subtle: #f1f5f9;
            --border-strong: #cbd5e1;
            
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-soft: rgba(79, 70, 229, 0.08);
            --primary-soft-border: rgba(79, 70, 229, 0.18);
            
            --accent-cyan: #0284c7;
            --accent-purple: #9333ea;
            --accent-emerald: #059669;
            
            --text-main: #0f172a;
            --text-muted: #475569;
            --text-dim: #94a3b8;
            
            --glow-primary: 0 10px 25px -3px rgba(79, 70, 229, 0.12), 0 4px 6px -4px rgba(79, 70, 229, 0.08);
            --glow-cyan: 0 10px 25px -3px rgba(2, 132, 199, 0.12);
            --glow-purple: 0 10px 25px -3px rgba(147, 51, 234, 0.12);
            --shadow-card: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            --shadow-card-hover: 0 16px 32px -4px rgba(79, 70, 229, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            
            --nav-bg: rgba(255, 255, 255, 0.88);
            --footer-bg: #ffffff;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
        }

        [data-theme="dark"] {
            color-scheme: dark;
            --bg: #06080f;
            --bg-secondary: #0b101e;
            --surface: rgba(15, 23, 42, 0.65);
            --surface-glass: rgba(15, 23, 42, 0.65);
            --surface-hover: rgba(30, 41, 59, 0.85);
            --card-border: rgba(99, 102, 241, 0.18);
            --card-border-glow: rgba(99, 102, 241, 0.45);
            --border: rgba(99, 102, 241, 0.18);
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-strong: rgba(255, 255, 255, 0.15);
            
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-soft: rgba(99, 102, 241, 0.15);
            --primary-soft-border: rgba(99, 102, 241, 0.3);
            
            --accent-cyan: #06b6d4;
            --accent-purple: #a855f7;
            --accent-emerald: #10b981;
            
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
            
            --glow-primary: 0 0 30px rgba(99, 102, 241, 0.35);
            --glow-cyan: 0 0 30px rgba(6, 182, 212, 0.35);
            --glow-purple: 0 0 35px rgba(168, 85, 247, 0.35);
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.3);
            --shadow-card-hover: 0 0 30px rgba(99, 102, 241, 0.35);
            
            --nav-bg: rgba(6, 8, 15, 0.85);
            --footer-bg: rgba(6, 8, 15, 0.95);
            --input-bg: rgba(11, 16, 30, 0.8);
            --input-border: rgba(255, 255, 255, 0.12);
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
                radial-gradient(circle at 15% 15%, rgba(79, 70, 229, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 85% 25%, rgba(2, 132, 199, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 50% 80%, rgba(147, 51, 234, 0.03) 0%, transparent 50%);
            background-attachment: fixed;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        [data-theme="dark"] body {
            background-image: 
                radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 85% 25%, rgba(6, 182, 212, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 80%, rgba(168, 85, 247, 0.06) 0%, transparent 50%);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        /* Ambient cards & glass panels */
        .glass-panel {
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-panel:hover {
            border-color: var(--card-border-glow);
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-2px);
        }

        .glass-nav {
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--card-border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: background 0.25s ease, border-color 0.25s ease;
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
            background: linear-gradient(135deg, var(--primary) 0%, #4338ca 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(79, 70, 229, 0.4);
            color: #ffffff !important;
        }

        .btn-cyan {
            background: linear-gradient(135deg, var(--accent-cyan) 0%, #0369a1 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.25);
        }

        .btn-cyan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(2, 132, 199, 0.4);
            color: #ffffff !important;
        }

        .btn-outline {
            background: var(--bg-secondary);
            color: var(--text-main);
            border: 1px solid var(--border-strong);
        }

        .btn-outline:hover {
            background: var(--surface-hover);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #0f172a 30%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        [data-theme="dark"] .text-gradient {
            background: linear-gradient(135deg, #ffffff 30%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-ai {
            background: linear-gradient(135deg, #0284c7 0%, #4f46e5 50%, #9333ea 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        [data-theme="dark"] .text-gradient-ai {
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
            background: var(--primary-soft);
            border: 1px solid var(--primary-soft-border);
            color: var(--primary);
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
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px;
            color: var(--text-main);
            padding: 12px 16px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
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
        .alert-success { background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.25); color: #065f46; }
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #991b1b; }
        .alert-warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.25); color: #92400e; }

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
            color: var(--text-main);
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
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            font-size: 18px;
            color: #ffffff !important;
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
            color: var(--primary);
            font-weight: 600;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .cart-badge, .theme-toggle-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-strong);
            color: var(--text-main);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cart-badge:hover, .theme-toggle-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--surface-hover);
        }

        .cart-count {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--primary);
            color: #ffffff !important;
            font-size: 11px;
            font-weight: 800;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            color: var(--text-muted);
            border-bottom: 1px solid var(--card-border);
            padding: 14px 16px;
            font-weight: 600;
            font-size: 13px;
        }
        table td {
            color: var(--text-main);
            border-bottom: 1px solid var(--card-border);
            padding: 16px;
        }
        table tbody tr:hover {
            background-color: var(--surface-hover);
        }

        /* Footer */
        footer {
            margin-top: 100px;
            border-top: 1px solid var(--card-border);
            background: var(--footer-bg);
            padding: 60px 0 40px;
            transition: background 0.25s ease;
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
            color: var(--text-main);
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
            color: var(--primary);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: rgba(5, 150, 105, 0.08);
            border: 1px solid rgba(5, 150, 105, 0.25);
            border-radius: 20px;
            font-size: 12px;
            color: var(--accent-emerald);
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-emerald);
            box-shadow: 0 0 10px var(--accent-emerald);
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.15); }
        }

        /* Seamless Light Mode Compatibility Bridge for View Inline Styles */
        [data-theme="light"] [style*="color: #fff"], 
        [data-theme="light"] [style*="color:#fff"],
        [data-theme="light"] [style*="color: #ffffff"], 
        [data-theme="light"] [style*="color:#ffffff"],
        [data-theme="light"] [style*="color: white"] {
            color: var(--text-main) !important;
        }

        /* Preserve white text for filled buttons and specific indicators */
        [data-theme="light"] .btn-primary, [data-theme="light"] .btn-primary *,
        [data-theme="light"] .btn-cyan, [data-theme="light"] .btn-cyan *,
        [data-theme="light"] .brand-icon, [data-theme="light"] .cart-count {
            color: #ffffff !important;
        }

        /* Legacy dark boxes convert into clean light cards */
        [data-theme="light"] [style*="background: rgba(0,0,0"], 
        [data-theme="light"] [style*="background: rgba(0, 0, 0"],
        [data-theme="light"] [style*="background: rgba(8, 12, 22"], 
        [data-theme="light"] [style*="background: rgba(8,12,22"],
        [data-theme="light"] [style*="background: rgba(11, 16, 30"], 
        [data-theme="light"] [style*="background: rgba(16, 24, 40"],
        [data-theme="light"] [style*="background: #0b101e"], 
        [data-theme="light"] [style*="background:#0b101e"] {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }

        /* Translucent white borders convert to clean slate borders */
        [data-theme="light"] [style*="border: 1px solid rgba(255,255,255"],
        [data-theme="light"] [style*="border: 1px solid rgba(255, 255, 255"],
        [data-theme="light"] [style*="border-color: rgba(255,255,255"],
        [data-theme="light"] [style*="border-color: rgba(255, 255, 255"],
        [data-theme="light"] [style*="border-top: 1px solid rgba(255,255,255"],
        [data-theme="light"] [style*="border-top: 1px solid rgba(255, 255, 255"],
        [data-theme="light"] [style*="border-bottom: 1px solid rgba(255,255,255"],
        [data-theme="light"] [style*="border-bottom: 1px solid rgba(255, 255, 255"] {
            border-color: #e2e8f0 !important;
        }

        /* Terminal code box formatting */
        .terminal-preview {
            background: #090d16 !important;
            border: 1px solid #1e293b !important;
            box-shadow: 0 20px 40px -8px rgba(15, 23, 42, 0.25) !important;
        }
        .terminal-preview * {
            color: inherit;
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
                    <span>AI Cloud <span style="color: var(--accent-cyan);">Host</span></span>
                </a>

                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('domains.index') }}" class="{{ request()->routeIs('domains.*') ? 'active' : '' }}">Domains</a></li>
                    <li><a href="{{ route('hosting.index') }}" class="{{ request()->routeIs('hosting.*') ? 'active' : '' }}">CWP Hosting</a></li>
                    <li><a href="{{ route('vps.index') }}" class="{{ request()->routeIs('vps.*') ? 'active' : '' }}">Google Cloud VPS</a></li>
                    <li><a href="{{ route('home') }}#pricing">Pricing</a></li>
                </ul>

                <div class="nav-actions">
                    <!-- Light / Dark Mode Toggle -->
                    <button type="button" class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" title="Toggle Light / Dark Mode" aria-label="Toggle Theme">
                        <svg id="moonIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg id="sunIcon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>

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
                        <span>AI Cloud <span style="color: var(--accent-cyan);">Host</span></span>
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
                        <li><a href="https://srv.shamsman.com:2083/" target="_blank">CWP Direct Login</a></li>
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

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('aichost_theme', next);
            syncThemeIcons(next);
        }

        function syncThemeIcons(theme) {
            const moon = document.getElementById('moonIcon');
            const sun = document.getElementById('sunIcon');
            if (moon && sun) {
                if (theme === 'dark') {
                    moon.style.display = 'none';
                    sun.style.display = 'block';
                } else {
                    moon.style.display = 'block';
                    sun.style.display = 'none';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            syncThemeIcons(current);
        });
    </script>
    @yield('scripts')
</body>
</html>
