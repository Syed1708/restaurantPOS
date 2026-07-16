<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\StoreScope;

class Product extends Model
{
    protected $fillable = ['category_id','store_id', 'name', 'price', 'vat_rate', 'is_active'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Automatically injects the store isolation check into every query
        static::addGlobalScope(new StoreScope);

        static::saving(function ($product) {
            // 🚀 AUTOMATIC INHERITANCE:
            // Products always inherit their store_id directly from the Category they belong to!
            if ($product->category_id) {
                $category = \App\Models\Category::withoutGlobalScopes()->find($product->category_id);
                if ($category) {
                    $product->store_id = $category->store_id;
                }
            }
        });
    }
    // Ensure floats don't lose precision
    protected $casts = [
        'price' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
