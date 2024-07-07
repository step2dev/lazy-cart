<?php

namespace Step2Dev\LazyCart\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Step2Dev\LazyCart\Services\CartService;

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

    /**
     * @noinspection SensitiveParameterInspection
     */
    public function scopeForSession(Builder $query, ?string $sessionId = null): Builder
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeForCurrentUser(Builder $query): Builder
    {
        $currentSessionId = (new CartService)->getSessionId();

        return $query->when(auth()->check(),
            fn (Builder $query) => $query
                ->forUser(auth()->id())
                ->orWhere
                ->forSession($currentSessionId),
            fn (Builder $query) => $query
                ->forSession($currentSessionId));
    }

    public function prunable(): self
    {
        return static::where('updated_at', '<=', config('lazy.cart.cart.prune_days', 365));
    }

    public function total(): float
    {
        return $this->items->sum(fn (CartItem $item) => $item->total());
    }
}
