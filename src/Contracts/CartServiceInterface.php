<?php

namespace Step2Dev\LazyCart\Contracts;

interface CartServiceInterface
{
    public function add($item, int $quantity = 1, array $options = []);

    public function update($item, int $quantity = 1, array $options = []);

    public function remove($item);

    public function clear();

    public function content();

    public function count();

    public function total();

    public function isEmpty();

    public function has($item);

    public function get($item);

    public function setQuantity($item, int $quantity);

    public function increment($item, int $quantity = 1);

    public function decrement($item, int $quantity = 1);

    public function calculateTotal();
}
