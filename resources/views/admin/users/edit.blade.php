@extends('layouts.app')

@section('title', 'Edit User — ' . $user->name . ' — Super Admin')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 90px; max-width: 960px;">
    <!-- Breadcrumb & Header -->
    <div style="margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--accent-cyan); text-decoration: none;">Super Admin</a>
            <span>/</span>
            <a href="{{ route('admin.users.index') }}" style="color: var(--accent-cyan); text-decoration: none;">Users</a>
            <span>/</span>
            <span>Edit {{ $user->name }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">Edit User: {{ $user->name }}</h1>
                <p style="color: var(--text-muted); font-size: 14px;">Update profile details, reset password, adjust account balance, or change privileges.</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.invoices.create', ['user_id' => $user->id]) }}" class="btn btn-cyan btn-sm">
                    + Issue Invoice for User
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">&larr; Back to Users</a>
            </div>
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

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
            <!-- Left Column: Account & Profile Info -->
            <div class="glass-panel" style="padding: 28px;">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <span>👤</span> User Profile & Password
                </h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width: 100%;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width: 100%;" required>
                    </div>
                </div>

                <div style="padding: 14px; border-radius: 10px; background: var(--surface-hover); border: 1px solid var(--card-border); margin-bottom: 16px;">
                    <div style="font-size: 12px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">
                        🔒 Change Password <span style="font-weight: 400; color: var(--text-muted);">(Leave blank to keep current password)</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">New Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep unchanged" style="width: 100%;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="Repeat new password" style="width: 100%;">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Company / Organization</label>
                        <input type="text" name="company" value="{{ old('company', $user->company) }}" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" style="width: 100%;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" style="width: 100%;">
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Billing Address</label>
                    <textarea name="address" rows="3" style="width: 100%;">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <!-- Right Column: Role, Status & Credits -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="glass-panel" style="padding: 28px;">
                    <h3 style="font-size: 17px; font-weight: 700; color: var(--text-main); margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                        <span>⚙️</span> Role & Wallet Settings
                    </h3>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Account Role *</label>
                        <select name="role" style="width: 100%;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer (Standard Portal Access)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Super Admin (Full Admin Control)</option>
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <small style="font-size: 11px; color: var(--text-muted);">You cannot demote your own Super Admin role.</small>
                        @endif
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Account Status *</label>
                        <select name="status" style="width: 100%;" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="status" value="{{ $user->status }}">
                        @endif
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Account Credit Balance ($ USD)</label>
                        <input type="number" step="0.01" min="0" name="balance" value="{{ old('balance', $user->balance) }}" style="width: 100%; font-weight: 700; font-family: 'Space Grotesk', sans-serif;" required>
                    </div>

                    <div style="padding-top: 16px; border-top: 1px solid var(--card-border); display: flex; flex-direction: column; gap: 10px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center; text-align: center;">
                            Cancel
                        </a>
                    </div>
                </div>

                @if($user->id !== auth()->id())
                    <div class="glass-panel" style="padding: 20px; border: 1px solid rgba(239, 68, 68, 0.25);">
                        <h4 style="font-size: 14px; font-weight: 700; color: #ef4444; margin-bottom: 6px;">Danger Zone</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 12px;">
                            Permanently delete this user account and remove portal access.
                        </p>
                        <button type="button" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center; color: #ef4444; border-color: rgba(239, 68, 68, 0.35);" onclick="if(confirm('Permanently delete user {{ $user->name }} ({{ $user->email }})?')) document.getElementById('delete-user-form').submit();">
                            Delete User Account
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </form>

    @if($user->id !== auth()->id())
        <form id="delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
</div>
@endsection
