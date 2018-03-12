@foreach($options as $k => $option)
    @if(is_numeric($k))
        <label data-type="{{ $id }}" data-name="{{ $option }}" class="btn btn-outline-secondary product-variant @if(!empty($product->currentVariant[$id]) && $product->currentVariant[$id] == $option){{'active'}}@endif">
            <input value="{{ $option }}" name="variant[]" type="radio" autocomplete="off">{{ $option }}
        </label>
    @endif
@endforeach