<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_email',
        'client_phone',
        'client_company',
        'client_address',
        'order_id',
        'invoice_number',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total',
        'currency',
        'status',
        'due_at',
        'paid_at',
        'notes',
        'terms',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isUnpaid(): bool
    {
        return $this->status === 'unpaid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_at && $this->due_at->isPast();
    }

    public function getRecipientNameAttribute(): string
    {
        return $this->client_name ?: ($this->user?->name ?? 'Valued Client');
    }

    public function getRecipientEmailAttribute(): string
    {
        return $this->client_email ?: ($this->user?->email ?? '');
    }

    public function getRecipientCompanyAttribute(): ?string
    {
        return $this->client_company ?: $this->user?->company;
    }

    public function getRecipientAddressAttribute(): ?string
    {
        return $this->client_address ?: $this->user?->address;
    }
}
