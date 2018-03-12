@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.marketplaces') }}">Market places list</a></li>
        <li class="active">{{ empty($marketplace->id) ? 'Add new' : 'Edit market place' }}</li>
    </ol>
    <h1 class="page-header">{{ empty($marketplace->id) ? 'Add new' : 'Edit '.$marketplace->name.' '.$marketplace->country->name }} <small></small></h1>
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
                                <input name="name" value="{{ $marketplace->name }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Country</label>
                            </div>
                            <div class="col-md-9">
                                <select name="country_id" class="form-control" style="height:34px">
                                    <?php foreach ($countries as $country) {
                                        if($country->id == $marketplace->country_id)
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
                                <input type="submit" class="btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection