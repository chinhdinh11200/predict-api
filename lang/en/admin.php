<?php

use App\Admin\Metrics\Dashboard\ReporterUserBet;
use App\Models\Bet;
use App\Models\TicketHistory;
use App\Models\HistoryCommission;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\UserSetting;
use App\Admin\Metrics\Dashboard\TradeStats;
use App\Models\HistoryCommandBet;

return [
    'scaffold' => [
        'header'            => 'Scaffold',
        'choose'            => 'choose',
        'table'             => 'Table',
        'model'             => 'Model',
        'controller'        => 'Controller',
        'repository'        => 'Repository',
        'add_field'         => 'Add field',
        'pk'                => 'Primary key',
        'soft_delete'       => 'Soft delete',
        'create_migration'  => 'Create migration',
        'create_model'      => 'Create model',
        'create_repository' => 'Create repository',
        'create_controller' => 'Create controller',
        'run_migrate'       => 'Run migrate',
        'create_lang'       => 'Create lang',
        'field'             => 'field',
        'translation'       => 'translation',
        'comment'           => 'comment',
        'default'           => 'default',
        'field_name'        => 'field name',
        'type'              => 'type',
        'nullable'          => 'nullable',
        'key'               => 'key',
        'translate_title'   => 'Translate Title',
        'sync_translation_with_comment' => 'Sync translation and comment',
    ],
    'client' => [
        'delete_confirm'    => 'Are you sure to delete this item ?',
        'confirm'           => 'Confirm',
        'cancel'            => 'Cancel',
        'refresh_succeeded' => 'Refresh succeeded !',
        'close'             => 'Close',
        'selected_options'  => ':num options selected',
        'exceed_max_item'   => 'Maximum items exceeded.',
        'no_preview'        => 'No preview available.',

        '500' => 'Internal server error !',
        '403' => 'Permission deny !',
        '401' => 'Unauthorized !',
        '419' => 'Page expired !',
    ],
    'online'                => 'Online',
    'login'                 => 'Login',
    'logout'                => 'Logout',
    'setting'               => 'Setting',
    'name'                  => 'Name',
    'username'              => 'Username',
    'user'                  => 'User',
    'alias'                 => 'Alias',
    'routes'                => 'Routes',
    'route_action'          => 'Route Action',
    'middleware'            => 'Middleware',
    'method'                => 'Method',
    'old_password'          => 'Old password',
    'password'              => 'Password',
    'password_confirmation' => 'Password confirmation',
    'old_password_error'    => 'Incorrect password',
    'remember_me'           => 'Remember me',
    'user_setting'          => 'User setting',
    'avatar'                => 'Avatar',
    'list'                  => 'List',
    'new'                   => 'New',
    'create'                => 'Create',
    'delete'                => 'Delete',
    'remove'                => 'Remove',
    'edit'                  => 'Edit',
    'quick_edit'            => 'Quick Edit',
    'view'                  => 'View',
    'continue_editing'      => 'Continue editing',
    'continue_creating'     => 'Continue creating',
    'detail'                => 'Detail',
    'browse'                => 'Browse',
    'reset'                 => 'Reset',
    'export'                => 'Export',
    'batch_delete'          => 'Batch delete',
    'save'                  => 'Save',
    'refresh'               => 'Refresh',
    'order'                 => 'Order',
    'expand'                => 'Expand',
    'collapse'              => 'Collapse',
    'filter'                => 'Filter',
    'search'                => 'Search',
    'close'                 => 'Close',
    'show'                  => 'Show',
    'entries'               => 'entries',
    'captcha'               => 'Captcha',
    'action'                => 'Action',
    'title'                 => 'Title',
    'description'           => 'Description',
    'back'                  => 'Back',
    'back_to_list'          => 'Back to List',
    'submit'                => 'Submit',
    'menu'                  => 'Menu',
    'input'                 => 'Input',
    'succeeded'             => 'Succeeded',
    'failed'                => 'Failed',
    'delete_confirm'        => 'Are you sure to delete this item ?',
    'delete_succeeded'      => 'Delete succeeded !',
    'delete_failed'         => 'Delete failed !',
    'update_succeeded'      => 'Update succeeded !',
    'update_failed'         => 'Update failed !',
    'save_succeeded'        => 'Save succeeded !',
    'save_failed'           => 'Save failed !',
    'refresh_succeeded'     => 'Refresh succeeded !',
    'login_successful'      => 'Login successful',
    'choose'                => 'Choose',
    'choose_file'           => 'Select file',
    'choose_image'          => 'Select image',
    'more'                  => 'More',
    'deny'                  => 'Permission denied',
    'administrator'         => 'Administrator',
    'no_data'               => 'No data.',
    'roles'                 => 'Roles',
    'permissions'           => 'Permissions',
    'slug'                  => 'Slug',
    'created_at'            => 'Created At',
    'updated_at'            => 'Updated At',
    'alert'                 => 'Alert',
    'parent_id'             => 'Parent',
    'icon'                  => 'Icon',
    'uri'                   => 'URI',
    'operation_log'         => 'Operation log',
    'parent_select_error'   => 'Parent select error',
    'tree'                  => 'Tree',
    'table'                 => 'Table',
    'default'               => 'Default',
    'import'                => 'Import',
    'is_not_import'         => 'No',
    'selected_options'      => ':num options selected',
    'pagination'            => [
        'range' => 'Showing :first to :last of :total entries',
    ],
    'role'                  => 'Role',
    'permission'            => 'Permission',
    'route'                 => 'Route',
    'confirm'               => 'Confirm',
    'cancel'                => 'Cancel',
    'selectall'             => 'Select all',
    'http'                  => [
        'method' => 'HTTP method',
        'path'   => 'HTTP path',
    ],
    'all_methods_if_empty'  => 'All methods if empty',
    'all'                   => 'All',
    'current_page'          => 'Current page',
    'selected_rows'         => 'Selected rows',
    'upload'                => 'Upload',
    'new_folder'            => 'New folder',
    'time'                  => 'Time',
    'size'                  => 'Size',
    'between_start'         => 'Start',
    'between_end'           => 'End',
    'next_page'             => 'Next',
    'prev_page'             => 'Previous',
    'next_step'             => 'Next',
    'prev_step'             => 'Previous',
    'done'                  => 'Done',
    'listbox'               => [
        'text_total'         => 'Showing all {0}',
        'text_empty'         => 'Empty list',
        'filtered'           => '{0} / {1}',
        'filter_clear'       => 'Show all',
        'filter_placeholder' => 'Filter',
    ],
    'responsive' => [
        'display_all' => 'Display all',
        'display'     => 'Display',
        'focus'       => 'Focus',
    ],
    'uploader' => [
        'add_new_media'          => 'Browse',
        'drag_file'              => 'Or drag file here',
        'max_file_limit'         => 'The :attribute may not be greater than :max.',
        'exceed_size'            => 'Exceeds the maximum file-size',
        'interrupt'              => 'Interrupt',
        'upload_failed'          => 'Upload failed! Please try again.',
        'selected_files'         => ':num files selected，size: :size。',
        'selected_has_failed'    => 'Uploaded: :success, failed: :fail, <a class="retry"  href="javascript:"";">retry </a>or<a class="ignore" href="javascript:"";"> ignore</a>',
        'selected_success'       => ':num(:size) files selected, Uploaded: :success.',
        'dot'                    => ', ',
        'failed_num'             => 'failed::fail.',
        'pause_upload'           => 'Pause',
        'go_on_upload'           => 'Go On',
        'start_upload'           => 'Upload',
        'upload_success_message' => ':success files uploaded successfully',
        'go_on_add'              => 'New File',
        'Q_TYPE_DENIED'          => 'Sorry, the type of this file is not allowed!',
        'Q_EXCEED_NUM_LIMIT'     => 'Sorry, maximum number of allowable file uploads has been exceeded!',
        'F_EXCEED_SIZE'          => 'Sorry，the maximum file-size has been exceeded!',
        'Q_EXCEED_SIZE_LIMIT'    => 'Sorry, the maximum file-size has been exceeded!',
        'F_DUPLICATE'            => 'Duplicate file.',
        'confirm_delete_file'    => 'Are you sure delete this file from server?',
        'dimensions'             => 'The image dimensions is invalid.',
    ],
    'import_extension_confirm'  => 'Are you sure import the extension?',
    'quick_create'              => 'Quick create',
    'grid_items_selected'       => '{n} items selected',
    'nothing_updated'           => 'Nothing has been updated.',
    'welcome_back'              => 'Welcome back, please login to your account.',
    'documentation'             => 'Documentation',
    'demo'                      => 'Demo',
    'extensions'                => 'Extensions',
    'version'                   => 'Version',
    'current_version'           => 'Current version',
    'latest_version'            => 'Latest version',
    'upgrade_to_version'        => 'Upgrade to version :version',
    'enable'                    => 'Enable',
    'disable'                   => 'Disable',
    'uninstall'                 => 'Uninstall',
    'confirm_uninstall'         => 'Please confirm that you wish to uninstall this extension. This may result in potential data loss.',
    'marketplace'               => 'Marketplace',
    'theme'                     => 'Theme',
    'application'               => 'Application',
    'install_from_local'        => 'Install From Local',
    'install_succeeded'         => 'Install succeeded !',
    'invalid_extension_package' => 'Invalid extension package !',
    'copied'                    => 'Copied',
    'auth_failed'               => 'These credentials do not match our records.',
    'validation'               => [
        'match'     => 'The :attribute and :other must match.',
        'minlength' => 'The :attribute must be at least :min characters.',
        'maxlength' => 'The :attribute may not be greater than :max characters.',
    ],
    'view_button_table' => 'View',
    'users' => [
        'id' => 'ID',
        'username' => 'Username',
        'fullname' => 'Full name',
        'avatar' => 'Avatar',
        'email' => 'Email',
        'refcode' => 'Ref code',
        'real_balance' => 'Real balance',
        'bet_amount' => 'Số tiền cược',
        'virtual_balance' => 'Virtual balance',
        'usdt_balance' => 'Usdt balance',
        'level' => 'Level',
        'agency_status' => 'Agency',
        'total_tickets' => 'Total tickets',
        'status' => 'Status',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'status_lang' => [
            User::STATUS_ACTIVE => 'Active',
            User::STATUS_INACTIVE => 'Inactive',
            User::STATUS_LOCK => 'Lock',
        ],
        'agency_status_lang' => [
            User::AGENCY_STATUS_NON => 'None',
            User::AGENCY_STATUS_REGULAR => 'Regular',
            User::AGENCY_STATUS_VIP => 'Vip',
        ],
        'title' => [
            'list' => 'User',
            'show' => 'Detail user',
            'update' => 'Cập nhật',
            'user_online' => 'User online',
            'user_bet' => 'Regulation',
        ],
        'setting' => [
            'title_edit' => 'Setting user',
            'title' => 'Setting',
            'transaction_title' => 'Lock transaction',
            'marketing_title' => 'Marketing',
            'revert_title' => 'Revert',
            'status_transaction' => [
                UserSetting::NONE_LOCK_TRANSACTION => 'No',
                UserSetting::LOCK_TRANSACTION => 'Yes',
            ],
            'status_marketing' => [
                UserSetting::NONE_MARKETING => 'No',
                UserSetting::MARKETING => 'Yes',
            ],
            'status_revert' => [
                UserSetting::NONE_REVERT => 'No',
                UserSetting::REVERT => 'Yes',
            ],
        ],
        'bet_type' => [
            Bet::UP => 'Buy',
            Bet::DOWN => 'Sell',
        ],
        'bet_summary' => [
            'summary' => 'Summary',
            'buy' => 'Total bet buy',
            'sell' => 'Total bet sell',
            'pool_buy' => 'Pool buy',
            'pool_sell' => 'Pool sell',
            'regulation' => 'Regulation',
            'regulation_success' => 'Regulation success.',
            'regulation_error' => 'Regulation failed.',
            'type_session' => [
                'bet' => 'Bet time :',
                'result' => 'Result time :',
            ]
        ],
    ],

    'transaction_detail' => [
        'title' => [
            'list' => 'Transaction',
        ],
        'confirm_withdraw' => 'Confirm',
        'user_filter_label' => 'Transaction user',
        'type_filter_label' => 'Transaction type',
        'id' => 'ID',
        'user_id' => 'Transaction user',
        'tx' => 'Tx',
        'username' => 'Username',
        'address' => 'Address',
        'amount' => 'Amount',
        'fee' => 'Fee',
        'note' => 'Note',
        'type' => 'Transaction type',
        'status' => 'Status',
        'type_lang' => [
            TransactionDetail::TRANSACTION_TYPE_DEPOSIT => 'Deposit',
            TransactionDetail::TRANSACTION_TYPE_WITHDRAW => 'Withdraw',
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Internal deposit',
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Internal withdraw',
            TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Buy regular',
            TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Buy vip',
        ],
        'status_lang' => [
            TransactionDetail::TRANSACTION_STATUS_CANCELLED => 'Cancelled',
            TransactionDetail::TRANSACTION_STATUS_COMPLETED => 'Completed',
            TransactionDetail::TRANSACTION_STATUS_FAILED => 'Failed',
            TransactionDetail::TRANSACTION_STATUS_PENDING => 'Pending',
        ],
        'approve' => [
            TransactionDetail::TRANSACTION_STATUS_CANCELLED => 'Reject',
            TransactionDetail::TRANSACTION_STATUS_COMPLETED => 'Approve',
        ],
    ],
    'lucky_wheel' => [
        'title' => [
            'list' => 'Lucky wheel'
        ]
    ],
    'ticket_history' => [
        'title' => [
            'list' => 'Ticket history'
        ],
        'user_id' => 'User',
        'quantity' => 'Quantity',
        'prize' => 'Prize',
        'value' => 'Value',
        'type' => 'Type',
        'type_lang' => [
            TicketHistory::TYPE_BUY => 'Buy',
            TicketHistory::TYPE_USE => 'Use',
            TicketHistory::TYPE_REFUND => 'Refund',
        ],
    ],
    'settings' => [
        'title' => [
            'list' => 'Settings'
        ],
        'key' => ' ',
        'value' => 'Value',
        'type' => 'Type',
        'trade_min' => 'Trade fee',
        'withdraw_fee' => 'Withdraw fee',
        'start_golden_hour' => 'Start golden hour',
        'end_golden_hour' => 'End golden hour',
        'ticket_price_golden_hour' => 'Ticket price golden hour',
        'ticket_price' => 'Ticket price',
        'profit_bet' => 'Profit bet',
        'basic_agency_fee' => 'Basic agency fee',
        'vip_agency_fee' => 'Vip agency fee',
        'rate_to_system_wallet' => 'Rate to system wallet',
    ],
    'commission' => [
        'title' => [
            'list' => 'History commission',
        ],
        'user_id' => 'Receiver',
        'from_user_id' => 'Sender',
        'value' => 'Value',
        'type' => 'Type',
        'note' => 'Note',
        'type_lang' => [
            HistoryCommission::TYPE_BET_COMMISSION => 'Bet commission',
            HistoryCommission::TYPE_VIP_COMMISSION => 'Vip commission',
            HistoryCommission::TYPE_NORMAL_COMMISSION => 'Normal commission',
        ],
    ],
    'dashboard' => [
        'win' => 'Win',
        'lose' => 'Lose',
        'amount_win_sub_lose' => 'Win - Lose', 
        'amount_win_div_lose' => 'Win : Lose', 
        'trade' => [
            TradeStats::ONE_DAY => 'Today',
            TradeStats::ONE_WEEK => 'Last week',
            TradeStats::ONE_MONTH => 'Last month',
            TradeStats::ONE_YEAR => 'Last year',
            TradeStats::ALL => 'All',
        ],
        'title' => [
            'profit' => 'Net profit',
            'revenue' => 'Total revenue',
            'trade' => 'Total trade',
            'win' => 'Total win round',
            'lose' => 'Total lose round',
            'buy' => 'Buy',
            'sell' => 'Sell',
            'win_rate' => 'Win rate',
            'trade_summary' => 'Trade summary',
            'list' => 'Betting user statistics',
            'filter_date' => 'Select date',
        ],
        'win_lose' => [
            Bet::WIN => 'Win',
            Bet::LOSE => 'Lose',
        ],
        'report_user_bet' => [
            ReporterUserBet::ONE_DAY => 'Today',
            ReporterUserBet::ONE_WEEK => 'Last Week',
            ReporterUserBet::ONE_MONTH => 'Last Month',
            ReporterUserBet::ALL => 'All',
        ],
    ],

    'transaction_history' => [
        'title' => [
            'list' => 'Transaction History',
        ],
        'confirm_withdraw' => 'Confirm',
        'user_filter_label' => 'Transaction user',
        'type_filter_label' => 'Transaction type',
        'id' => 'ID',
        'user_id' => 'Transaction user',
        'tx' => 'Tx',
        'username' => 'Username',
        'address' => 'Address',
        'amount' => 'Amount',
        'fee' => 'Fee',
        'note' => 'Note',
        'type' => 'Transaction type',
        'status' => 'Status',
        'created_at' => 'Time',
        'type_lang' => [
            TransactionDetail::TRANSACTION_TYPE_DEPOSIT => 'Deposit',
            TransactionDetail::TRANSACTION_TYPE_WITHDRAW => 'Withdraw',
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Internal deposit',
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Internal withdraw',
            TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Buy regular',
            TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Buy vip',
        ],
        'status_lang' => [
            TransactionDetail::TRANSACTION_STATUS_CANCELLED => 'Cancelled',
            TransactionDetail::TRANSACTION_STATUS_COMPLETED => 'Completed',
            TransactionDetail::TRANSACTION_STATUS_FAILED => 'Failed',
            TransactionDetail::TRANSACTION_STATUS_PENDING => 'Pending',
        ],
        'tx_lang' => [
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_DEPOSIT => 'Funding -> Live',
            TransactionDetail::TRANSACTION_TYPE_INTERNAL_WITHDRAW => 'Live -> Funding',
            TransactionDetail::TRANSACTION_TYPE_BUY_VIP => 'Vip',
            TransactionDetail::TRANSACTION_TYPE_BUY_NORMAL => 'Regular',
            TransactionDetail::TRANSACTION_TYPE_WITHDRAW => '',
            TransactionDetail::TRANSACTION_TYPE_DEPOSIT => '',
        ],
    ],
    'command' => [
        'title' => [
            'list' => 'History regulation'
        ],
        'session_id' => 'Session',
        'type' => 'Reverse/Regulation command',
        'type_target' => 'Buy/Sell',
        'type_lang' => [
            HistoryCommandBet::COMMAND_REGULATION_TYPE => 'Regulation',
            HistoryCommandBet::COMMAND_REVERSE_TYPE => 'Reverse',
        ],
        'type_target_lang' => [
            HistoryCommandBet::SELL_TYPE => 'Sell',
            HistoryCommandBet::BUY_TYPE => 'Buy',
        ],
    ],
    'user_marketing' => [
        'title' => [
            'list' => 'User marketing',
        ],
    ],
    'pool' => [
        'title' => [
            'value' => 'Pool',
        ],
        'value' => 'Pool value',
        'value_deposit' => 'Value deposit',
        'value_withdraw' => 'Value withdraw',
        'deposit' => 'Deposit',
        'withdraw' => 'Withdraw',
        'pool_not_enough' => 'Pool not enough.',
        'wallet_not_enough' => 'System wallet not enough.',
        'btn_deposit' => 'Deposit',
        'btn_withdraw' => 'Withdraw',
    ],
    'bet' => [
        'user_id' => 'User bet',
        'amount' => 'Amount',
        'session_id' => 'Session',
        'reward' => 'Reward',
        'result' => 'Result',
        'is_demo' => 'Type bet',
        'bet_type' => 'Type user bet',
        'filter' => [
            'user' => 'User bet',
            'type_result' => 'Type result',
            'type_bet' => 'Type Bet',
            'type_user_bet' => 'Type user bet',
            'date_between'=> 'Date filter' 
        ],
        'title' => [
            'list' => 'List bets',
        ],
        'is_demo_lang' => [
            Bet::DEMO_TYPE => 'Demo bet',
            Bet::REAL_TYPE => 'Real bet',
        ],
        'bet_type_lang' => [
            Bet::UP => 'Mua',
            Bet::DOWN => 'Bán',
        ],
        'is_result_lang' => [
            Bet::NO_ACTION => 'No result',
            Bet::EXECUTED_RESULT => 'Resulted',
        ],
        'result_lang' => [
            'no_result' => '',
            Bet::UP => 'Buy',
            Bet::DOWN => 'Sell',
        ]
    ],
];
