@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Market places</li>
    </ol>
    <h1 class="page-header">Market places <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form method="get" action="{{ route('admin.marketplaces') }}" id="form">
                        <input type="hidden" id="form_sort" name="sort">
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="name" value="{{ request('name') }}">
                        </div>
                        <div style="clear:both; height:10px"></div>
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Country</label>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="country_id">
                                <option value="">All</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @if(!is_null(request('country_id')) && request('country_id') == $country->id){{'selected'}}@endif>{{ $country->name }}</option>
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

                    @if(!$marketplaces->isEmpty())
                        <?php $helper = (new App\Classes\Helpers); ?>
                        <table id="data-table" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Market ID {{ $helper->admin_table_sortable('id') }}</th>
                                <th>Name {{ $helper->admin_table_sortable('name') }}</th>
                                <th>Country {{ $helper->admin_table_sortable('country_id') }}</th>
                                <th>Created {{ $helper->admin_table_sortable('created_at') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($marketplaces as $marketplace)
                                <tr id="market_{{ $marketplace->id }}">
                                    <td>#{{ $marketplace->id }}</td>
                                    <td class="dl-js-name">{{ $marketplace->name }}</td>
                                    <td class="dl-js-country-name">{{ $marketplace->country->name }}</td>
                                    <td>{{ $marketplace->created_at->format('j M, Y') }}</td>
                                    <td class="dl-ico-action">
                                        <a href="{{ route('admin.marketplaces.edit', array('id' => $marketplace->id)) }}"><i class="fa fa-pencil-square-o"></i></a>
                                        <a style="cursor: pointer" onclick="delete_market({{ $marketplace->id }})"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div style="width: 100%;text-align: right">
                            {!! $marketplaces->appends(['sort' => request('sort'),'limit' => request('limit'),'country_id' => request('country_id'),'name' => request('name')])->links() !!}
                        </div>
                    @else
                        <strong>Market places not found</strong>
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
        function delete_market(market_id) {
            var name = $.trim($('#market_'+market_id+' .dl-js-name').html());
            name += ' '+$.trim($('#market_'+market_id+' .dl-js-country-name').html());
            if(!confirm('Do you want to delete market "'+name+'" ?')) {
                return true;
            }
            var url = "{{ route('admin.marketplaces.delete', ['id' => 0]) }}";
            url = url.replace('/0/','/'+market_id+'/');
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