@extends('layouts.app')
@section('content')
<div class="page">
<div class="container">
    <div class="success">
        <i class="success-ico"></i>
        <h2 class="search-title">Payment was canceled</h2>
        <div class="success-ok">
            <a href="{{ route('orders') }}" class="btn btn-primary px-3"><i class="fa fa-check"></i>Ok</a>
        </div>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $('.qtd_shp').on('change', function() {
            var data = $(this).data();

            $('#delivery_date').val(data.date);
            $('#delivery_price').val(data.price);
            $('#delivery_name').val(data.name);
        });
    });
</script>
@endpush