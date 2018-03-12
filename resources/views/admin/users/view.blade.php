@extends('layouts.admin')
@section('content')
    <style>
        label.dl_item_info {width: 80px;}
        #data-table_paginate, #data-table_filter {text-align: right;}
    </style>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.users') }}">Users list</a></li>
        <li class="active">User detail</li>
    </ol>
    <h1 class="page-header">{{ $user->name }} <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <div class="col-md-3" style="text-align: center;padding: 25px 20px">
                        <img width="100%" src="{{ $user->get_avatar() }}" style="max-width: 200px;">
                    </div>
                <div class="col-md-9">
                    <div style="padding: 0 20px">
                        <h3>Information <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"><i class="fa fa-pencil-square-o"></i></a></h3>
                        <div>
                            <label class="dl_item_info">User Id</label>: #{{ $user->id }}
                        </div>
                        <div>
                            <label class="dl_item_info">Name</label>: {{ $user->name }}
                        </div>
                        <div>
                            <label class="dl_item_info">Email</label>: {{ $user->email }}
                        </div>
                        <div>
                            <label class="dl_item_info">Phone</label>: {{ $user->phone }}
                        </div>
                        <div>
                            <label class="dl_item_info">Address</label>: {{ $user->address }}
                        </div>
                        <div>
                            <label class="dl_item_info">&nbsp;</label>&nbsp; {{ ($user->is_admin) ? 'Is admin' : 'Customer' }}
                        </div>
                        <div>
                            <label class="dl_item_info">&nbsp;</label>&nbsp; {{ trans('users.status.'.$user->status) }}
                        </div>
                        <h3>Order histories</h3>
                        <div>
                            @if(count($user->orders) > 0)
                                <table id="data-table" class="table table-striped table-bordered" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Payment type</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Delivery Service</th>
                                        <th>Cost</th>
                                        <th>Paypal Status / ID</th>
                                        <th>Marketplace</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->orders as $order)
                                        <tr id="order_{{ $order->id }}" data-id="{{ $order->id }}" data-stripe-transaction-id="{{ $order->stripe_transaction_id ? $order->stripe_transaction_id : '' }}">
                                            <td>
                                                #{{ $order->id }} <br>
                                                <div class="text-muted d-block">{{ $order->products->count() }} items</div>
                                            </td>
                                            <td>{{ $order->payment_type }}</td>
                                            <td>
                                                <a id="btn-change-status-{{ $order->id }}" title="Click to change status" style="cursor: pointer;color: #337ab7;" onclick="change_status({{ $order->id }},{{ $order->status }})"><strong>{{ $order->showStatus() }}</strong></a>
                                            </td>
                                            <td>{{ $order->created_at->format('j M, Y') }}</td>
                                            <td>{{ $order->shippingMethod->name }}</td>
                                            <td>{{ number_format($order->getFullTotalPrice(),2) }} {{ $siteCurrency }}</td>
                                            <td>
                                                <div class="admin-status d-block">
                                                    @if($order->paypal_status)
                                                        <span class="admin-status--created">{{ $order->paypal_status }}</span> Auto
                                                    @else
                                                        Manual
                                                    @endif
                                                </div>
                                                <small class="admin-id">{{ $order->paypal_id }}</small>
                                            </td>
                                            <td>{{ $order->marketplace->name }} {{ $order->marketplace->country->name }}</td>
                                            <td>
                                                @if($order->payment_type == 'stripe' && $order->status != \App\Order::ORDER_REFUND && $order->status != \App\Order::ORDER_UNPAID)
                                                    <input type="button" class="btn-warning btn_refund" value="Refund">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <strong>No Order</strong>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            if($('#data-table').length > 0) {
                $('#data-table').DataTable({
                    scrollX: true,
                    info: true,
                    paging: true,
                    searching: true,
                    ordering: true
                });
            }
        });
    </script>
@endsection