<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'product_plan_id',
        'serviceable_type',
        'serviceable_id',
        'label',
        'status',
        'billing_cycle',
        'amount',
        'currency',
        'next_due_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'      => 'decimal:2',
            'next_due_at' => 'datetime',
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

    public function productPlan(): BelongsTo
    {
        return $this->belongsTo(ProductPlan::class);
    }

    public function serviceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function hostingAccount(): HasOne
    {
        return $this->hasOne(HostingAccount::class);
    }

    public function vpsInstance(): HasOne
    {
        return $this->hasOne(VpsInstance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
