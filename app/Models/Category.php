<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\StoreScope;

class Category extends Model
{
    protected $fillable = ['store_id', 'name'];

    protected static function booted(): void
    {
        static::addGlobalScope(new StoreScope);

        // 🚀 THE "BLANK MEANS ALL" AUTOMATED LOOP:
        static::saving(function ($category) {
            
            // 1. If standard store manager, force to belong to their own store
            if (auth()->check() && !auth()->user()->hasRole('superadmin')) {
                $category->store_id = auth()->user()->store_id;
                return true; 
            }

            // 2. If Super Admin left the store field blank (null),
            // loop and create a copy of this category for every active store!
            if (is_null($category->store_id) && auth()->check() && auth()->user()->hasRole('superadmin')) {
                $stores = \App\Models\Store::all();

                foreach ($stores as $store) {
                    self::firstOrCreate([
                        'store_id' => $store->id,
                        'name' => $category->name
                    ]);
                }

                // 🛑 ABORT THE SINGLE SAVE:
                // Returning false stops Laravel from saving a category with store_id = null
                return false; 
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}