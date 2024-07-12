<?php

return [
    'verify' => [
        'token' => [
            'length' => 60
        ],
    ],

    'virtual_balance' => [
        'reset_value' => 1000,
    ],

    'transaction' => [
        'fee' => 1,
    ],

    'spin_probability' => 100000,

    'minimum_withdraw_amount' => env('MINIMUM_WITHDRAW_AMOUNT', 1000),

    'expired_session_time' => env('EXPIRED_SESSION_TIME', 30) // minutes

];
