<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    protected $fillable = ['name', 'address', 'postal_code', 'city', 'siret', 'vat_number'];

    // Get all users (cashiers/managers) belonging to this store
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Get all orders recorded in this store
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}