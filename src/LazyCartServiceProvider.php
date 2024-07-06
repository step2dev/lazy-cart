<?php

namespace Step2Dev\LazyCart;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Step2Dev\LazyCart\Commands\LazyCartCommand;

class LazyCartServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('lazy-cart')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_lazy-cart_table')
            ->hasCommand(LazyCartCommand::class);
    }
}
