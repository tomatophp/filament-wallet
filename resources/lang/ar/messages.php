<?php

return [
    'group' => 'المحفظة',
    'transactions' => [
        'title' => 'المعاملات',
        'single' => 'معاملة',
        'columns' => [
            'created_at' => 'التاريخ',
            'user' => 'المستخدم',
            'wallet' => 'اسم المحفظة',
            'amount' => 'المبلغ',
            'type' => 'النوع',
            'balance' => 'الرصيد',
            'description' => 'الوصف',
            'confirmed' => 'تأكيد',
            'uuid' => 'الكود',
        ],
        'filters' => [
            'accounts' => 'تصفية حسب الحسابات',
        ],
    ],
    'wallets' => [
        'title' => 'المحافظ',
        'columns' => [
            'created_at' => 'التاريخ',
            'user' => 'المستخدم',
            'name' => 'الاسم',
            'balance' => 'الرصيد',
            'credit' => 'إيداع',
            'debit' => 'سحب',
            'uuid' => 'الكود',
        ],
        'action' => [
            'title' => 'تحويلات المحفظة',
            'current_balance' => 'الرصيد الحالي',
            'credit' => 'إيداع',
            'debit' => 'سحب',
            'type' => 'النوع',
            'amount' => 'المبلغ',
        ],
        'filters' => [
            'accounts' => 'تصفية حسب الحسابات',
        ],
        'notification' => [
            'title' => 'رصيد المحفظة',
            'message' => 'تم تحديث رصيد المحفظة بنجاح',
        ],
    ],
];
