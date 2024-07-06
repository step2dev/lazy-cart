<?php

namespace Step2Dev\LazyCart\Support;

use Illuminate\Support\Facades\Cookie;

class SessionResolver
{
    public function getSessionId(): ?string
    {
        $sessionCartId = Cookie::get('cart_session_id');

        if (auth()->guest() && ! $sessionCartId) {
            $sessionCartId = session()->getId();
            $sessionCartDays = config('site.cart.days', 30);
            cookie()->queue('cart_session_id', $sessionCartId, 60 * 24 * $sessionCartDays);
        }

        return $sessionCartId;
    }
}
