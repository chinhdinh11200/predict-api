<?php

use App\Models\TransactionDetail;

return [
    'success' => 'Giao dịch thành công.',
    'wallet_fail' => 'Ví đã tồn tại.',
    'generate_fail' => 'Tạo ví nạp tiền thất bại.',
    'type' => [
        'all' => 'Tất cả',
        'deposit' => 'Nạp tiền nội bộ',
        'withdraw' => 'Rút tiền nội bộ',
        'internal_deposit' => 'Chuyển: Quỹ -> Trực tiếp',
        'internal_withdraw' => 'Chuyển: Trực tiếp -> Quỹ',
        'buy_normal' => 'Hoa hồng thông thường',
        'buy_vip' => 'Hoa hồng VIP',
    ],

    'cannot_withdraw' => 'Tài khoản của bạn không thể rút tiền.',
    'type_lang' => [
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Chuyển',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Chuyển',
        TransactionDetail::TRANSACTION_TYPE_WITHDRAW => 'Rút tiền',
        TransactionDetail::TRANSACTION_TYPE_DEPOSIT => 'Nạp tiền',
        TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Mua gói',
        TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Mua gói',
    ],
    'tx' => [
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Quỹ -> Trực tiếp',
        TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Trực tiếp -> Quỹ',
        TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Vip',
        TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Thường',
        TransactionDetail::TRANSACTION_TYPE_WITHDRAW => '',
        TransactionDetail::TRANSACTION_TYPE_DEPOSIT => '',
    ],
    'usdt_to_live_success' => 'Bạn đã chuyển thành công tiền từ Ví USDT sang tài khoản thực.',
    'live_to_usdt_success' => 'Bạn đã chuyển thành công tiền từ tài khoản thực sang Ví USDT.',
];
