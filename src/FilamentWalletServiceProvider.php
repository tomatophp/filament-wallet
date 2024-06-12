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
use TomatoPHP\FilamentWallet\Filament\Actions\WalletAction;


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
        //
    }
}
