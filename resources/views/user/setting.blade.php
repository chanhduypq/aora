@extends('layouts.app')
@section('content')
<div class="page page--contact">
    <div class="container">
        <div class="page-body">
            <div class="page-head">
                <h2 class="page-heading">Settings</h2>
            </div>
            <div class="page-data">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="checkout-user checkout-user--shipping">
                            <div class="checkout settings address-block">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="">Your Name</label>
                                    <div class="col-sm-8">
                                        <input name="name" type="text" required disabled class="form-control-plaintext js-form-control" placeholder="Samanta Sanders" value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="">Phone</label>
                                    <div class="col-sm-8">
                                        <input name="phone" type="text" required disabled class="form-control-plaintext js-form-control" placeholder="+65 424 12 22" value="{{ $user->phone }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="">Postal code</label>
                                    <div class="col-sm-8">
                                        <input name="postal_code" type="text" required disabled class="postal-code form-control-plaintext js-form-control" placeholder="18001" value="{{ $user->postal_code }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" for="">Address</label>
                                    <div class="col-sm-8">
                                        <input name="address" type="text" required disabled class="address form-control-plaintext js-form-control" placeholder="USD, Portland" value="{{ $user->address }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$deliveries->isEmpty())
                        <div class="checkout-user">
                            <div class="checkout settings">
                                <div class="my-saved">
                                    <h4 class="checkout-subtitle">My Saved Delievery Addresses</h4>
                                    @foreach($deliveries as $item)
                                    <div class="saved-address">
                                        <a data-id="{{ $item->id }}" href="#" class="cart-remove js-remove-saved-address"><i class="fa fa-trash"></i></a>
                                        <div class="saved-data">
                                            {{ $item->phone }}<br>{{ $user->address }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="text-md-center">
                            <span class="d-sm-inline-block">
                                <div class="text-left">
                                    <a href="#" class="btn btn-link js-edit-settings">Edit Your Info</a>
                                </div>
                                <div class="text-left">
                                    <a href="#" class="btn btn-link" data-toggle="modal" data-target="#modal-change-email">Change Email</a>
                                </div>
                                <div class="text-left">
                                    <a href="#" class="btn btn-link" data-toggle="modal" data-target="#modal-change-password">Change Password</a>
                                </div>
                                <div class="settings-actions">
                                    <a href="#" class="btn btn-primary">Save</a>
                                    <a href="#" class="btn btn-link">Cancel</a>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal--transparent fade" id="modal-change-password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.change.pass') }}" method="post" class="form form--login">
                    <div class="form-group">
                        <!--<label for="">Enter Old Password <a href="#" class="form-forgot" data-toggle="modal" data-target="#modal-forgot">Forgot</a></label>-->
                        <label for="">Enter Old Password </label>
                        <input name="old_password" class="form-control" type="password" placeholder="Old Password" value="">
                    </div>
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input name="password" class="form-control" type="password" placeholder="New Password" value="">
                    </div>
                    <div class="form-group">
                        <label for="">Confirm New Password</label>
                        <input name="password_confirmation" class="form-control" type="password" placeholder="Confirm Password" value="">
                    </div>
                    <div class="form-group error"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <button type="submit" id="submit-change-pass" class="btn btn-double-border btn-primary btn-block">Change Password</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal modal--transparent fade" id="modal-change-email" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post" class="form form--login">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <!--<label for="">Your Password<a href="#" class="form-forgot" data-toggle="modal" data-target="#modal-forgot">Forgot</a></label>-->
                        <label for="">Your Password</label>
                        <input required name="password" class="form-control" type="password" placeholder="Password" value="">
                    </div>
                    <div class="form-group">
                        <label for="">New Email</label>
                        <input required name="email" class="form-control" type="email" placeholder="Email" value="">
                    </div>
                    <div class="form-group error"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <button id="submit-change-email" type="submit" class="btn btn-double-border btn-primary btn-block">Change Email</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#submit-change-pass').on('click', function(e) {
                e.preventDefault();

                var modal = $(this).closest('.modal');
                var inputs = $(this).closest('form').find('input').serialize();
                var error = $(this).closest('form').find('.form-group.error');

                error.html('');

                if(inputs.length) {
                    $.ajax({
                        url: '{{ route('user.change.pass') }}',
                        type: 'POST',
                        data: inputs,
                        dataType: 'json',
                        success: function (response) {
                            if(response.status == 'error') {
                                error.html(response.msg);
                            } else {
                                error.html('Success');
                                //modal.fadeOut();
                                //$('#modal-success2').fadeIn();
                                //$('#modal-success2').addClass('show');
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            error.html(response.responseJSON.message);
                        },
                        complete: function (response) {
                        }
                    });
                }

                return false;
            });

            $('#submit-change-email').on('click', function(e) {
                e.preventDefault();

                var modal = $(this).closest('.modal');
                var inputs = $(this).closest('form').find('input').serialize();
                var error = $(this).closest('form').find('.form-group.error');

                error.html('');

                if(inputs.length) {
                    $.ajax({
                        url: '{{ route('user.change.email') }}',
                        type: 'POST',
                        data: inputs,
                        dataType: 'json',
                        success: function (response) {
                            if(response.status == 'error') {
                                error.html(response.msg);
                            } else {
                                error.html('Success');
                                //modal.fadeOut();
                                //$('#modal-success2').fadeIn();
                                //$('#modal-success2').addClass('show');
                            }
                        },
                        error: function (response) {
                            console.log(response);
                            error.html(response.responseJSON.message);
                        },
                        complete: function (response) {
                        }
                    });
                }

                return false;
            });

            $('.js-remove-saved-address').on('click', function(e) {

                e.preventDefault();

                var id = $(this).data('id');

                if(id) {
                    $.ajax({
                        url: '{{ route('user.delete.address') }}',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function (response) {
                        },
                        error: function (response) {
                        },
                        complete: function (response) {
                        }
                    });
                }
            });

            $('.settings-actions .btn-link').on('click', function(e) {
                e.preventDefault();
                $('.js-edit-settings').trigger('click');
            });

           $('.settings-actions .btn-primary').on('click', function(e) {
               e.preventDefault();
               var inputs = $('.address-block input').serialize();

               if(inputs.length) {
                   $.ajax({
                       url: '{{ route('user.update') }}',
                       type: 'POST',
                       data: inputs,
                       dataType: 'json',
                       success: function (response) {

                       },
                       error: function (response) {
                       },
                       complete: function (response) {
                           $('.js-edit-settings').trigger('click');
                       }
                   });
               }
           });
        });
    </script>
    @include('handlers.auto_address')
@endpush
