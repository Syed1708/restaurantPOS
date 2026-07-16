<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\StoreScope; 

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'price', 'vat_rate', 'is_active'];

       /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Automatically injects the store isolation check into every query
        static::addGlobalScope(new StoreScope);
    }
    // Ensure floats don't lose precision
    protected $casts = [
        'price' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}