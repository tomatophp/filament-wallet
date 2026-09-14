<?php

namespace TomatoPHP\FilamentWallet\Filament\Resources;

use BackedEnum;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use TomatoPHP\FilamentWallet\Filament\Actions\WalletAction;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource\Pages;
use TomatoPHP\FilamentWallet\Models\Wallet;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-wallet';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-wallet::messages.group');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-wallet::messages.wallets.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-wallet::messages.wallets.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-wallet::messages.wallets.title');
    }

    public static function form(Schema $form): Schema
    {
        return $form->components([
            TextInput::make('balance')
                ->columnSpan(2)
                ->disabled()
                ->label(trans('filament-wallet::messages.wallets.columns.balance'))
                ->numeric()
                ->live()
                ->required(),
            Select::make('type')
                ->columnSpan(2)
                ->searchable()
                ->default('credit')
                ->options([
                    'credit' => trans('filament-wallet::messages.wallets.columns.credit'),
                    'debit' => trans('filament-wallet::messages.wallets.columns.debit'),
                ])
                ->label(trans('filament-wallet::messages.wallets.columns.type'))
                ->required()
                ->live(),
            TextInput::make('amount')
                ->columnSpan(2)
                ->label(trans('filament-wallet::messages.wallets.columns.amount'))
                ->numeric()
                ->required()
                ->live()
                ->afterStateUpdated(function (?Wallet $record, mixed $state, Set $set, Get $get): void {
                    $balance = $record?->balanceFloatNum ?? 0;

                    if ($get('type') == 'debit') {
                        $set('balance', $balance - $state);
                    } else {
                        $set('balance', $balance + $state);
                    }
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('filament-wallet::messages.wallets.columns.created_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('holder.name')
                    ->label(trans('filament-wallet::messages.wallets.columns.user'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-wallet::messages.wallets.columns.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('balanceFloatNum')
                    ->label(trans('filament-wallet::messages.wallets.columns.balance'))
                    ->badge()
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label(trans('filament-wallet::messages.wallets.columns.uuid'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(filament('filament-wallet')->useAccounts ? [
                Tables\Filters\SelectFilter::make('holder_id')
                    ->label(trans('filament-wallet::messages.wallets.filters.accounts'))
                    ->searchable()
                    ->options(fn () => config('filament-accounts.model')::query()->pluck('name', 'id')->toArray()),
            ] : [])
            ->recordActions([
                WalletAction::make('wallet'),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWallets::route('/'),
        ];
    }
}
