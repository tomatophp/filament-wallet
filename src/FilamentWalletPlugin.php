<?php

namespace TomatoPHP\FilamentWallet;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Table\AccountActions;
use TomatoPHP\FilamentWallet\Filament\Actions\WalletAction;
use TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource;

class FilamentWalletPlugin implements Plugin
{
    public ?bool $useAccounts = false;

    public ?bool $hideResources = false;

    public function getId(): string
    {
        return 'filament-wallet';
    }

    public function register(Panel $panel): void
    {
        $resources = [];

        if (! $this->hideResources) {
            $resources = [
                TransactionResource::class,
                WalletResource::class,
            ];
        }

        $panel->resources($resources);
    }

    public function useAccounts(bool $useAccounts = true): static
    {
        $this->useAccounts = $useAccounts;

        return $this;
    }

    public function hideResources(bool $hideResources = true): static
    {
        $this->hideResources = $hideResources;

        return $this;
    }

    public function boot(Panel $panel): void
    {
        if ($this->useAccounts) {
            AccountActions::register(WalletAction::make('wallet'));
        }
    }

    public static function make(): static
    {
        return new static;
    }
}
