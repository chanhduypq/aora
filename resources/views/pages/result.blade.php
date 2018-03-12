@extends('layouts.app')
@section('content')
        <div class="page search page--padding-bottom">
            <div class="container">
                <div class="search-body">
                    <div class="page-head">
                        <h2 class="page-heading">Search Results</h2>
                    </div>
                    @if(!empty($product))
                    <div id="preloader" style="display: none;"></div>
                    <div class="item d-md-flex" data-rate="{{ $rate }}" data-product-id="{{ $product->id }}">
                        <div class="item-thumb">
                            <img src="{{ $product->image }}" alt="" style="max-width: 380px;">
                        </div>
                        <form class="item-data" id="result" action="{{ route('cart.add') }}" method="post">
                            <h3 class="item-title">{{ $product->title }}</h3>
                            <div class="item-dimension">
                                @if($product->dimensionsAsString)
                                    Product dimensions <span id="dimensions">{{ $product->dimensionsAsString }}</span> inches.
                                    @if($product->weight)
                                        Item weight <span id="weight">{{ $product->weight }}</span> pounds
                                    @endif
                                @endif
                            </div>
                            @if(!empty($product->variations))
                                @include('partials._variations')
                            @endif
                            <div class="item-price">
                                @if(!$product->support)
                                    <span>Sorry we do not support ebooks purchase</span>
                                @else
                                    @if(!$product->price || !$product->weight)
                                        <span>Sorry the product have been sold out</span>
                                    @else
                                        <span id="item-price-converted">{{ number_format($product->price * $rate, 2) }}</span> {{ $siteCurrency }}<span class="item-price--small"> / <span id="item-price-original">{{ number_format($product->price, 2) }}</span> {{ $shopCurrency }}</span>
                                    @endif
                                @endif
                            </div>
                            <div class="item-not-available" style="display:none">Sorry the product is not available</div>
                            @if($product->price && $product->weight)
                            <div class="item-order d-md-flex" style="z-index:99">
                                @if(!$product->in_blacklist)
                                <div class="item-cnt">
                                    <span class="item-minus">-</span>
                                    <input type="number" min="1" class="form-control item-input input-number–noSpinners quantity" name="quantity" value="1">
                                    <span class="item-plus">+</span>
                                </div>
                                @endif
                                <div class="item-btn">
                                    @if($product->in_blacklist)
                                    <button type="button" class="btn btn-warning">This product is denied</button>
                                    @else
                                    <button type="submit" class="btn btn-primary btn-order btn-block"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div id="error"></div>
                            <input type="hidden" name="variant_name" value="" id="product-variant"/>
                            <input type="hidden" name="id" value="{{ $product->id }}" id="product-id"/>
                            <input type="hidden" name="image" value="{{ $product->image }}" id="product-image"/>
                            <input type="hidden" name="title" value="{{ $product->title }}" id="product-title"/>
                            <input type="hidden" name="shop_price" value="{{ $product->price }}" id="product-price"/>
                            <input type="hidden" name="shipping_weight" value="{{ $product->weight }}" id="product-weight"/>
                            <input type="hidden" name="weight_gram" value="{{ $product->weightGram }}" id="product-weight-gram"/>
                            <input type="hidden" name="shipping_dimension" value="{{ $product->dimensionsAsString }}" id="product-dimensions"/>
                            {{ csrf_field() }}
                        </form>
                    </div>
                    @else
                        <p>Nothing find</p>
                    @endif
                </div>
            </div>
        </div>
@endsection
@include('pages._handler')