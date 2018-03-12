@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.blacklist') }}">Black list</a></li>
        <li class="active">{{ empty($blacklist->id) ? 'Add new' : 'Edit blacklist' }}</li>
    </ol>
    <h1 class="page-header">{{ empty($blacklist->id) ? 'Add new' : 'Edit '.$blacklist->name }} <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form class="dl_admin_form" id="market_form" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <label>Name</label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" value="{{ $blacklist->name }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Node</label>
                            </div>
                            <div class="col-md-9">
                                <input name="node" value="{{ $blacklist->node }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Type</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" name="type">
                                    @foreach($types as $id => $name)
                                        <option value="{{ $id }}" {{ $blacklist->type==$id ? 'selected' :'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Market</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" name="market_id" style="height: 34px">
                                    <?php foreach ($markets as $market) {
                                        if($blacklist->market_id == $market->id)
                                            echo '<option value="'.$market->id.'" selected>'.$market->name.' '.$market->country->name.'</option>';
                                        else
                                            echo '<option value="'.$market->id.'">'.$market->name.' '.$market->country->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label>To Country</label>
                            </div>
                            <div class="col-md-9">
                                <select name="country_id" class="form-control" style="height:34px">
                                    <?php foreach ($countries as $country) {
                                        if($country->id == $blacklist->country_id)
                                            echo '<option value="'.$country->id.'" selected>'.$country->name.'</option>';
                                        else
                                            echo '<option value="'.$country->id.'">'.$country->name.'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <label for="check_status"><input name="status" value="1" id="check_status" type="checkbox" @if(!empty($blacklist->status) && $blacklist->status==1){{'checked'}}@endif>&nbsp; Active</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection