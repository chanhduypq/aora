@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Emails</li>
    </ol>
    <h1 class="page-header">Emails <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form method="get" action="{{ route('admin.emails') }}" id="form">
                        <input type="hidden" id="form_sort" name="sort">
                        <div class="col-md-2">
                            <label style="line-height: 28px;">Code</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="code" value="{{ request('code') }}">
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

                    @if(!$emails->isEmpty())
                        <?php $helper = (new App\Classes\Helpers); ?>
                        <table id="data-table" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                            <tr>
                                <th>Email ID {{ $helper->admin_table_sortable('id') }}</th>
                                <th>Code {{ $helper->admin_table_sortable('code') }}</th>
                                <th>Status {{ $helper->admin_table_sortable('status') }}</th>
                                <th>Created {{ $helper->admin_table_sortable('created_at') }}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($emails as $email)
                                <tr id="email_{{ $email->id }}" data-id="{{ $email->id }}">
                                    <td>#{{ $email->id }}</td>
                                    <td>{{ $email->code }}</td>
                                    <td><strong>{{ $email->showStatus() }}</strong></td>
                                    <td>{{ $email->created_at->format('j M, Y') }}</td>
                                    <td class="dl-ico-action">
                                        <a href="{{ route('admin.emails.edit', array('id' => $email->id)) }}"><i class="fa fa-pencil-square-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div style="width: 100%;text-align: right">
                            {!! $emails->appends(['sort' => request('sort'),'limit' => request('limit'),'status' => request('status'),'code' => request('code')])->links() !!}
                        </div>
                    @else
                        <strong>Emails not found</strong>
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
    </script>
@endsection
