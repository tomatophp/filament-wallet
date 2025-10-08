<?php

namespace TomatoPHP\FilamentWallet\Filament\Resources\WalletResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource;

class ListWallets extends ManageRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
