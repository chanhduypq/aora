@extends('layouts.app')
@section('content')
<div class="page search">
    <div class="container">
        <div class="search-body">
            <h2 class="search-title">Checkout</h2>
            <div class="checkout">
                <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
                <script type="text/javascript">
                    Stripe.setPublishableKey('{{ env('STRIPE_API_KEY') }}');
                </script>
                <form action="{{ route('stripe.checkout') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" value="{{ $order->id }}" name="order_id">
                    <input type="hidden" value="{{ $order->payment_hash }}" name="payment_hash">
                    <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="{{ env('STRIPE_API_KEY') }}"
                            data-amount="{{ round($totalPrice, 2) * 100 }}"
                            data-name="Your order #{{ $order->id }}"
                            data-description="(${{ round($totalPrice, 2) }})"
                            data-label="Pay with Card"
                            data-locale="auto"
                            data-currency="sgd">
                    </script>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
