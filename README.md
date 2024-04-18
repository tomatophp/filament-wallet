![Screenshot](https://github.com/tomatophp/filament-wallet/blob/master/arts/3x1io-tomato-wallet.jpg)

# Filament Wallet

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-wallet/version.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![PHP Version Require](http://poser.pugx.org/tomatophp/filament-wallet/require/php)](https://packagist.org/packages/tomatophp/filament-wallet)
[![License](https://poser.pugx.org/tomatophp/filament-wallet/license.svg)](https://packagist.org/packages/tomatophp/filament-wallet)
[![Downloads](https://poser.pugx.org/tomatophp/filament-wallet/d/total.svg)](https://packagist.org/packages/tomatophp/filament-wallet)

Account Balance / Wallets Manager For FilamentPHP and Filament Account Builder


# Screenshots

![Account Wallet](https://github.com/tomatophp/filament-wallet/blob/master/arts/account-wallet.png)
![Charge A Wallet](https://github.com/tomatophp/filament-wallet/blob/master/arts/charge-wallet.png)
![Wallets List](https://github.com/tomatophp/filament-wallet/blob/master/arts/wallet.png)
![Transactions List](https://github.com/tomatophp/filament-wallet/blob/master/arts/transactions.png)


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

you need first publish Account Model using this command

```bash
php artisan vendor:publish --tag="filament-wallet-model"
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

you can get more detials about how to use this package in [Bavix Wallet](https://github.com/bavix/laravel-wallet)

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-wallet-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-wallet-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-wallet-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-wallet-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
