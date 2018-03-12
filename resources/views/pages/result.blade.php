@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://images-na.ssl-images-amazon.com/images/I/61P4yv5CcRL._RC|01evdoiemkL.css,01K+Ps1DeEL.css,31yErFkQitL.css,01kivkxD60L.css,11UGC+GXOPL.css,21LK7jaicML.css,11L58Qpo0GL.css,21EuGTxgpoL.css,01Xl9KigtzL.css,21IJTTf5-5L.css,019SHZnt8RL.css,01qy9K8SDEL.css,11vZhCgAHbL.css,21uiGhnhrlL.css,11WgRxUdJRL.css,01dU8+SPlFL.css,11ocrgKoE-L.css,01SHjPML6tL.css,111-D2qRjiL.css,01QrWuRrZ-L.css,31jVVYSnroL.css,114KWZGKCVL.css,01Alnvtt1zL.css,01oZl+VEzRL.css_.css?AUIClients/AmazonUI#us.not-trident" />
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
                    <div class="item d-md-flex" style="margin-top: 30px;">
                        <div id="cm-cr-dp-review-list" class="a-section review-views celwidget" data-hook="top-customer-reviews-widget">
                            @foreach ($comments as $comment) 
                            <div class="a-section review">
                                <div class="a-section celwidget">
                                    <div class="a-row a-spacing-mini">
                                        <div aria-hidden="true" class="a-profile-avatar-wrapper">
                                            <div class="a-profile-avatar">
                                                <img src="{{ $comment['avatar'] }}" class="" data-src="{{ $comment['avatar'] }}">
                                            </div>
                                        </div>
                                        <div class="a-profile-content"><span class="a-profile-name">{{ $comment['name'] }}</span></div>

                                    </div>
                                    <div class="a-row">
                                        <a class="a-link-normal" title="{{ $comment['rating_title'] }}" href="{{ $comment['rating_href'] }}">
                                            <i data-hook="review-star-rating" class="{{ $comment['rating_i_class'] }}"><span class="a-icon-alt">{{ $comment['rating_i_html'] }}</span></i>
                                        </a>
                                        <span class="a-letter-space"></span>
                                        <a data-hook="review-title" class="a-size-base a-link-normal review-title a-color-base a-text-bold" href="{{ $comment['title_href'] }}">{{ $comment['title_html'] }}</a>
                                    </div>
                                    <span data-hook="review-date" class="a-size-base a-color-secondary review-date">{{ $comment['time'] }}</span>
                                    <div class="a-row a-spacing-mini review-data review-format-strip">
                                        <span data-hook="format-strip-linkless" class="a-color-secondary">
                                            {{ $comment['string1'] }}<i class="a-icon a-icon-text-separator" aria-label="|"><span class="a-icon-alt">|</span></i>{{ $comment['string2'] }}
                                        </span>
                                    </div>
                                    <div class="a-row review-data">
                                        <span data-hook="review-body" class="a-size-base review-text">
                                            <div aria-live="polite" data-a-expander-name="review_text_read_more" data-a-expander-collapsed-height="300" class="a-expander-collapsed-height a-row a-expander-container a-spacing-base a-expander-partial-collapse-container" style="max-height:300px; _height:300px">
                                                <div data-hook="review-collapsed" aria-expanded="false" class="a-expander-content a-expander-partial-collapse-content">
                                                    {{ $comment['content'] }}
                                                </div>
                                                <div class="a-expander-header a-expander-partial-collapse-header" style="opacity: 0; display: none;"><div class="a-expander-content-fade"></div><a href="javascript:void(0)" data-hook="expand-collapse-read-more-less" data-action="a-expander-toggle" class="a-declarative" data-a-expander-toggle="{&quot;allowLinkDefault&quot;:true, &quot;expand_prompt&quot;:&quot;Read more&quot;, &quot;collapse_prompt&quot;:&quot;Read less&quot;}"><i class="a-icon a-icon-extender-expand"></i><span class="a-expander-prompt">Read more</span></a></div>
                                            </div>                                        
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                        <p>Nothing find</p>
                    @endif
                </div>
            </div>
        </div>
@endsection
@include('pages._handler')