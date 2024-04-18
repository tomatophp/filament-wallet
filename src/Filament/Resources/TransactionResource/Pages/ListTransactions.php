<?php

namespace TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ManageRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
