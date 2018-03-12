@extends('layouts.admin')
@section('content')
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.emails') }}">Emails list</a></li>
        <li class="active">{{ empty($email->id) ? 'Add new' : 'Edit email' }}</li>
    </ol>
    <h1 class="page-header">{{ empty($email->id) ? 'Add new' : 'Edit '.$email->code }} <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <form class="dl_admin_form" id="email_form" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <label>Code</label>
                            </div>
                            <div class="col-md-9">
                                <input name="code" value="{{ $email->code }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Subject</label>
                            </div>
                            <div class="col-md-9">
                                <input name="subject" value="{{ $email->subject }}" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Content</label>
                            </div>
                            <div class="col-md-9">
                                <textarea style="height:200px" class="form-control" name="content">{{ $email->content }}</textarea>
                                <div style="clear:both;width:100%;margin-top:10px">
                                Keywords list:
                                @foreach($keys as $key)
                                    <div style="clear:both;height:5px"></div>
                                    <div class="col-md-4">
                                        {{ $key->keyword }}
                                    </div>
                                    <div class="col-md-8">
                                        {{ nl2br($key->description) }}
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-9">
                                <label for="check_status"><input name="status" value="1" id="check_status" type="checkbox" @if(!empty($email->status) && $email->status==1){{'checked'}}@endif>&nbsp; Active</label>
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