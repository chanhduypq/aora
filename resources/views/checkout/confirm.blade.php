@extends('layouts.app')
@section('content')
<div class="page">
<div class="container">
    <div class="success">
        <i class="success-ico"></i>
        <h2 class="search-title">Payment is successful</h2>
        <p>You will receive an email confirmation containing your tracking code and your order details within 30 minutes at cedricchoy@gmail.com. If you have any questions concerning your purchase, please connect with us at customerservice@aora.com.sg. Thank you, and we look forward to you receiving your purchase!</p>
        <div class="success-ok">
            <a href="{{ route('orders') }}" class="btn btn-primary px-3"><i class="fa fa-check"></i>Ok</a>
        </div>
    </div>
</div>
</div>
@endsection