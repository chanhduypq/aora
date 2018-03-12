<?php

return [
    'status' => [
        \App\Order::ORDER_UNPAID => 'Unpaid',
        \App\Order::ORDER_NEW => 'New',
        \App\Order::ORDER_LEAVING_USA => 'Leaving USA',
        \App\Order::ORDER_ARRIVED_SG => 'Arrived in Singapore',
        \App\Order::ORDER_SHIPPING => 'Expect shipping',
        \App\Order::ORDER_COMPLETE => 'Complete',
        \App\Order::ORDER_CANCEL => 'Cancel',
        \App\Order::ORDER_REFUND => 'Refund',
        \App\Order::ORDER_PROCESSING => 'Processing',
    ]

];