<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentWallet\FilamentWalletPlugin;

it('registers plugin', function () {
    $panel = Filament::getCurrentOrDefaultPanel();

    $panel->plugins([
        FilamentWalletPlugin::make(),
    ]);

    expect($panel->getPlugin('filament-wallet'))
        ->not()
        ->toThrow(Exception::class);
});
