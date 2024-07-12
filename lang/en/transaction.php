<?php

use App\Models\TransactionDetail;

return [
    'success' => 'Transaction successful.',
    'wallet_fail' => 'Wallet exist.',
    'generate_fail' => 'Generate deposit wallet fail.',
    'type' => [
        'all' => 'All',
        'deposit' => 'Deposit',
        'withdraw' => 'Withdraw',
        'internal_deposit' => 'Transfer: Funding -> Live',
        'internal_withdraw' => 'Transfer: Live -> Funding',
        'buy_normal' => 'Commission normal',
        'buy_vip' => 'Commission vip',
    ],

    'cannot_withdraw' => 'Your account cannot be withdrawn.',
    'type_lang' => [
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Transfer',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Transfer',
        TransactionDetail::TRANSACTION_TYPE_WITHDRAW => 'Withdraw',
        TransactionDetail::TRANSACTION_TYPE_DEPOSIT => 'Deposit',
        TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Buy package',
        TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Buy package',
    ],
    'tx' => [
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Funding -> Live',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Live -> Funding',
        TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Vip',
        TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Regular',
        TransactionDetail::TRANSACTION_TYPE_WITHDRAW => '',
        TransactionDetail::TRANSACTION_TYPE_DEPOSIT => '',
    ],
    'usdt_to_live_success' => 'You have successfully transferred funds from USDT Wallet to Live account.',
    'live_to_usdt_success' => 'You have successfully transferred funds from Live account to USDT Wallet.',
];
