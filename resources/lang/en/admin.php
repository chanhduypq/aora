<?php

return [
    'orders' => [
        'status' => [
            \App\Order::ORDER_UNPAID => 'Unpaid',
            \App\Order::ORDER_CANCEL => 'Cancel',
            \App\Order::ORDER_COMPLETE => 'Complete',
            \App\Order::ORDER_PROCESS => 'Process',
            \App\Order::ORDER_NEW => 'New',
        ]
    ]
];