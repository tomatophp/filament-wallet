<?php

namespace TomatoPHP\FilamentWallet\Tests;

use Filament\Facades\Filament;
use Illuminate\Config\Repository;
use TomatoPHP\FilamentWallet\Filament\Resources\WalletResource\Pages;
use TomatoPHP\FilamentWallet\FilamentWalletPlugin;
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

it('can credit (deposit) funds using wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;
    $initialBalance = (float) $user->balanceFloat;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 50,
        ])
        ->assertNotified();

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe($initialBalance + 50.0);
});

it('can debit (withdraw) funds using wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;
    $initialBalance = (float) $user->balanceFloat;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'debit',
            'amount' => 30,
        ])
        ->assertNotified();

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe($initialBalance - 30.0);
});

it('validates required amount field in wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => null,
        ])
        ->assertHasTableActionErrors(['amount' => 'required']);
});

it('validates required type field in wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => null,
            'amount' => 50,
        ])
        ->assertHasTableActionErrors(['type' => 'required']);
});

it('validates numeric amount field in wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 'not-a-number',
        ])
        ->assertHasTableActionErrors(['amount']);
});

it('can perform multiple credit operations', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 50,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(150.0);

    $wallet->refresh();

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 25,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(175.0);
});

it('can perform multiple debit operations', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'debit',
            'amount' => 20,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(80.0);

    $wallet->refresh();

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'debit',
            'amount' => 10,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(70.0);
});

it('can perform mixed credit and debit operations', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    // Credit operation
    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 50,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(150.0);

    $wallet->refresh();

    // Debit operation
    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'debit',
            'amount' => 30,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(120.0);
});

it('handles decimal amounts in wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 25.75,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(125.75);
});

it('shows success notification after wallet action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 50,
        ])
        ->assertNotified()
        ->assertHasNoTableActionErrors();
});

it('credits balance correctly with zero initial balance', function () {
    $user = User::factory()->create();

    // Create wallet by depositing 0 first
    $user->depositFloat(0);
    $wallet = $user->wallet;

    expect((float) $user->balanceFloat)->toBe(0.0);

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 100,
        ]);

    $user->refresh();
    expect((float) $user->balanceFloat)->toBe(100.0);
});

it('updates wallet transactions after credit action', function () {
    $user = User::factory()->create();
    $user->depositFloat(50);

    $wallet = $user->wallet;
    $initialTransactionCount = $wallet->transactions()->count();

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'credit',
            'amount' => 75,
        ]);

    $wallet->refresh();
    expect($wallet->transactions()->count())->toBe($initialTransactionCount + 1);
});

it('updates wallet transactions after debit action', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $wallet = $user->wallet;
    $initialTransactionCount = $wallet->transactions()->count();

    livewire(Pages\ListWallets::class)
        ->callTableAction('wallet', $wallet, data: [
            'type' => 'debit',
            'amount' => 25,
        ]);

    $wallet->refresh();
    expect($wallet->transactions()->count())->toBe($initialTransactionCount + 1);
});
