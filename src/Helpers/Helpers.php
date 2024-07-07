<?php

namespace Step2Dev\LazyCart\Helpers;

class Helpers
{
    private array $moneyFormat;

    public function __construct()
    {
//        $this->moneyFormat = $this->moneyFormat();
    }

//    private function moneyFormat(): array
//    {
//        return [
//            'separator' => config('lazy.cart.money_format.separator'),
//            'thousand'  => config('lazy.cart.money_format.thousand'),
//            'precision' => config('lazy.cart.money_format.precision'),
//
//        ];
//    }

    public static function normalizePrice(string|int|float|null $price): float
    {
        $price ??= 0;

        return (float) number_format($price, 10, '.', '');
    }

    public static function normalizeQuantity(int|float|null $quantity): int
    {
        return $quantity ?? 1;
    }

    public static function normalizeOptions(array $options): array
    {
        return $options;
    }

    public static function moneyFormat(int|string|float $price): string
    {
        $precision = config('lazy.cart.money_format.precision');

        $price = self::roundPrice($price);

        return number_format($price, $precision, config('lazy.cart.money_format.separator'), config('lazy.cart.money_format.thousand'));
    }

    public static function roundPrice(int|string|float|null $price = null): float
    {
        $price = self::normalizePrice($price);

        return round($price, config('lazy.cart.money_format.precision'), PHP_ROUND_HALF_UP);
    }
}
