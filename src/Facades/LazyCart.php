<?php

namespace Step2Dev\LazyCart\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Step2Dev\LazyCart\LazyCart
 */
class LazyCart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Step2Dev\LazyCart\LazyCart::class;
    }
}
