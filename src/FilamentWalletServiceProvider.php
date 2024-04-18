<?php

namespace TomatoPHP\FilamentWallet;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentAccounts\Facades\FilamentAccounts;
use TomatoPHP\FilamentAccounts\Models\Account;


class FilamentWalletServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentWallet\Console\FilamentWalletInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-wallet.php', 'filament-wallet');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-wallet.php' => config_path('filament-wallet.php'),
        ], 'filament-wallet-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-wallet-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-wallet');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-wallet'),
        ], 'filament-wallet-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-wallet');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-wallet'),
        ], 'filament-wallet-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }

    public function boot(): void
    {
        FilamentAccounts::registerAccountActions([
            Action::make('wallet')
                ->iconButton()
                ->icon('heroicon-s-wallet')
                ->tooltip('Charge Wallet')
                ->form(function ($record){
                    return [
                        TextInput::make('current_balance')
                            ->disabled()
                            ->label('Current balance')
                            ->numeric()
                            ->required()
                            ->live()
                            ->default($record->balance),
                        Select::make('type')
                            ->searchable()
                            ->default('credit')
                            ->options([
                                'credit' => 'Credit',
                                'debit' => 'Debit'
                            ])
                            ->label('Type')
                            ->required()
                            ->live(),
                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function($record, $state, Set $set, Get $get){
                                if($get('type') == 'debit'){
                                    $set('current_balance', $record->balance - $state);
                                }
                                else {
                                    $set('current_balance', $record->balance + $state);
                                }
                            })
                    ];
                })
                ->action(function($record,array $data){
                    if($data['type'] == 'debit'){
                        $record->withdraw($data['amount']);
                    }
                    else {
                        $record->deposit($data['amount']);
                    }

                    Notification::make()
                        ->title('Wallet Charged')
                        ->message('Wallet Charged Successfully')
                        ->send();
                }),
        ]);
    }
}
