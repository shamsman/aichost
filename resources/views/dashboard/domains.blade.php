@extends('layouts.app')

@section('title', 'My Registered Domains — AICHost')

@section('content')
<div class="container" style="padding-top: 32px; padding-bottom: 80px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: var(--text-main);">My Registered Domains</h1>
            <p style="color: var(--text-muted); font-size: 14px;">Domains managed via InternetBS Reseller API with free DNS propagation.</p>
        </div>
        <a href="{{ route('domains.index') }}" class="btn btn-primary btn-sm">+ Register New Domain</a>
    </div>

    @if($domains->isEmpty())
        <div class="glass-panel" style="padding: 60px; text-align: center;">
            <div style="font-size: 44px; margin-bottom: 16px;">🌐</div>
            <h3 style="font-size: 18px; margin-bottom: 8px; color: var(--text-main);">No domains registered yet</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">Search and register your brand name across global TLDs.</p>
            <a href="{{ route('domains.index') }}" class="btn btn-primary">Find an AI Domain</a>
        </div>
    @else
        <div class="glass-panel" style="padding: 24px; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--card-border); color: var(--text-muted);">
                        <th style="padding: 14px 16px;">Domain Name</th>
                        <th style="padding: 14px 16px;">Registrar</th>
                        <th style="padding: 14px 16px;">Nameservers</th>
                        <th style="padding: 14px 16px;">Expires</th>
                        <th style="padding: 14px 16px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domains as $dom)
                        <tr style="border-bottom: 1px solid var(--card-border);">
                            <td style="padding: 16px;">
                                <strong style="color: var(--text-main); font-size: 15px;">{{ $dom->domain_name }}</strong>
                            </td>
                            <td style="padding: 16px; color: var(--text-muted);">
                                InternetBS API
                            </td>
                            <td style="padding: 16px; font-family: monospace; font-size: 12px; color: var(--accent-cyan); font-weight: 600;">
                                {{ implode(', ', $dom->nameservers ?? ['ns1.aichost.com', 'ns2.aichost.com']) }}
                            </td>
                            <td style="padding: 16px; color: var(--text-muted);">
                                {{ $dom->expires_at?->format('M d, Y') }}
                            </td>
                            <td style="padding: 16px;">
                                <span style="background: rgba(16, 185, 129, 0.12); color: #059669; border: 1px solid rgba(16, 185, 129, 0.25); font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">
                                    {{ $dom->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $domains->links() }}
        </div>
    @endif
</div>
@endsection
