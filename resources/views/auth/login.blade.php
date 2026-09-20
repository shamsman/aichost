@extends('layouts.app')

@section('title', 'Client Login — AICHost')

@section('content')
<div class="container" style="padding-top: 60px; padding-bottom: 100px; max-width: 480px;">
    <div class="glass-panel" style="padding: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
        <div style="text-align: center; margin-bottom: 30px;">
            <div class="brand-icon" style="margin: 0 auto 16px; width: 48px; height: 48px; font-size: 24px;">⚡</div>
            <h1 style="font-size: 24px; font-weight: 800; color: #fff;">Client Portal Login</h1>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 6px;">Manage your CWP hosting, Google VMs, and domains</p>
        </div>

        @if($errors->any())
            <div class="alert-banner alert-error" style="margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', 'admin@aichost.com') }}" style="width: 100%; height: 48px;" placeholder="name@company.com" required autofocus>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Password</label>
                <input type="password" name="password" value="password123" style="width: 100%; height: 48px;" placeholder="••••••••" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; font-size: 13px;">
                <label style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); cursor: pointer;">
                    <input type="checkbox" name="remember" checked style="accent-color: var(--primary);">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; font-size: 15px;">
                Sign In to Portal &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
            Don't have an account yet? <a href="{{ route('register') }}" style="color: var(--accent-cyan); text-decoration: none; font-weight: 600;">Create one</a>
        </div>
    </div>
</div>
@endsection
