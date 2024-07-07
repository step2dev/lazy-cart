<?php

namespace Step2Dev\LazyCart;

use Step2Dev\LazyCart\Contracts\CartServiceInterface;
use Step2Dev\LazyCart\Models\Cart;

class LazyCart
{
    public CartServiceInterface $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createCart()
    {
        return Cart::forCurrentUser()->firstOrCreate();
    }

    public function add($item, ?int $quantity = null, array $options = [])
    {
        $quantity ??= 1;

        return $this->cartService->add($item, $quantity, $options);
    }

    public function update($item, ?int $quantity = null, array $options = [])
    {
        $quantity ??= 1;

        return $this->cartService->update($item, $quantity, $options);
    }
}
