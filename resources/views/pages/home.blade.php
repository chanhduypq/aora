@extends('layouts.app')
@section('content')

    <div class="promo promo--upd">
        <div class="container">
            <h3 class="promo-title">Multiple Marketplaces,<br class="d-block d-md-none">On a Single Shopping Cart</h3>
            <form class="form form--promo" id="myForm" method="post" action="{{ route('pages.result') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="cotrol-group-label" for="form-link">Your shopping cart has never looked better</label>
                    <div class="form-input">
                        <input required name="link" class="form-control" type="text" value="" placeholder="https://www.amazon.com/dp/B075QLRSP9">
                        <button type="submit" class="btn btn-primary btn--submit">Shop Now<i class="fa fa-angle-right"></i></button>
                    </div>
                    <span class="help-text"><i class="fa fa-lightbulb-o"></i>Insert a link to the product in the store and click on arrow.</span>
                </div>
            </form>
            <div class="promo-with">
                <span>Shop with us</span>
                <img src="{{ asset('images/img-shop-with.png') }}" alt="" class="promo-logos">
            </div>
        </div>
    </div>
    <div class="section section--how">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-2">
                    <h5 class="section-title">how<span>Aora</span>works?</h5>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="how-item">
                                <i class="how-ico how-ico--choose"></i>
                                <h4 class="how-title">Select Products</h4>
                                <p class="how-text">The world is your shopping cart - select your products, copy the URL and paste them here on AORA.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="how-item">
                                <i class="how-ico how-ico--pay"></i>
                                <h4 class="how-title">Make Payment</h4>
                                <p class="how-text">Put in your shipping address, and make a one-time payment with all the fees laid out on the table. There's no hidden fees when you shop with AORA. </p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">
                            <div class="how-item">
                                <i class="how-ico how-ico--wait"></i>
                                <h4 class="how-title">Sealed, Signed and Delivered</h4>
                                <p class="how-text">We'll update you with delivery information every step of the way, and you can check the status of your order anytime and anywhere via our website and app. It's a fuss-free shopping experience, at its very best. </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.section -->
    <div class="section section--features">
        <div class="container">
            <h2 class="section-heading"><span>Product Recommendation</span></h2>
            <div class="products">
                <div class="products-carousel js-carousel">
                    @foreach($productsRecommendation as $recommendation)
                    <div class="product">
                        <div class="product-thumb">
                            <img class="recommendation-img" src="{{ $recommendation->image }}" alt="">
                        </div>
                        <div class="product-provider">
                            <span class="product-from">Amazon</span>
                            <span class="product-rating">
                                @for ($i = 0; $i < $recommendation->rate; $i++)
                                    <i class="fa fa-star fa-star--active"></i>
                                @endfor
                                @for ($i = $recommendation->rate; $i < 5; $i++)
                                    <i class="fa fa-star fa-star"></i>
                                @endfor
                            </span>
                        </div>
                        <h4 class="product-title">
                            <a href="/result/{{ $recommendation->product_id }}" class="product-link">{{ $recommendation->title }}</a>
                        </h4>
                        <span class="product-price">${{ $recommendation->shop_price }} </span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="section-data">
                <h3 class="section-heading d-block d-sm-none"><span>Benefits</span></h3>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="benefit">
                                    <h4 class="benefit-title">Seamless Browsing Experience</h4>
                                    <div class="benefit-text">
                                        <p>No forms to fill out, or multiple pages to toggle between. Buy from multiple marketplaces and checkout from one cart instantly.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="benefit">
                                    <h4 class="benefit-title">Affiliates, Rebates and Coupons</h4>
                                    <div class="benefit-text">
                                        <p>We dig deep and look for all the best discounts and savings, and pass them onto you for a shopping cart that's lighter on the wallet. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="benefit">
                                    <h4 class="benefit-title">FX Savings</h4>
                                    <div class="benefit-text">
                                        <p>Skip the queue at the money changer. We convert the dollars at the best rates, and pass on the savings to you.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="benefit">
                                    <h4 class="benefit-title">Group Buying Value</h4>
                                    <div class="benefit-text">
                                        <p>With great purchasing power, comes great savings. 'The more, the merrier' isn't just a saying here at AORA - it's an entire business model.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="benefit">
                                    <h4 class="benefit-title">One-time Final Bill</h4>
                                    <div class="benefit-text">
                                        <p>Without any hidden charge at all - honesty really is the best policy. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                        <div class="features">
                            <div class="features-title">Features</div>
                            <div class="features-item"><i class="fa fa-check"></i>No need to toggle between multiple websites</div>
                            <div class="features-item"><i class="fa fa-check"></i>Fixed shipping prices</div>
                            <div class="features-item"><i class="fa fa-check"></i>Group buying value</div>
                            <div class="features-item"><i class="fa fa-check"></i>Enjoy rebates and affiliates</div>
                            <div class="features-item"><i class="fa fa-check"></i>Backed by your friendly national carrier</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section section--hear">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                    <h5 class="section-title">hear<br class="d-none d-sm-block">from our<span>aora</span><span>community</span></h5>
                </div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="blockquote">
                        <p>For those who just want to get shopping done fast-and-furious style without a sweat, I highly recommend AORA.</p>
                        <div class="blockquote-author">
                            Jonathan Black <img src="{{ asset('images/img-author.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="section--center">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="blockquote">
                            <p>Ever since I've become a mother and had to get all my baby products from US and UK, I've always used AORA to get my shopping done. It's straightforward, transparent, and saves me all the time and energy. </p>
                            <div class="blockquote-author">
                                Sharon Jackson<img src="{{ asset('images/img-author.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="blockquote">
                            <p>Whenever I buy stuff from Amazon, my wallet always suffers because of the unknown shipping charges. AORA changes the game for me.</p>
                            <div class="blockquote-author">
                                David Peterson<img src="{{ asset('images/img-author.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection