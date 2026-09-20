@extends('layouts.app')

@section('title', 'Create Client Account — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 60px; padding-bottom: 100px; max-width: 520px;">
    <div class="glass-panel" style="padding: 40px; box-shadow: var(--shadow-card);">
        <div style="text-align: center; margin-bottom: 30px;">
            <div class="brand-icon" style="margin: 0 auto 16px; width: 48px; height: 48px; font-size: 24px;">⚡</div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-main);">Create Customer Account</h1>
            <p style="color: var(--text-muted); font-size: 14px; margin-top: 6px;">Get started on the next-gen AI Cloud Hosting platform</p>
        </div>

        @if($errors->any())
            <div class="alert-banner alert-error" style="margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.process') }}">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" style="width: 100%; height: 46px;" placeholder="Alex Vance" required autofocus>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; height: 46px;" placeholder="alex@company.com" required>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Company / Project (Optional)</label>
                <input type="text" name="company" value="{{ old('company') }}" style="width: 100%; height: 46px;" placeholder="AI Lab Inc.">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 24px;">
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Password</label>
                    <input type="password" name="password" style="width: 100%; height: 46px;" placeholder="Min. 8 chars" required>
                </div>
                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Confirm</label>
                    <input type="password" name="password_confirmation" style="width: 100%; height: 46px;" placeholder="Repeat password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; font-size: 15px;">
                Complete Registration &rarr;
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-muted);">
            Already have an account? <a href="{{ route('login') }}" style="color: var(--accent-cyan); text-decoration: none; font-weight: 600;">Sign In</a>
        </div>
    </div>
</div>
@endsection
