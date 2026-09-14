<?php

use TomatoPHP\FilamentWallet\Console\FilamentWalletInstall;

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    // The real command shells out to `php artisan migrate`; skip that sub-process in tests.
    app()->bind(FilamentWalletInstall::class, fn () => new class extends FilamentWalletInstall
    {
        public array $ran = [];

        public function artisanCommand(array $command, ?bool $withOutput = false): void
        {
            $this->ran[] = $command;
        }
    });

    artisan('filament-wallet:install')
        ->expectsOutputToContain('Filament Wallet installed successfully.')
        ->assertSuccessful();
});
