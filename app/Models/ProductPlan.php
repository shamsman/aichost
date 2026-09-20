<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'slug',
        'name',
        'specs',
        'price_monthly',
        'price_annually',
        'setup_fee',
        'currency',
        'provider_type',
        'provider_config',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'specs'           => 'array',
            'provider_config' => 'array',
            'price_monthly'   => 'decimal:2',
            'price_annually'  => 'decimal:2',
            'setup_fee'       => 'decimal:2',
            'is_active'       => 'boolean',
            'sort_order'      => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function isDomain(): bool
    {
        return $this->product?->type === Product::TYPE_DOMAIN;
    }

    public function isSharedHosting(): bool
    {
        return $this->product?->type === Product::TYPE_SHARED_HOSTING;
    }

    public function isVps(): bool
    {
        return $this->product?->type === Product::TYPE_VPS;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider_type', $provider);
    }
}
