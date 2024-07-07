<?php

namespace Step2Dev\LazyCart\Traits;

trait HasDynamicPricing
{
    public function getPrice(): float|int
    {
        return $this->price ?? 0;
    }

    public function getDiscount(): float|int
    {
        return $this->discount ?? 0;
    }

    public function getDiscountedPrice(): float|int
    {
        return $this->getPrice() * (1 - $this->getDiscount() / 100);
    }
}
