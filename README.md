![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/fadymondy-tomato-wallet.jpg)

# Filament Wallet

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-wallet/version.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![License](https://poser.pugx.org/tomatophp/filament-wallet/license.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![Downloads](https://poser.pugx.org/tomatophp/filament-wallet/d/total.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![Dependabot Updates](https://github.com/tomatophp/filament-wallet/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-wallet/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-wallet/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-wallet/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-wallet/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-wallet/actions/workflows/tests.yml)

Account Balance / Wallets Manager For FilamentPHP and Filament Account Builder

you can get more details about how to use this package in [Bavix Wallet](https://github.com/bavix/laravel-wallet)

# Screenshots

![Wallets List](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/wallets-light.png)
![Wallets List Dark](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/wallets-dark.png)
![Charge A Wallet](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/charge-wallet-light.png)
![Charge A Wallet Dark](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/charge-wallet-dark.png)
![Transactions List](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/transactions-light.png)
![Transactions List Dark](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/transactions-dark.png)


## Compatibility

| Package | Filament | Laravel | PHP |
|---------|----------|---------|-----|
| 5.x | 5.x | 12.x / 13.x | 8.3+ |
| 4.x | 4.x | 11.x / 12.x | 8.2+ |
| 1.x | 3.x | 10.x / 11.x | 8.1+ |

## Installation

```bash
composer require tomatophp/filament-wallet
```
after installing your package, please run this command

```bash
php artisan filament-wallet:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentWallet\FilamentWalletPlugin::make())
```

## Usage

to add a wallet to your user model on your model add this trait

```php

namespace  App\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;

class Account extends Model implements Wallet
{
    use HasWalletFloat;
}
```

now your model is having a wallet on your resource add this action to your table

```php
use TomatoPHP\FilamentWallet\Filament\Actions\WalletAction;

public static function table(Table $table): Table
{
    return $table->recordActions([
        WalletAction::make('wallet'),
    ]);
}
```

now yo can charge the wallet of the user by clicking on the wallet action

## Integration With Filament Accounts

first you need to install Filament Account Builder

```bash
composer require tomatophp/filament-account
```

then you need to publish the model file

```bash
php artisan vendor:publish --tag="filament-accounts-model"
```

then you can use this model in your project and attach this traits to your model

```php

namespace  App\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;

class Account extends Model implements Wallet
{
    use HasWalletFloat;
}
```

now your accounts have a balance ready.

finally, register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentWallet\FilamentWalletPlugin::make()->useAccounts())
```

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
