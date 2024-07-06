<?php

namespace Step2Dev\LazyCart\Facades;

use Illuminate\Support\Facades\Facade;
use Step2Dev\LazyCart\Support\ModelTableResolver;

class ModelTableResolverFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ModelTableResolver::class;
    }
}
