<?php

namespace Step2Dev\LazyCart\Support;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use RuntimeException;
use Step2Dev\LazyCart\Models\Cart;
use Step2Dev\LazyCart\Models\CartItem;

class ModelTableResolver
{
    private ConfigRepository $config;

    public function __construct(ConfigRepository $config)
    {
        $this->config = $config;
    }

    public function getCartClass(): string
    {
        return Cart::class;
    }

    public function getCartItemClass(): string
    {
        return CartItem::class;
    }

    public function getUserModel(): string
    {
        $userModel = $this->config->get('lazy-cart.user_model');

        if (! $userModel) {
            throw new RuntimeException('Please publish the config file and set the user_model key');
        }

        if (! class_exists($userModel)) {
            throw new RuntimeException('User model class does not exist');
        }

        return $this->config->get('lazy-cart.user_model');
    }

    public function getTablePrefix(): string
    {
        return $this->config->get('lazy-cart.table_prefix');
    }

    public function getCartItemTable(): string
    {
        return app($this->getCartItemClass())->getTable();
    }

    public function getCartTable(): string
    {
        return app($this->getCartClass())->getTable();
    }
}
