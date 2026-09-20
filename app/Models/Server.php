<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'hostname',
        'ip_address',
        'api_url',
        'api_key_encrypted',
        'api_secret_encrypted',
        'region',
        'capacity',
        'used_slots',
        'status',
    ];

    protected $hidden = [
        'api_key_encrypted',
        'api_secret_encrypted',
    ];

    protected function casts(): array
    {
        return [
            'api_key_encrypted'    => 'encrypted',
            'api_secret_encrypted' => 'encrypted',
            'capacity'             => 'integer',
            'used_slots'           => 'integer',
        ];
    }

    public function hostingAccounts(): HasMany
    {
        return $this->hasMany(HostingAccount::class);
    }

    public function hasCapacity(): bool
    {
        return $this->capacity === 0 || $this->used_slots < $this->capacity;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getApiKeyAttribute(): ?string
    {
        return $this->api_key_encrypted;
    }

    public function getApiSecretAttribute(): ?string
    {
        return $this->api_secret_encrypted;
    }
}
