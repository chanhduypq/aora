@extends('layouts.app')
@section('content')
    <div class="page page--contact">
        <div class="container">
            <div class="page-body">
                <div class="page-head">
                    <h2 class="page-heading">Contact Us</h2>
                </div>
                <div class="page-data">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6">
                            <div class="contact">
                                <p>If you are facing any issues with your purchase, or if you just want to say hi, connect with our friendly customer service team today. We'll be more than happy to help.</p>
                                <div class="address">
                                    <div class="address-item">
                                        <span class="address-label">Address:</span>
                                        <div class="address-value">
                                        46 East Coast Road #07-06<br>EastGate Commercial Building<br>Singapore 428766
                                        </div>
                                    </div>
                                    <div class="address-item">
                                        <span class="address-label">Customer Service Email:</span>
                                        <div class="address-value">
                                            <a href="#">contact@aora.sg</a>
                                        </div>
                                    </div>
                                    <div class="address-item">
                                        <span class="address-label">Customer Hotline Numer:</span>
                                        <div class="address-value">
                                            +65 6282 1296
                                            <small>(Mon-Sat, 10am to 6pm)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <div class="checkout checkout-card contact-form">
                                <form action="{{ route('feedback') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-form-label" for="">Name</label>
                                        <input required name="name" type="text" class="form-control" placeholder="Your name">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="">Email or Phone</label>
                                        <input required name="contact" type="text" class="form-control" placeholder="So we can contact you">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label" for="">Your Message</label>
                                        <textarea required class="form-control" name="msg" id="" cols="30" rows="3" placeholder="Type here"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Send Message</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.784118464994!2d103.90115831533801!3d1.3045871990484916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da18727a87e155%3A0x1a1ae1b6a2dbad2!2sEast+Gate+%40+Katong!5e0!3m2!1sen!2ssg!4v1513052378665" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
@endsection