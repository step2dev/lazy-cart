<?php

namespace Step2Dev\LazyCart\Support;

use Illuminate\Support\Facades\Cookie;
use SensitiveParameter;

class SessionResolver
{
    private string $sessionName;
    private int $cartSaveDays;

    public function __construct()
    {
        $this->sessionName = config('lazy.cart.cookie.name', 'cart_session');
        $this->cartSaveDays = config('lazy.cart.cart.days', 30);
    }

    public function getSessionName(): string
    {
        return $this->sessionName;
    }

    public function getCartSaveDays(): int
    {
        return $this->cartSaveDays;
    }

    private function getCartSession(): ?string
    {
        return Cookie::get($this->sessionName);
    }

    public function saveCartSession(#[SensitiveParameter] string $sessionId): void
    {
        cookie()->queue($this->sessionName, $sessionId, 60 * 24 * $this->cartSaveDays);
    }


    public function getCartSessionId(): ?string
    {
        $sessionCart = $this->getCartSession();

        if ($sessionCart) {
            return $sessionCart;
        }

        $sessionCartId = session()?->getId();

        if (! $sessionCartId) {
            return null;
        }

        $this->saveCartSession($sessionCartId);

        return $sessionCartId;
    }

    public function forgetCartSession(): void
    {
        cookie()->queue(Cookie::forget($this->sessionName));
    }

    public function hasCartSession(): bool
    {
        return Cookie::has($this->sessionName);
    }


}
