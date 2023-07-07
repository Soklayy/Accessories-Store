<?php

return[
    //role for user
    'role'=>[
        'user'=>'user',//buyer
        'admin' => 'admin',
        'seller'=> 'seller'
    ],


    //payment
    'payment'=>[
        'merchant_id'=>env('ABA_PAYWAY_MERCHANT_ID'),
        'public_key' =>env('ABA_PAYWAY_PUBLIC_KEY'),
        'checkout_api_url'=>env('ABA_PAYWAY_API_URL')
    ],
];