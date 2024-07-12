<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'throttle_user' => 'The maximum number of times the authentication code can be sent has been exceeded. Please try again in :seconds seconds.',
    'logout_success' => 'Logged out.',
    'login_failed' => 'Login failed.',
    'register_success' => 'You have successfully created an account. Please check your email to verify your account.',
    'verify_success' => 'Your account has been successfully verified.',
    'register_fail' => 'Registration failed.',
    'forgot_password' => [
        'success' => 'You have successfully changed your password.',
        'fail' => 'Change password fail.',
        'send_email' => 'The password reset link has been successfully sent to your email. Please check your email to reset your password.',
        'verify_email' => 'Password changed successfully.',
    ],
    'virtual_balance' => [
        'success' => 'Reset virtual balance success.',
        'fail' => 'Reset virtual balance fail.',
    ],

    'login_status' => [
        'inactive' => 'The account has not been verified. Please verify to use.',
        'lock' => 'Your account is locked.',
    ],

];
