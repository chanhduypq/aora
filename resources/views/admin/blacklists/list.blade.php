@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Black list</li>
    </ol>
    <h1 class="page-header">Black list <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form method="get" action="{{ route('admin.blacklist') }}" id="form">
                        <input type="hidden" id="form_sort" name="sort">
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="name" value="{{ request('name') }}">
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Node</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="node" value="{{ request('node') }}">
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Type</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="type">
                                <option value="">All</option>
                                @foreach($types as $id => $name)
                                    <option value="{{ $id }}" @if(!is_null(request('type')) && request('type') == $id){{'selected'}}@endif>{{ $name }}</option>
                                @endforeach
                            </select>
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

                    @if(!$blacklists->isEmpty())
                        <?php $helper = (new App\Classes\Helpers); ?>
                        <table id="data-table" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Blacklist ID {{ $helper->admin_table_sortable('id') }}</th>
                                <th>Name {{ $helper->admin_table_sortable('name') }}</th>
                                <th>Node {{ $helper->admin_table_sortable('node') }}</th>
                                <th>Type {{ $helper->admin_table_sortable('type') }}</th>
                                <th>Market {{ $helper->admin_table_sortable('market_id') }}</th>
                                <th>...To Country {{ $helper->admin_table_sortable('country_id') }}</th>
                                <th>Status {{ $helper->admin_table_sortable('status') }}</th>
                                <th>Created {{ $helper->admin_table_sortable('created_at') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($blacklists as $blacklist)
                                <tr id="blacklist_{{ $blacklist->id }}" data-id="{{ $blacklist->id }}">
                                    <td>#{{ $blacklist->id }}</td>
                                    <td class="dl-js-name">{{ $blacklist->name }}</td>
                                    <td>{{ $blacklist->node }}</td>
                                    <td>{{ trans('blacklist.type.'.$blacklist->type) }}</td>
                                    <td>{{ $blacklist->market->name }} {{ $blacklist->market->country->name }}</td>
                                    <td>{{ $blacklist->country->name }}</td>
                                    <td><strong>{{ trans('blacklist.status.'.$blacklist->status) }}</strong></td>
                                    <td>{{ $blacklist->created_at->format('j M, Y') }}</td>
                                    <td class="dl-ico-action">
                                        <a style="margin-right: 5px;" href="{{ route('admin.blacklist.edit', ['id' => $blacklist->id]) }}"><i class="fa fa-pencil-square-o"></i></a>
                                        <a style="cursor: pointer" onclick="delete_blacklist({{ $blacklist->id }})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div style="width: 100%;text-align: right">
                            {!! $blacklists->appends(['sort' => request('sort'),'limit' => request('limit'),'status' => request('status'),'type' => request('type'),'name' => request('name')])->links() !!}
                        </div>
                    @else
                        <strong>Black list not found</strong>
                    @endif
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
        function delete_blacklist(blacklist_id) {
            var name = $.trim($('#blacklist_'+blacklist_id+' .dl-js-name').html());
            if(!confirm('Do you want to delete blacklist "'+name+'" ?')) {
                return true;
            }
            var url = "{{ route('admin.blacklist.delete', ['id' => 0]) }}";
            url = url.replace('/0/','/'+blacklist_id+'/');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'post',
                data: {},
                dataType: 'json',
                success: function(result){
                    if(result.success) {
                        window.location.reload();
                    } else {
                        alert(result.message);
                    }
                }
            });
        }
    </script>
@endsection