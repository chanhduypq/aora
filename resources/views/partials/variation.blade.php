@if(!empty($variation))
    <label class="btn btn-outline-secondary product-variant">
        <input data-name="{{ implode(' + ', $variation) }}" value="{{ $id }}" name="variant" type="radio" autocomplete="off">{{ implode(' + ', $variation) }}
    </label>
@endif