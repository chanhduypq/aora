@extends('layouts.app')
@section('content')
<div class="page search">
<div class="container">
    <div class="page-body">
        <h2 class="page-title">Track</h2>
        <div class="track-body">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="track">
                        <i class="track-pic track-pic--active track-pic--stock">
                            <i></i>
                        </i>
                        <h4 class="track-title">Order in stock</h4>
                        <div class="track-status">
                            <div class="track-status-item track-status-item--active">
                                <i class="fa fa-check-circle-o"></i>Waiting for the fence by the carrier<br>{{ date('j M, Y H:i', strtotime($order->created_at)) }} [GMT + 8]
                            </div>
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('1 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Order accepted by the delivery service<br>{{ date('j M, Y H:i', strtotime($order->created_at)+3500) }} [GMT + 8]
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="track">
                        <i class="track-pic track-pic--active track-pic--leaving">
                            <i></i>
                        </i>
                        <h4 class="track-title">Leaving the USA</h4>
                        <div class="track-status">
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('12 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Adopted by air carrier<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 43900) }} [GMT + 8]
                            </div>
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('48 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Leave the country of departure<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 171800) }} [GMT + 8]
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="track">
                        <i class="track-pic track-pic--arrived">
                            <i></i>
                        </i>
                        <h4 class="track-title">Arrived in Singapore</h4>
                        <div class="track-status">
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('72 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Arrived in the country of destination<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 259450) }} [GMT + 8]
                            </div>
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('78 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Issued by customs<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 280590) }} [GMT + 8]
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="track">
                        <i class="track-pic track-pic--expect">
                            <i></i>
                        </i>
                        <h4 class="track-title">Expect shipping</h4>
                        <div class="track-status">
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('90 hours ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>The order was accepted by the local department of the delivery service<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 324970) }} [GMT + 8]
                            </div>
                            <div class="track-status-item @if (strtotime($order->created_at) < strtotime('120 hour ago')) track-status-item--active @endif">
                                <i class="fa fa-check-circle-o"></i>Delivered<br>{{ date('j M, Y H:i', strtotime($order->created_at) + 432850) }} [GMT + 8]
                            </div>
                        </div>
                    </div>                                
                </div>
            </div>
        </div>
        <div class="track-actions text-center">
            <a href="{{ route('orders') }}" class="btn btn-primary"><i class="fa fa-check"></i>Ok</a>
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