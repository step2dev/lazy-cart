<?php

namespace Step2Dev\LazyCart\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('lazy-cart.user_model'));
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
