<?php

namespace TomatoPHP\FilamentWallet\Tests\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use TomatoPHP\FilamentWallet\Tests\Database\Factories\UserFactory;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, Wallet
{
    use HasFactory;
    use HasWalletFloat;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
