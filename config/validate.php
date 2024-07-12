<?php

return [

    'max_length' => [
        'card_number' => 16,
        'email' => 50,
        'name' => 50,
        'password' => 32,
        'phone' => 12,
        'string' => 255,
        'zipcode' => 7,
        'refcode' => 6,
    ],

    'min_length' => [
        'phone' => 10,
        'zipcode' => 7,
    ],

    'max_value' => [
        'numeric' => 9000000,
        'quantity' => 99,
        'percent' => 100,
    ],

    'min_value' => [
        'quantity' => 1,
        'percent' => 0,
    ],

];
