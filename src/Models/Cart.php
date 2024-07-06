<?php

namespace Step2Dev\LazyCart\Models;

use App\Services\CartService;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForSession(Builder $query, ?string $sessionId = null): Builder
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForCurrentUser(Builder $query): Builder
    {
        $currentSessionId = (new CartService)->getSessionId();

        return $query->when(auth()->check(),
            fn ($query) => $query
                ->forUser(auth()->id())
                ->orWhere
                ->forSession($currentSessionId),
            fn ($query) => $query
                ->forSession($currentSessionId));
    }
}
