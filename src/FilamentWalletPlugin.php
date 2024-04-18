<?php

namespace TomatoPHP\FilamentWallet;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource;
use TomatoPHP\FilamentWallet\Filament\Resources\TransferResource;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource;

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
                TransactionResource::class,
                WalletResource::class
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
