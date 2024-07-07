<?php

namespace Step2Dev\LazyCart\Services;

use Illuminate\Database\Eloquent\Model;
use Step2Dev\LazyCart\Models\Cart;
use Step2Dev\LazyCart\Support\SessionResolver;

class CartService
{
    private ?string $sessionKey;

    private SessionResolver $sessionResolver;

    private ?int $authUserId;

    public function __construct()
    {
        $this->sessionResolver = new SessionResolver();
        $this->sessionKey = $this->sessionResolver->getCartSessionKey();
        $this->authUserId = auth('sanctum')->id() ?? auth()->id();
    }

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

    public function getAuthUserId(): ?int
    {
        return $this->authUserId;
    }

    public function getSessionKey(): string
    {
        return $this->sessionKey;
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

    public function clear(): ?bool
    {
        return Cart::forCurrentUser()->delete();
    }
}
