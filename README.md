![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/3x1io-tomato-wallet.jpg)

# Filament Wallet

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-wallet/version.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![License](https://poser.pugx.org/tomatophp/filament-wallet/license.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![Downloads](https://poser.pugx.org/tomatophp/filament-wallet/d/total.svg)](https://packagist.org/packages/tomatophp/filament-wallet)

Account Balance / Wallets Manager For FilamentPHP and Filament Account Builder

you can get more detials about how to use this package in [Bavix Wallet](https://github.com/bavix/laravel-wallet)

# Screenshots

![Account Wallet](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/account-wallet.png)
![Charge A Wallet](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/charge-wallet.png)
![Wallets List](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/wallet.png)
![Transactions List](https://raw.githubusercontent.com/tomatophp/filament-wallet/master/arts/transactions.png)


## Installation

```bash
composer require tomatophp/filament-wallet
```
after install your package please run this command

```bash
php artisan filament-wallet:install
```

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentWallet\FilamentWalletPlugin::make())
```

## Usage

to add a wallet to your user model on your model add this trait

```php

namespace  App\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;

class Account extends Model implements Wallet
{
    use HasWallet;
}
```

now your model is has a wallet on your resource add this action to your table

```php
use TomatoPHP\FilamentWallet\Filament\Actions\WalletAction;

public function table(Table $table): void
{
    $table->actions([
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
use Bavix\Wallet\Traits\HasWallet;

class Account extends Model implements Wallet
{
    use HasWallet;
}
```

now you accounts has a balance ready.

finally reigster the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentWallet\FilamentWalletPlugin::make()->useAccounts())
```


## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
