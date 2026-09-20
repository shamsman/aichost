<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostingAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'server_id',
        'cwp_username',
        'cwp_account_id',
        'primary_domain',
        'package_name',
        'ip_address',
        'login_url',
        'status',
        'provisioned_at',
        'suspended_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'meta'           => 'array',
            'provisioned_at' => 'datetime',
            'suspended_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
