@extends('layouts.app')
@section('content')
<div class="page search">
    <div class="container">
        <div class="search-body">
            <h2 class="search-title">Checkout</h2>
            <div class="checkout">
                <form method="post" action="{{ route('checkout.process') }}" id="checkout-form">
                {{ csrf_field() }}
                <input type="hidden" name="shipping_method" value="{{ $shippingMethod }}" />
                <input type="hidden" name="shipping_total" value="{{ $shippingTotal }}" />
                <input type="hidden" name="discount" value="{{ $discountTotal }}" />
                <input type="hidden" name="rate" value="{{ $rate }}" />
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="checkout-form">
                            <div class="checkout-user checkout-user--shipping">
                                <h3 class="checkout-subtitle">Shipping Address</h3>
                                <div class="addresses">
                                    @if(!$shippings->isEmpty())
                                        @foreach($shippings as $shipping)
                                        <label class="custom-control custom-radio js-show-add-new">
                                            <input required id="radio1" checked="checked" name="shipping_id" value="{{ $shipping->id }}" type="radio" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">{{ $shipping->full_name }}<b>{{ $shipping->address }}, {{ $shipping->postal_code }}</b></span>
                                        </label>
                                        @endforeach
                                    @endif
                                    <label class="custom-control custom-radio js-show-add-new">
                                        <input required id="radio2" name="shipping_id" type="radio" value="new" class="custom-control-input js-create-new-address">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">New address</span>
                                    </label>
                                </div>
                                <div class="checkout-fieldset addresses-create">
                                    <div class="checkout-card address-block">
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Full Name</label>
                                            <input name="shipping[full_name]" class="form-control" type="text" value="" placeholder="Your name">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Address</label>
                                            <input name="shipping[address]" class="form-control address" type="text" value="" placeholder="Where to deliver?">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Postal Code</label>
                                            <input name="shipping[postal_code]" class="form-control postal-code" type="text" value="" placeholder="Your Postal Code">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Phone Number</label>
                                            <input name="shipping[phone]" class="form-control" type="text" value="" placeholder="+65">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-user checkout-user--billing">
                                <h3 class="checkout-subtitle">Billing Address</h3>
                                <div class="addresses">
                                    <label class="custom-control custom-checkbox js-same-as-shipping">
                                        <input id="radio4" checked="checked" name="billing_id" value="shipping" type="checkbox" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Same as Shipping address</span>
                                    </label>
                                    <div class="checkout-notsame">
                                        @if(!$billings->isEmpty())
                                            @foreach($billings as $billing)
                                            <label class="custom-control custom-radio js-show-add-new">
                                                <input required id="billing{{ $billing->id }}" name="billing_id" value="{{ $billing->id }}" type="radio" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">{{ $billing->full_name }}<b>{{ $billing->address }}, {{ $billing->postal_code }}</b></span>
                                            </label>
                                            @endforeach
                                        @endif
                                        <label class="custom-control custom-radio js-show-add-new">
                                            <input required id="radio6" name="billing_id" type="radio" value="new" class="custom-control-input js-create-new-address">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">New address</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="checkout-fieldset addresses-create">
                                    <div class="checkout-card address-block">
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Full Name</label>
                                            <input name="billing[full_name]" class="form-control" type="text" value="" placeholder="Your name">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Address</label>
                                            <input name="billing[address]" class="form-control address" type="text" value="" placeholder="Where to deliver?">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Postal Code</label>
                                            <input name="billing[postal_code]" class="form-control postal-code" type="text" value="" placeholder="Your Postal Code">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="">Phone Number</label>
                                            <input name="billing[phone]" class="form-control" type="text" value="" placeholder="+65">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="checkout-form checkout-form--new-card">
                            <h3 class="checkout-subtitle">Payment Method</h3>
                            <div class="addresses">
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-radio">
                                        <input id="radio10" checked="checked" name="payment" value="paypal" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">
                                                <img src="{{ asset('images/img-payment-paypal.png') }}" alt="">
                                            </span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="radio32" name="payment" value="stripe" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">
                                                <img width="335px" src="{{ asset('images/img-payment-stripe.png') }}" alt="">
                                            </span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="radio31" name="payment" value="dbs" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">
                                                <img src="{{ asset('images/img-payment-dbs.png') }}" alt="">
                                            </span>
                                    </label>
                                    <label class="custom-control custom-radio">
                                        <input id="radio51" name="payment" value="other" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Other</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-total">
                            <div class="checkout-actions">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                        <div class="cart-last">
                                            <span>Total <small>(Excl. Shipping)</small></span>
                                            <strong>{{ number_format($total, 2)}} <small>{{ $siteCurrency }}</small></strong>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-8 col-lg-8">
                                        <a href="" id="submit-form" class="btn btn-primary btn-block"><i class="fa fa-credit-card"></i>Confirm and pay</a>
                                    </div>
                                </div>
                            </div>
                            <div class="privacy">
                                <p>Shipping charge of approx SGD {{ number_format($shippingTotal, 2) }} will be charged by SingPost</p>
                                By clicking to continue you agree to the <a href="#" class="btn btn-outline-primary">Privacy Policy</a>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if($backParams['error'])
    <script>
        $(document).ready(function() {
            $('#radio2').prop('checked',true);
            $('.addresses-create').addClass('addresses-create--active');
            @foreach($backParams as $k => $v)
                @if(empty($v))
                $('.addresses-create').find('input[name="shipping[{{ $k }}]"]').css('border','1px solid red');
                @else
                $('.addresses-create').find('input[name="shipping[{{ $k }}]"]').val('{{ $backParams[$k] }}');
                @endif
            @endforeach
        });
    </script>
@endif
@endsection
@include('checkout._handler')