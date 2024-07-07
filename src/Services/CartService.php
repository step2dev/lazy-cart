<?php

namespace Step2Dev\LazyCart\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Step2Dev\LazyCart\Models\Cart;

class CartService
{
    public function getCartId(): int
    {
        return Cart::forCurrentUser()->value('id');
    }

    public function getCart()
    {
        return Cart::forCurrentUser()
            ->with('items.itemable')
            ->withCount('items')
            ->first();
    }

    public function getSessionId(): string
    {
        $name = config('lazy.cart.cookie.name', 'cart_session_id');
        $sessionCartId = Cookie::get($name);

        if ($sessionCartId) {
            return $sessionCartId;
        }

        if (auth()->guest()) {
            $sessionCartId = session()->getId();
            $sessionCartDays = config('lazy.cart.cart.days', 30);

            cookie()->queue($name, $sessionCartId, 60 * 24 * $sessionCartDays);
        }

        return $sessionCartId;
    }

    public function add(Model $item, int $quantity, array $options = []): bool
    {
        $items = Cart::forCurrentUser()->items()->create([
            'itemable_id' => $item->id,
            'itemable_type' => get_class($item),
            'quantity' => $quantity,
            'options' => $options,
        ]);

        return $items->wasRecentlyCreated;
    }

    public function update($item, int $quantity, array $options = []): bool
    {
        return Cart::forCurrentUser()
            ->items()
            ->where('itemable_id', $item->id)
            ->update(compact('quantity', 'options'));
    }

    public function remove($item)
    {
        Cart::forCurrentUser()
            ->items()
            ->where('itemable_id', $item->id)
            ->delete();
    }

    public function clear(): bool|null
    {
       return Cart::forCurrentUser()->delete();
    }

}
