<?php

use App\Models\TransactionDetail;

return [
    'type' => [
        0 => 'all',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'internal_deposit',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'internal_withdraw',
        TransactionDetail::TRANSACTION_TYPE_DEPOSIT => 'deposit',
        TransactionDetail::TRANSACTION_TYPE_WITHDRAW => 'withdraw',
        TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'buy_normal',
        TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'buy_vip',
    ],
];
