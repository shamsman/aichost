@extends('layouts.app')

@section('title', 'Add New User — Super Admin — AI Cloud Host')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 90px; max-width: 960px;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--accent-cyan); text-decoration: none;">Super Admin</a>
            <span>/</span>
            <a href="{{ route('admin.users.index') }}" style="color: var(--accent-cyan); text-decoration: none;">Users</a>
            <span>/</span>
            <span>Add New User</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Add New User</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Create a new customer or administrator account with custom credit balance and permissions.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">&larr; Back to Users</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-banner alert-error" style="margin-bottom: 24px;">
            <div>
                <strong style="display: block; margin-bottom: 4px;">Please fix the following errors:</strong>
                <ul style="padding-left: 18px; font-size: 13px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
            <!-- Left Column: Account & Profile Info -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <span>👤</span> User Profile & Credentials
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Sarah Connor" style="width: 100%;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="client@company.com" style="width: 100%;" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Password *</label>
                        <input type="password" name="password" placeholder="Min. 6 characters" style="width: 100%;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Confirm Password *</label>
                        <input type="password" name="password_confirmation" placeholder="Repeat password" style="width: 100%;" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Company / Organization</label>
                        <input type="text" name="company" value="{{ old('company') }}" placeholder="AICHost Corp" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 019-2834" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'US') }}" placeholder="US, UK, TR..." style="width: 100%;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Billing Address</label>
                    <textarea name="address" rows="3" placeholder="Street address, Suite, City, State, Postal Code" style="width: 100%;">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Role, Status & Credits -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <span>⚙️</span> Role & Wallet Settings
                </h3>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Account Role *</label>
                    <select name="role" style="width: 100%;">
                        <option value="customer" {{ old('role', 'customer') === 'customer' ? 'selected' : '' }}>Customer (Standard Portal Access)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Super Admin (Full Admin Control)</option>
                    </select>
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Account Status *</label>
                    <select name="status" style="width: 100%;">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Initial Credit Balance ($ USD)</label>
                    <input type="number" step="0.01" min="0" name="balance" value="{{ old('balance', '0.00') }}" style="width: 100%; font-weight: 700; font-family: 'Space Grotesk', sans-serif;" required>
                </div>

                <div style="padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; flex-direction: column; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                        + Create User Account
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center; text-align: center;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
