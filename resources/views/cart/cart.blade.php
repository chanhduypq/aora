@extends('layouts.app')
@section('content')
    <div class="page search">
        <div class="container">
            <div class="search-body">
                <h2 class="search-title">Your Cart</h2>
                @if(!empty($cart))
                <div class="cart-body">
                    <div class="cart-table">
                        <div class="cart-thead">
                            <div class="cart-cell cart-cell--del">&nbsp;</div>
                            <div class="cart-cell cart-cell--photo">Photo</div>
                            <div class="cart-cell cart-cell--title">Title</div>
                            <div class="cart-cell cart-cell--qt">Qty</div>
                            <div class="cart-cell cart-cell--price">Price</div>
                        </div>

                        @foreach($cart as $item)
                            @include('cart._item')
                        @endforeach

                    </div>
                    <form action="{{ route('checkout') }}" method="post" id="cart-form" data-discount="{{ $discount }}" data-rate="{{ $rate }}" data-currency="{{ $siteCurrency }}" >
                        <h4 class="mt-3">Shipping preference</h4>
                        <p>This is an estimate shipping cost that will be charged by SingPost.</p>
                        <div class="cart-summary">
                            <div class="cart-total cart-total--mobile">
                                <span>Cart</span>
                                <strong>
                                    <strong class="order_subtotal">{{ number_format($total, 2) }}</strong> <small>{{ $siteCurrency }}</small>
                                </strong>
                            </div>
                            <div class="cart-shipping">
                                @include('partials.shipping_methods')
                            </div>
                            <div class="cart-total cart-total--mobile">
                                <span>AORA Savings</span>
                                <strong><strong class="discount-total">{{ number_format($discountTotal, 2) }}</strong> <small>{{ $siteCurrency }}</small></strong>
                            </div>
                            <div class="cart-last cart-last--mobile">
                                <span>Total</span>
                                <strong>
                                    <strong class="order_total">{{ number_format((($total - $discountTotal)), 2) }}</strong> <small>{{ $siteCurrency }}</small>
                                </strong>
                            </div>
                            <div class="cart-actions cart-actions--mobile">
                                <a href="" class="btn btn-primary submit-form"><i class="fa fa-align-left"></i>Proceed to checkout</a>
                            </div>
                        </div>
                        <div class="cart-summary cart-summary--last">
                            <div class="cart-total">
                                <span>Sub Total</span>
                                <strong>
                                    <strong class="order_subtotal">{{ number_format($total, 2) }}</strong> <small>{{ $siteCurrency }}</small>
                                </strong>
                            </div>
                            <div class="cart-total">
                                <span>AORA Savings</span>
                                <strong><strong class="discount-total">{{ number_format($discountTotal, 2) }}</strong> <small>{{ $siteCurrency }}</small></strong>
                            </div>
                            <div class="cart-last">
                                <span>Total (Excl. Shipping)</span>
                                <strong>
                                    <strong class="order_total">{{ number_format(($total - $discountTotal), 2) }}</strong>
                                    <small>{{ $siteCurrency }}</small>
                                </strong>
                            </div>
                            <div class="cart-last">
                                <div class="cart-actions">
                                    <a href="" class="btn btn-primary submit-form"><i class="fa fa-align-left"></i>Proceed to checkout</a>
                                </div>
                            </div>
                        </div>
                        <input id="shipping-total" name="shipping_total" value="0" type="hidden">
                        <input id="discount-total" name="discount" value="{{ $discountTotal }}" type="hidden">
                        {{ csrf_field() }}
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@include('cart._handler')