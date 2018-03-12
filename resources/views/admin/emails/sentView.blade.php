@extends('layouts.admin')
@section('content')
    <style>
        .default-form div {
            margin: 10px 0;
        }
        .default-form label {
            width: 200px;
            line-height: 28px;
            vertical-align: top;
        }
        .default-form .form-control {
            width: calc(100% - 220px);
            display: inline-block;
            background-color: #fff;
            color: #000000;
        }
    </style>
    <ol class="breadcrumb pull-right">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.emails.sent') }}">Emails sent list</a></li>
        <li class="active">Email sent detail</li>
    </ol>
    <h1 class="page-header">{{ $email->code }} to {{ $email->to_email }} <small></small></h1>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-body">
                    <div class="default-form" style="padding: 0 20px">
                        <div>
                            <label>Email Id</label>
                            <input readonly class="form-control" value="#{{ $email->id }}">
                        </div>
                        <div>
                            <label>User</label>
                            <input readonly class="form-control" value="{{ !empty($email->user) ? $email->user->name :'' }}">
                        </div>
                        <div>
                            <label>Email</label>
                            <input readonly class="form-control" value="{{ $email->to_email }}">
                        </div>
                        <div>
                            <label>Subject</label>
                            <input readonly class="form-control" value="{{ $email->subject }}">
                        </div>
                        <div>
                            <label>Content</label>
                            <textarea readonly style="height: 400px" class="form-control"><?php echo ($email->content); ?></textarea>
                        </div>
                        <div>
                            <label>Created</label>
                            <input readonly class="form-control" value="{{ $email->created_at->format('j M, Y H:i:s') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
        });
    </script>
@endsection