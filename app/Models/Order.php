<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'store_id',
        'user_id',
        'sequence_number',
        'subtotal_excl_vat',
        'vat_amount',
        'total_incl_vat',
        'hash',
        'previous_hash',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'subtotal_excl_vat' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_incl_vat' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
