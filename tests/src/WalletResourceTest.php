<?php

namespace TomatoPHP\FilamentWallet\Tests;

use Filament\Facades\Filament;
use Illuminate\Config\Repository;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource\Pages;
use TomatoPHP\FilamentWallet\FilamentWalletPlugin;
use TomatoPHP\FilamentWallet\Models\Wallet;
use TomatoPHP\FilamentWallet\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $app = $this->app;

    tap($app['config'], function (Repository $config) {
        $config->set('filament-wallet.useAccounts', false);
    });

    actingAs(User::factory()->create());

    $this->panel = Filament::getCurrentOrDefaultPanel();
    $this->panel->plugin(
        FilamentWalletPlugin::make()
    );
});

it('can render wallet resource', function () {
    $this->get(WalletResource::getUrl('index'))->assertSuccessful();
});

it('can list wallets', function () {
    $user = User::factory()->create();

    // Create wallet for user by depositing
    $user->depositFloat(100);

    livewire(Pages\ListWallets::class)
        ->assertSuccessful();
});

it('can render wallet columns in table', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    livewire(Pages\ListWallets::class)
        ->assertCanRenderTableColumn('created_at')
        ->assertCanRenderTableColumn('holder.name')
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('balanceFloatNum')
        ->assertCanRenderTableColumn('uuid');
});

it('can see wallet records in table', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->assertCanSeeTableRecords([$wallet]);
});

it('displays correct wallet balance', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.50);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->assertTableColumnStateSet('balanceFloatNum', 100.50, $wallet);
});

it('can sort wallets by created_at', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1->depositFloat(100);
    sleep(1);
    $user2->depositFloat(200);

    livewire(Pages\ListWallets::class)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords([$user2->wallet, $user1->wallet], inOrder: true);
});

it('can search wallets by holder name', function () {
    $user1 = User::factory()->create(['name' => 'John Doe']);
    $user2 = User::factory()->create(['name' => 'Jane Smith']);

    $user1->depositFloat(100);
    $user2->depositFloat(200);

    livewire(Pages\ListWallets::class)
        ->searchTable('John')
        ->assertCanSeeTableRecords([$user1->wallet])
        ->assertCanNotSeeTableRecords([$user2->wallet]);
});

it('can search wallets by uuid', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->searchTable($wallet->uuid)
        ->assertCanSeeTableRecords([$wallet]);
});

it('can deposit to wallet', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    expect((float) $user->balanceFloat)->toBe(100.0);
});

it('can withdraw from wallet', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);
    $user->withdrawFloat(50);

    expect((float) $user->balanceFloat)->toBe(50.0);
});

it('can transfer between wallets', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $user1->depositFloat(100);
    $user1->transferFloat($user2, 30);

    expect((float) $user1->balanceFloat)->toBe(70.0)
        ->and((float) $user2->balanceFloat)->toBe(30.0);
});
