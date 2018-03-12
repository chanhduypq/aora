<div class="item-conf">
    <div class="item-prop">
        <div class="item-label">{{ $product->getVariationsTitle() }}</div>
        <div class="btn-groups" data-toggle="buttons">
            @foreach($product->variations as $id => $variation)
                @include('partials.variation', ['check' => $loop->first])
            @endforeach
        </div>
    </div>
</div>