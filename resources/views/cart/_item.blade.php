<div class="cart-row" data-weight_max="{{ isset($item['weight_gram_max']) ? $item['weight_gram_max'] : $item['weight_gram'] }}" data-weight="{{ $item['weight_gram'] }}" data-price="{{ $item['shop_price'] }}" data-id="{{ $item['id'] }}">
    <div class="cart-cell cart-cell--del">
        <a href="#" class="cart-remove">
            <i class="fa fa-trash"></i>
        </a>
    </div>
    <div class="cart-cell cart-cell--photo">
        <a class="cart-img" href="{{ route('pages.result.byId', $item['id']) }}">
            <img src="{{ $item['image'] }}" style="max-width:130px; max-height:130px" alt="">
        </a>
    </div>
    <div class="cart-cell cart-cell--title">
        <div class="cart-title">
            <a class="cart-link" href="{{ route('pages.result.byId', $item['id']) }}">{{ $item['title'] }}</a>
            @if($item['variant_name'])
            <span class="cart-info">{{ $item['variant_name'] }}</span>
            @endif
        </div>
        <div class="cart-mobile">
            <div class="item-cnt">
                <span class="item-minus">-</span>
                <input type="number" min="1" class="form-control item-input input-number–noSpinners quantity-mobile" name="quantity" value="{{ $item['quantity'] }}">
                <span class="item-plus">+</span>
            </div>
            <div class="item-price">
                {{ number_format($item['shop_price'] * $rate, 2) }} {{ $siteCurrency }}<br><span class="item-price--small">{{ number_format($item['shop_price'], 2) }} {{ $shopCurrency }}</span>
            </div>
        </div>
    </div>
    <div class="cart-cell cart-cell--qt">
        <div class="item-cnt">
            <span class="item-minus">-</span>
            <input type="number" min="1" class="form-control item-input input-number–noSpinners quantity" name="quantity" value="{{ $item['quantity'] }}">
            <span class="item-plus">+</span>
        </div>
    </div>
    <div class="cart-cell cart-cell--price">
        <div class="item-price">
            {{ number_format($item['shop_price'] * $rate, 2) }} {{ $siteCurrency }}<br><span class="item-price--small">{{ number_format($item['shop_price'], 2) }} {{ $shopCurrency }}</span>
        </div>
    </div>
</div>