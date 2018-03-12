@if (!empty($shipping))
    @foreach($shipping as $k => $v)
        <label class="custom-control custom-radio">
            <input required id="ship{{ $v->id }}" name="shipping_method" type="radio" class="custom-control-input" value="{{ $v->id }}" data-rate="{{ $rate }}" data-name="{{ strtolower($v->name) }}" data-price="{{ number_format($v->base_charge, 2) }}" data-add-price="{{ number_format($v->getAddPrice(), 2) }}" @if ($k == 0) checked @endif>
            <span class="custom-control-indicator"></span>
            <span for="ship{{ $v->id }}" class="custom-control-description">
                {{ $v->name }} <span class="shipping_rate"><i class="fa fa-spinner" aria-hidden="true"></i></span> <small>{{ $siteCurrency }}</small>
                <b style="margin-bottom: 10px;">{{ number_format($v->base_charge, 2) }} + per 100g: <span>{{ number_format($v->getAddPrice(), 2) }} {{ $siteCurrency }}</span></b>
                <b>Delivery In: {{ $v->duration }} Working days</b>
            </span>
        </label>
    @endforeach
@endif