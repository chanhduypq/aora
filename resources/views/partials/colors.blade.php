@if(array_key_exists($option, current($product->variations)))
    <div class="item-conf">
        <div class="item-prop">
            <div class="item-label">{{ $option }}</div>
            <div class="btn-groups" data-toggle="buttons">
                @foreach($product->variations as $id => $variation)
                    @include('partials.variation', ['check' => $loop->first])
                @endforeach
            </div>
        </div>
    </div>
@endif