<?php

use App\Models\Bet;
use App\Models\HistoryCommission;
use App\Models\HistoryPool;
use App\Models\User;

return [
    'not_found' => 'Data not found.',
    'not_found_with' => ':object not found.',
    'invalid' => 'The data you entered is incorrect. Please try again.',
    'unauthenticated' => 'Please log in again to continue.',
    'update_successfully' => 'Update successful.',
    'created' => 'Create new :object successfully.',
    'updated' => 'Update :object successfully.',
    'deleted' => 'Delete :object successfully.',
    'status_updated' => 'Update status :object successfully.',
    'cant_delete' => 'Can not delete this :object.',
    'label' => [
        'user' => 'user',
        'coworking' => 'coworking',
        'restaurant' => 'restaurant',
        'staff' => 'staff',
        'room' => 'room',
        'menu' => 'menu',
        'service' => 'service',
        'service_option' => 'service option',
        'setting' => 'setting',
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
        'invalid' => 'Invalid amount.'
    ],
    'commission' => [
        'type' => [
            HistoryCommission::TYPE_BET_COMMISSION => 'Volume',
            HistoryCommission::TYPE_VIP_COMMISSION => 'Vip',
            HistoryCommission::TYPE_NORMAL_COMMISSION => 'Regular',
        ],
    ],
    'pool' => [
        'type' => [
            HistoryPool::TYPE_BET => "Bet",
            HistoryPool::TYPE_PAY => "Pay",
        ],
    ],
    'bet' => [
        'demo' => [
            Bet::REAL_TYPE => 'Live',
            Bet::DEMO_TYPE => 'Demo',
        ],
        'bet_type' => [
            Bet::UP => 'Buy',
            Bet::DOWN => 'Sell',
        ],
    ],

    'buy_ticket_success' => 'You have successfully purchased lucky box.',
    'upload_image' => 'Photo uploaded successfully!',
    'not_enough' => 'Your account has insufficient funds!',
    'no_ticket' => "You don't have any lucky box.",

    'agency' => [
        'success' => 'Purchase agency successful.',
        'vip' => 'You are a VIP agent!',
    ],
    'user' => [
        'agency_status' => [
            User::AGENCY_STATUS_NON => 'None',
            User::AGENCY_STATUS_REGULAR => 'Regular agency',
            User::AGENCY_STATUS_VIP => 'Vip agency',
        ],
    ],
    'register_validation' => [
        'email' => 'This account already exists. Please log in.',
    ],
    'verify' => [
        'register_verified' => 'Your account has been verified.',
        'forgot_password_verified' => 'Your password change request has expired.',
    ],
    'user_setting' => [
        'success' => 'Settings changed successfully',
    ],

];
