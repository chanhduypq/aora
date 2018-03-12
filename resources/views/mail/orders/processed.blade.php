@component('mail::message')
<strong>Hi {{ $order->user->name }}</strong>,<br>
Shipment #2 of your Order {{ $order->id }}< has been shipped out from our warehouse, and is on its way to your doorstep.<br>
We know you’re excited for your purchase to reach your hands. Track your order here, and let the anticipation bubble up! <br>
<br>
@component('mail::panel')
<strong>Billing & Delivery Information</strong><br>
@if($order->shippingAddress)
Delivery Address:<br>
{{ $order->shippingAddress->address }}<br>
@endif
@if($order->billingAddress)
Billing Address:<br>
{{ $order->billingAddress->address }}<br>
@endif
@endcomponent
@component('mail::panel')
@if($order->shippingMethod)
    <strong>Delivery Dates</strong><br>
    Shipment by {{ $order->shippingMethod->name }}: {{ $order->shippingMethod->duration }} days<br>
@endif
@endcomponent
@component('mail::panel')
<strong>Tracking code: 582 249 248</strong>
    @component('mail::button', ['url' => '', 'color' => 'blue'])
    Track order >
    @endcomponent
@endcomponent
<strong>Order Information</strong>
@component('mail::table')
| Image       | Name         | Prices  |
| ------------- |:-------------:| --------:|
@foreach($order->products as $product)
|<img width="150" src="{{ $product->image }}">|{{ $product->title }}|{{ $product->getTotalPrice()  * $order->rate }} {{ $siteCurrency }}|
@endforeach
@endcomponent
<br>
Subtotal: {{ $siteCurrency }} {{ number_format($order->getTotalPrice() * $order->rate, 2) }}<br>
Shipping fee: {{ $siteCurrency }} {{ number_format($order->shipping_total * $order->rate, 2) }}<br>
AORA Discount: -{{ $siteCurrency }} {{ number_format($order->discount * $order->rate, 2) }}<br>
<strong>Total: {{ $siteCurrency }} {{ number_format($order->getFullTotalPrice(), 2) }}</strong>
<br>
You’ll hear from us again when your order has reached the warehouse. Have a great day ahead!<br>
See you soon.
@endcomponent