<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VpsInstance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'gcp_project',
        'gcp_zone',
        'instance_name',
        'machine_type',
        'disk_gb',
        'external_ip',
        'internal_ip',
        'status',
        'cwp_installed',
        'cwp_url',
        'specs',
        'provisioned_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'specs'          => 'array',
            'meta'           => 'array',
            'cwp_installed'  => 'boolean',
            'disk_gb'        => 'integer',
            'provisioned_at' => 'datetime',
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
}
