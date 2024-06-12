<?php

return [
    "group" => "Wallet",
    "transactions" => [
        "title" => "Transactions",
        "single" => "Transaction",
        "columns" => [
            "created_at" => "Date",
            "user" => "User",
            "wallet" => "Wallet Name",
            "amount" => "Amount",
            "type" => "Type",
            "balance" => "Balance",
            "description" => "Description",
            "confirmed" => "Confirmed",
            "uuid" => "UUID",
        ],
        "filters" => [
            "accounts" => "Filter By Accounts",
        ]
    ],
    "wallets" => [
        "title" => "Wallets",
        "columns" => [
            "created_at" => "Date",
            "user" => "User",
            "name" => "Name",
            "balance" => "Balance",
            "credit" => "Credit",
            "debit" => "Debit",
            "uuid" => "UUID",
        ],
        "action" => [
            "title" => "Wallet Action",
            "current_balance" => "Current Balance",
            "credit" => "Credit",
            "debit" => "Debit",
            "type" => "Type",
            "amount" => "Amount",
        ],
        "filters" => [
            "accounts" => "Filter By Accounts",
        ]
    ],
];
