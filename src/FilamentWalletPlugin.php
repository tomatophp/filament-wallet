<?php

namespace TomatoPHP\FilamentWallet;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentWalletPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-wallet';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([

            ]);
    }

    public function boot(Panel $panel): void
    {

    }

    public static function make(): static
    {
        return new static();
    }
}
