Hi {{ $order->user->name }},

Hi {{ $order->user->name }},
Shipment #2 of your Order {{ $order->id }}< has been shipped out from our warehouse, and is on its way to your doorstep.
We know you’re excited for your purchase to reach your hands. Track your order here, and let the anticipation bubble up!

Billing & Delivery Information
@if($order->shippingAddress)
    Delivery Address:
    {{ $order->shippingAddress->address }}
@endif
@if($order->billingAddress)
    Billing Address:
    {{ $order->billingAddress->address }}
@endif
@if($order->shippingMethod)
Delivery Dates
Shipment {{ $order->shippingMethod->name }}: {{ $order->shippingMethod->duration }} days
@endif
Tracking code: 582 249 248
Track order >

Order Information:
| Image       | Name         | Prices  |
| ------------- |:-------------:| --------:|
@foreach($order->products as $product)
|{{ $product->title }}|{{ $product->getTotalPrice() * $order->rate }} {{ $siteCurrency }}|
@endforeach

Subtotal: {{ $siteCurrency }} {{ number_format($order->getTotalPrice() * $order->rate, 2) }}
Shipping fee: {{ $siteCurrency }} {{ number_format($order->shipping_total * $order->rate, 2) }}
AORA Discount: -{{ $siteCurrency }} {{ number_format($order->discount * $order->rate, 2) }}
Total: {{ $siteCurrency }} {{ number_format($order->getFullTotalPrice(), 2) }}

You’ll hear from us again when your order has reached the warehouse. Have a great day ahead!
See you soon.