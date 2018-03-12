@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.users') }}">Users list</a></li>
        <li class="active">{{ empty($user->id) ? 'Add new' : 'Edit user' }}</li>
    </ol>
    <h1 class="page-header">{{ empty($user->id) ? 'Add new' : 'Edit '.$user->name }} <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form class="dl_admin_form" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <label>Name</label>
                            </div>
                            <div class="col-md-9">
                                <input name="name" value="{{ $user->name }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Email</label>
                            </div>
                            <div class="col-md-9">
                                <input name="email" value="{{ $user->email }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Avatar</label>
                            </div>
                            <div class="col-md-9">
                                <img src="{{ $user->get_avatar() }}" style="width: 100px;margin-bottom: 15px;">
                                <input type="file" name="avatar" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Phone</label>
                            </div>
                            <div class="col-md-9">
                                <input name="phone" value="{{ $user->phone }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Address</label>
                            </div>
                            <div class="col-md-9">
                                <input name="address" value="{{ $user->address }}" class="dl-js-address form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Postal code</label>
                            </div>
                            <div class="col-md-9">
                                <input name="postal_code" value="{{ $user->postal_code }}" class="postal-code form-control" type="text">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 7px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <label for="check_admin"><input name="is_admin" value="1" id="check_admin" type="checkbox" @if(!empty($user->is_admin) && $user->is_admin==1){{'checked'}}@endif>&nbsp; Is admin</label>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 7px;">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <label for="check_status"><input name="status" value="1" id="check_status" type="checkbox" @if(!empty($user->status) && $user->status==1){{'checked'}}@endif>&nbsp; Active</label>
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
@include('handlers.auto_address')