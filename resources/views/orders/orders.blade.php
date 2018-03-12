@extends('layouts.app')
@section('content')
    <div class="page search">
        <div class="container">
            <div class="search-body">
                <h2 class="search-title">My Orders</h2>
                @if(!$orders->isEmpty())
                <div class="order-list">
                @foreach($orders as $order)
                    <div class="order" data-order-id="{{ $order->id }}">
                        <div class="order-date">
                            {{ $order->created_at->format('j M, Y') }}
                        </div>
                        <div class="order-table">
                        @foreach($order->products as $item)
                            <div class="order-row">
                                <div class="order-cell order-cell--title">{{ $item->title }}</div>
                                <div class="order-cell order-cell--ctn">x{{ $item->quantity }}</div>
                                <div class="order-cell order-cell--price">{{ number_format($item->getTotalPrice() * $order->rate, 2) }} {{ $siteCurrency }}</div>
                            </div>
                        @endforeach
                        </div>
                        <div class="order-total">
                            <div class="order-status">
                                <span class="order-status__title">Status: {{ $order->showStatus() }}</span>
                                @if($order->status == \App\Order::ORDER_UNPAID)
                                    <a href="{{ route('orders.repay', ['order'=>$order->id]) }}" class="btn btn-outline-primary">Pay</a>
                                @else
                                    @if($order->status != \App\Order::ORDER_COMPLETE)
                                        &nbsp;<a href="{{ route('orders.track', ['order'=>$order->id]) }}" class="btn btn-outline-primary">Detailed Status</a>
                                    @endif
                                @endif
                            </div>
                            <div style="float:right">
                                <span class="total-label">Discount</span>
                                <span class="total-value" style="margin-right: 30px;color:#2a2a2a;width: 110px;display: inline-block;">{{ number_format($order->discount, 2) }} <small>{{ $siteCurrency }}</small></span>
                                <div style="clear: both;height:5px;"></div>
                                <div class="total">
                                    <span class="total-label">Total price</span>
                                    <strong class="total-value" style="width: 110px;display: inline-block;">{{ number_format($order->getFullTotalPrice(), 2) }} <small>{{ $siteCurrency }}</small></strong>
                                </div> 
                                <div style="clear: both;height:0"></div>                               
                                <span class="total-label">Estimated Shipping</span>
                                <span class="total-value" style="margin-right: 30px;color:#2a2a2a;width: 110px;display: inline-block;">{{ number_format($order->shipping_total, 2) }} <small>{{ $siteCurrency }}</small></span>
                                <div style="clear: both;height:0"></div>
                            </div>
                        </div>
                    </div><!-- /.order -->
                @endforeach
                </div>
                @else
                    <strong>Orders not found</strong>
                @endif
            </div>
        </div>
    </div>
@endsection