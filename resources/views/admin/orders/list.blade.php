@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Orders</li>
    </ol>
    <h1 class="page-header">Orders <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form method="get" action="{{ route('admin.orders') }}" id="form">
                        <input type="hidden" id="form_sort" name="sort">
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Customer name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="name" value="{{ request('name') }}">
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Product name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="q" value="{{ request('q') }}">
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Status</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="status">
                                <option value="">All</option>
                                @foreach($statuses as $id => $name)
                                    <option value="{{ $id }}" @if(!is_null(request('status')) && request('status') == $id){{'selected'}}@endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Marketplace</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="marketplace">
                                <option value="">All</option>
                                @foreach($marketplaces as $marketplace)
                                    <option value="{{ $marketplace->id }}" @if(request('marketplace') == $marketplace->id){{'selected'}}@endif>{{ $marketplace->name }} {{ $marketplace->country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Show entries</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="limit">
                                <option value="10">10</option>
                                <option value="50" @if(request('limit') == 50){{'selected'}}@endif>50</option>
                                <option value="100" @if(request('limit') == 100){{'selected'}}@endif>100</option>
                            </select>
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2"></div>
                        <div class="col-md-4">
                            <input type="submit" class="btn btn-primary" value="Search">
                        </div>
                        <div style="clear:both; height:10px"></div>

                    </form>

                    @if(!$orders->isEmpty())
                    <?php $helper = (new App\Classes\Helpers); ?>
                        <table id="data-table" class="table table-striped table-bordered" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Order ID {{ $helper->admin_table_sortable('id') }}</th>
                            <th>Payment type {{ $helper->admin_table_sortable('payment_type') }}</th>
                            <th>Status {{ $helper->admin_table_sortable('status') }}</th>
                            <th>User {{ $helper->admin_table_sortable('user_id') }}</th>
                            <th>Created {{ $helper->admin_table_sortable('created_at') }}</th>
                            <th>Delivery Service</th>
                            <th>Arrive</th>
                            <th>…To User</th>
                            <th>Cost {{ $helper->admin_table_sortable('cost') }}</th>
                            <th>Paypal Status / ID</th>
                            <th>Marketplace {{ $helper->admin_table_sortable('marketplace_id') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                        <tr id="order_{{ $order->id }}" data-id="{{ $order->id }}" data-stripe-transaction-id="{{ $order->stripe_transaction_id ? $order->stripe_transaction_id : '' }}">
                            <td>
                                #{{ $order->id }} <br>
                                <div class="text-muted d-block">{{ $order->products->count() }} items</div>
                            </td>
                            <td>{{ $order->payment_type }}</td>
                            <td>
                                <a id="btn-change-status-{{ $order->id }}" title="Click to change status" style="cursor: pointer;color: #337ab7;" onclick="change_status({{ $order->id }},{{ $order->status }})"><strong>{{ $order->showStatus() }}</strong></a>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('j M, Y') }}</td>
                            <td>{{ $order->shippingMethod->name }}</td>
                            <td>{{ $order->shippingMethod->duration }} days</td>
                            <td><?php
                                $duration_arr = explode('-', $order->shippingMethod->duration);
                                echo date('j M, Y', strtotime($order->created_at. ' + '.trim($duration_arr[0]).' days'));
                                if(!empty($duration_arr[1])) {
                                    echo ' - '.date('j M, Y', strtotime($order->created_at. ' + '.trim($duration_arr[1]).' days'));
                                } ?>
                            </td>
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
                    <div style="width: 100%;text-align: right">
                        {!! $orders->appends(['sort' => request('sort'),'limit' => request('limit'),'status' => request('status'),'marketplace' => request('marketplace'),'q' => request('q'),'name' => request('name')])->links() !!}
                    </div>
                    @else
                        <strong>Orders not found</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="status_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button style="margin-top: -10px;" type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="font-size: 25px;">Update status</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="input_order_id">
                    <select id="input_status" class="form-control" style="height: auto">
                        @foreach($statuses as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button style="padding: 10px 20px;" onclick="action_change_status()" type="button" class="btn btn-default">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#data-table').DataTable({
                scrollX: true,
                info: false,
                paging: false,
                searching: false,
                ordering: false
            });
            $('.btn_refund').on('click', function(){
                if(confirm('You want to create refund request ?')) {
                    var orderId = $(this).closest('.admin-tr').data('id'),
                        stripe_transaction_id = $(this).closest('.admin-tr').data('stripe-transaction-id');

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('admin.orders.createRefund') }}",
                        type: 'post',
                        data: {
                            id: orderId,
                            stripe_transaction_id: stripe_transaction_id
                        },
                        dataType: 'json',
                        success: function(result){
                            if(result.status == 'success') {
                                location.reload();
                            }
                            else {
                                alert('Something went wrong');
                            }
                        }
                    });
                }
            });
            $('.dl-js-sortable').click(function() {
                var new_sort_value = $(this).data('sort');
                if(typeof(new_sort_value) == 'undefined' || new_sort_value == '') {
                    return false;
                }
                var current_sort = '{{ request('sort') }}';
                if(current_sort == '') {
                    current_sort = 'created_at_desc';
                }
                var current_sort_parser = current_sort.split('_');
                var current_sort_key = '';
                for(var i=0;i<current_sort_parser.length - 1;i++) {
                    current_sort_key += current_sort_parser[i]+'_';
                }
                if(new_sort_value+'_' == current_sort_key) {
                    if(current_sort_parser[current_sort_parser.length - 1] == 'asc') {
                        new_sort_value += '_desc';
                    } else {
                        new_sort_value += '_asc';
                    }
                } else {
                    new_sort_value += '_asc';
                }
                $('#form_sort').val(new_sort_value);
                document.getElementById('form').submit();
            });
        });
        function change_status(order_id, order_status) {
            $('#input_order_id').val(order_id);
            $('#input_status').val(order_status);
            $('#status_modal').modal();
        }
        function action_change_status() {
            var order_id = $('#input_order_id').val();
            var status = $('#input_status').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.orders.updateStatus') }}",
                type: 'post',
                data: {
                    id: order_id,
                    status: status
                },
                dataType: 'json',
                success: function(result){
                    if(result.success) {
                        var status_name = $('#input_status').find('option[value="' + status + '"]').text();
                        $("#order_" + order_id).find('a strong').text(status_name);
                        $('#status_modal').find('.close').click();
                        $('#btn-change-status-'+order_id).attr('onclick','change_status('+order_id+', '+status+')');
                    }
                }
            });
        }
    </script>
@endsection