<?php

use App\Models\Bet;
use App\Models\HistoryCommission;
use App\Models\HistoryPool;
use App\Models\User;

return [
    'not_found' => 'Dữ liệu không tồn tại.',
    'not_found_with' => ':object không tìm thấy.',
    'invalid' => 'Dữ liệu bạn nhập không chính xác. Vui lòng thử lại.',
    'unauthenticated' => 'Vui lòng đăng nhập lại để tiếp tục.',
    'update_successfully' => 'Cập nhật thành công.',
    'created' => 'Tạo mới :object thành công.',
    'updated' => 'Cập nhật :object thành công.',
    'deleted' => 'Xóa :object thành công.',
    'status_updated' => 'Cập nhật trạng thái :object thành công.',
    'cant_delete' => 'Không thể xóa :object.',
    'label' => [
        'user' => 'người dùng',
        'coworking' => 'coworking',
        'restaurant' => 'restaurant',
        'staff' => 'staff',
        'room' => 'room',
        'menu' => 'menu',
        'service' => 'service',
        'service_option' => 'service option',
        'setting' => 'cài đặt',
    ],
    'coworking_history' => [
        'status' => 'In use',
        'in_status' => 'Used'
    ],
    'checkout_success' => 'Checkout successfully!',
    'qrcode' => [
        'room_in_use' => 'This room is in use.',
        'checked_in_success' => 'You have successfully checked-in :object.',
        'checked_out_success' => 'You have successfully checked-out :object.',
        'checked_in_fail' => 'You have failed to check-in :object.',
        'checked_out_fail' => 'You have failed to check-out :object.',
        'service' => 'Register successfully service.',
        'service_fail' => 'Register failed service.',
        'checked_in' => 'You have already checked-in :object.',
        'not_checked_in' => 'You haven\'t checked-in :object yet.',
    ],
    'history' => [
        'name_coworking' => 'Coworking space',
        'name_room' => 'Private room',
        'name_order' => 'Order food',
        'name_service' => 'Other services',
        'color_coworking' => '#CB5500',
        'color_room' => '#FE6E00',
        'color_order' => '#FFA05C',
        'color_service' => '#FFC79D',
    ],

    'transaction' => [
        'invalid' => 'Giá trị không hợp lệ.'
    ],
    'commission' => [
        'type' => [
            HistoryCommission::TYPE_BET_COMMISSION => 'Hoa hồng cược',
            HistoryCommission::TYPE_VIP_COMMISSION => 'Hoa hồng vip',
            HistoryCommission::TYPE_NORMAL_COMMISSION => 'Hoa hồng thường',
        ],
    ],
    'pool' => [
        'type' => [
            HistoryPool::TYPE_BET => "Tiền cược",
            HistoryPool::TYPE_PAY => "Tiền trả",
        ],
    ],
    'bet' => [
        'demo' => [
            Bet::REAL_TYPE => 'Thật',
            Bet::DEMO_TYPE => 'Thử',
        ],
        'bet_type' => [
            Bet::UP => 'Mua',
            Bet::DOWN => 'Bán',
        ],
    ],

    'buy_ticket_success' => 'Bạn đã mua hộp may mắn thành công.',
    'upload_image' => 'Tải ảnh lên thành công!',
    'not_enough' => 'Tài khoản của bạn không đủ tiền!',
    'no_ticket' => 'Bạn không có hộp may mắn nào.',

    'agency' => [
        'success' => 'Mua đại lý thành công.',
        'vip' => 'Bạn đã là đại lý vip!',
    ],
    'user' => [
        'agency_status' => [
            User::AGENCY_STATUS_NON => 'Không',
            User::AGENCY_STATUS_REGULAR => 'Đại lý thường',
            User::AGENCY_STATUS_VIP => 'Đại lý vip',
        ],
    ],
    'register_validation' => [
        'email' => 'Tài khoản này đã được đăng ký. Vui lòng hãy đăng nhập.',
    ],
    'verify' => [
        'register_verified' => 'Tài khoản của bạn đã được kích hoạt.',
        'forgot_password_verified' => 'Yêu cầu thay đổi mật khẩu của bạn đã hết hạn.',
    ],
    'user_setting' => [
        'success' => 'Cài đặt đã thay đổi thành công',
    ],

];
