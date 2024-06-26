<?php

namespace TomatoPHP\FilamentWallet\Filament\Actions;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class WalletAction extends Action
{
    protected function setUp(): void
    {
        $this->iconButton();
        $this->icon('heroicon-s-wallet');
        $this->tooltip(trans('filament-wallet::messages.wallets.action.title'));
        $this->label(trans('filament-wallet::messages.wallets.action.title'));
        $this->form(function ($record){
            return [
                TextInput::make('current_balance')
                    ->disabled()
                    ->label(trans('filament-wallet::messages.wallets.action.current_balance'))
                    ->numeric()
                    ->required()
                    ->live()
                    ->default($record->balance),
                Select::make('type')
                    ->searchable()
                    ->default('credit')
                    ->options([
                        'credit' => trans('filament-wallet::messages.wallets.action.credit'),
                        'debit' => trans('filament-wallet::messages.wallets.action.debit')
                    ])
                    ->label(trans('filament-wallet::messages.wallets.action.type'))
                    ->required()
                    ->live(),
                TextInput::make('amount')
                    ->label(trans('filament-wallet::messages.wallets.action.amount'))
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
        });
        $this->action(function($record,array $data){
            if($data['type'] == 'debit'){
                $record->withdraw($data['amount']);
            }
            else {
                $record->deposit($data['amount']);
            }

            Notification::make()
                ->title(trans('filament-wallet::messages.wallets.notification.title'))
                ->body(trans('filament-wallet::messages.wallets.notification.message'))
                ->send();
        });
    }
}
