<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'domain_name',
        'tld',
        'registrar',
        'registrar_domain_id',
        'status',
        'registered_at',
        'expires_at',
        'auto_renew',
        'nameservers',
        'whois_privacy',
        'auth_code',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'nameservers'   => 'array',
            'meta'          => 'array',
            'auto_renew'    => 'boolean',
            'whois_privacy' => 'boolean',
            'registered_at' => 'datetime',
            'expires_at'    => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
