<?php

namespace TomatoPHP\FilamentWallet\Tests;

use Filament\Facades\Filament;
use Illuminate\Config\Repository;
use TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource;
use TomatoPHP\FilamentWallet\Filament\Resources\TransactionResource\Pages;
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

it('can render transaction resource', function () {
    $this->get(TransactionResource::getUrl('index'))->assertSuccessful();
});

it('can list transactions', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    livewire(Pages\ListTransactions::class)
        ->assertSuccessful();
});

it('can render transaction columns in table', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    livewire(Pages\ListTransactions::class)
        ->assertCanRenderTableColumn('created_at')
        ->assertCanRenderTableColumn('payable.name')
        ->assertCanRenderTableColumn('wallet.name')
        ->assertCanRenderTableColumn('type')
        ->assertCanRenderTableColumn('amount')
        ->assertCanRenderTableColumn('confirmed')
        ->assertCanRenderTableColumn('uuid');
});

it('can see transaction records in table', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $transaction = $user->wallet->transactions()->first();

    livewire(Pages\ListTransactions::class)
        ->assertCanSeeTableRecords([$transaction]);
});

it('displays deposit transaction with correct type', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $transaction = $user->wallet->transactions()->first();

    livewire(Pages\ListTransactions::class)
        ->assertTableColumnStateSet('type', 'deposit', $transaction);
});

it('displays withdraw transaction with correct type', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);
    $user->withdrawFloat(50);

    $transaction = $user->wallet->transactions()->where('type', 'withdraw')->first();

    livewire(Pages\ListTransactions::class)
        ->assertTableColumnStateSet('type', 'withdraw', $transaction);
});

it('displays correct transaction amount', function () {
    $user = User::factory()->create();
    $user->depositFloat(100.50);

    $transaction = $user->wallet->transactions()->first();

    // Amount is stored as decimal (100.5) for HasWalletFloat
    expect((float) $transaction->amountFloat)->toBe(100.5);
});

it('can sort transactions by created_at', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);
    sleep(1);
    $user->depositFloat(200);

    $transactions = $user->wallet->transactions;

    livewire(Pages\ListTransactions::class)
        ->sortTable('created_at', 'desc')
        ->assertCanSeeTableRecords($transactions->reverse()->values()->all(), inOrder: true);
});

it('can search transactions by uuid', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);

    $transaction = $user->wallet->transactions()->first();

    livewire(Pages\ListTransactions::class)
        ->searchTable($transaction->uuid)
        ->assertCanSeeTableRecords([$transaction]);
});

it('shows confirmed status correctly', function () {
    $user = User::factory()->create();
    $user->depositFloat(100, confirmed: true);

    $transaction = $user->wallet->transactions()->first();

    expect($transaction->confirmed)->toBeTrue();
});

it('can create multiple transactions for same wallet', function () {
    $user = User::factory()->create();
    $user->depositFloat(100);
    $user->depositFloat(200);
    $user->withdrawFloat(50);

    $transactions = $user->wallet->transactions;

    expect($transactions)->toHaveCount(3);
});

it('tracks wallet balance changes through transactions', function () {
    $user = User::factory()->create();

    $user->depositFloat(100);
    expect((float) $user->balanceFloat)->toBe(100.0);

    $user->depositFloat(50);
    expect((float) $user->balanceFloat)->toBe(150.0);

    $user->withdrawFloat(30);
    expect((float) $user->balanceFloat)->toBe(120.0);
});
