<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Aora</title>
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

        @stack('styles')

        <!-- favicons for all devices -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}"/>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}"/>
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}"/>
        <link rel="mask-icon" href="{{ asset('images/safari-pinned-tab.svg') }}" color="#5bbad5"/>
        <meta name="theme-color" content="#ffffff"/>
        <!-- favicons for all devices -->
    </head>
    <body>
        <div class="main @if(Route::currentRouteName() == 'pages.home'){{'main--lg'}}@endif">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <a href="#" class="navbar-hmbrgr js-toggle-hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                    <a class="navbar-brand" href="{{ route('pages.home') }}">
                        <img src="{{ asset('images/aora-logo.png') }}" alt="">
                    </a>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav navbar-nav--upd">
                            <li class="nav-item {{Request::is('orders') || Request::is('orders/*') ? 'active' : ''}}">
                                <a class="nav-link" href="{{ route('orders') }}"><i class="fa fa-clock-o"></i>My Orders</a>
                            </li>
                            <li class="nav-item {{Request::is('about') ? 'active' : ''}}">
                                <a class="nav-link" href="{{ route('pages.about') }}"><i class="fa fa-info-circle"></i>About Us</a>
                            </li>
                            <li class="nav-item {{Request::is('contact') ? 'active' : ''}}">
                                <a class="nav-link" href="{{ route('pages.contact') }}"><i class="fa fa-phone-square"></i>Contact Us</a>
                            </li>
                        </ul>
                    </div>
                    <div class="user ml-auto">
                        @if(Route::currentRouteName() != 'pages.home')
                        <a href="#" class="btn btn-link btn--search js-search">
                            <i class="fa fa-search"></i>
                        </a>
                        @endif
                        @if(!Auth::check())
                            <a class="btn btn-outline-primary btn--login" href="{{ route('login') }}" style="margin-right:10px">Log In</a>
                            <a class="btn btn-outline-primary btn--login" href="{{ route('register') }}">Sign Up</a>
                        @else
                        <div class="logged logged--empty">
                            <div class="logged-data">
                            <a class="logged-name" href="{{ route('user.setting') }}">{{ Auth::user()->name }}</a>
                            <a class="logged-out" href="{{ route('logout') }}"><i class="logged-ico"></i>Log Out</a>
                            </div>
                        </div>
                        @endif
                        @if ($cart_count || 1==1)
                            <a class="btn btn-link btn--cart" href="{{ route('cart') }}">
                                <i class="cart-ico cart-ico--full"><span>{{ $cart_count }}</span></i>
                            </a>
                        @else
                            <span class="btn btn-link btn--cart">
                                <i class="cart-ico"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </nav>
            <div class="hamburger">
                <div class="hamburger-wrap">
                    <div class="hamburger-title">Menu</div>
                    <ul class="navbar-nav">
                        <li class="nav-item {{Request::is('/') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('pages.home') }}"><i class="fa fa-search"></i>Search on Amazon</a>
                        </li>
                        <li class="nav-item {{Request::is('orders') || Request::is('orders/*') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('orders') }}"><i class="fa fa-clock-o"></i>My Orders</a>
                        </li>
                        <li class="nav-item {{Request::is('about') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('pages.about') }}"><i class="fa fa-clock-o"></i>About Us</a>
                        </li>
                        <li class="nav-item {{Request::is('contact') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('pages.contact') }}"><i class="fa fa-clock-o"></i>Contact Us</a>
                        </li>
                        <li class="nav-item {{Request::is('setting') ? 'active' : ''}}">
                            <a class="nav-link" href="{{ route('user.setting') }}"><i class="fa fa-sliders"></i>Settings</a>
                        </li>
                    </ul>
                    <div class="logged logged--mobile">
                        <i class="logged-pic">
                            <img src="{{ asset('images/img-user.png') }}" alt="">
                        </i>
                        <div class="logged-data">
                            @if(!Auth::check())
                                <span class="logged-name">Welcome! </span>
                                <a class="logged-out" href="{{ route('login') }}" style="margin-right:10px"><i class="logged-ico"></i>Log In</a>
                                <a class="logged-out" href="{{ route('register') }}"><i class="logged-ico"></i>Sign Up</a>
                            @else
                                <span class="logged-name">{{ Auth::user()->name }}</span>
                                <a class="logged-out" href="{{ route('logout') }}"><i class="logged-ico"></i>Log Out</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(Route::currentRouteName() != 'pages.home')
            <div class="search-toggle">
                <div class="container">
                    <form id="myForm" method="post" action="{{ route('pages.result') }}" class="form form--inner">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="form-input">
                                <input required name="link" class="form-control" type="text" value="" placeholder="URL on amazon">
                                <button type="submit" class="btn btn-secondary btn--submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @yield('content')

            <div class="main__push"></div>
        </div><!-- /.main -->

        <footer class="footer footer--new clearfix">
            <div class="container-fluid">
                <img src="{{ asset('images/footer-logo.png') }}" alt="" class="footer-logo">
                <span class="footer-copy">Copyright &copy; 2017 / All rights reserved.</span>
                <a href="#" class="btn btn-outline-secondary btn--help">Need help</a>
            </div>
        </footer>

        <div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <img class="modal-logo" src="{{ asset('images/aora-logo-modal.png') }}" alt="">
                        <h5 class="modal-title">Log In</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="orders.html" class="form form--login">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input class="form-control" type="text" placeholder="Your e-mail" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input class="form-control" type="password" placeholder="Your pass" value="">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-double-border btn-outline-secondary btn-block" data-toggle="modal" data-target="#modal-forgot" data-dismiss="modal">Forgot password</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" class="btn btn-double-border btn-primary btn-block">Log In</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-fieldset">
                                <h3 class="form-sub">With social networks</h3>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="#" class="btn btn-info btn-fb btn-block"><i class="fa fa-facebook"></i>Facebook</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="#" class="btn btn-gplus btn-warning btn-block"><i class="fa fa-google-plus"></i>Google +</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer modal-footer--padding">
                        <button type="button" class="btn btn-double-border btn-outline-secondary btn-block btn-create" data-toggle="modal" data-target="#modal-create" data-dismiss="modal">Create Account</button>
                        <button type="button" class="btn btn-double-border btn-outline-secondary btn-block btn-create btn-create--mobile js-toggle-create-acc">Create Account<span class="how-toggle"><i class="fa fa-angle-up"></i></span></button>
                    </div>
                    <div class="create-form-mobile">
                        <form action="orders.html" class="form form--login">
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input class="form-control" type="text" placeholder="First and last name" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input class="form-control" type="text" placeholder="Your e-mail" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input class="form-control" type="password" placeholder="Create a pass" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Confirm Password</label>
                                <input class="form-control" type="password" placeholder="Pass again" value="">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-double-border btn-primary btn-block" data-toggle="modal" data-target="#modal-success" data-dismiss="modal">Create User</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group--privacy">
                                <div class="row">
                                    <div class="col">
                                        <p>By clicking to Create User you agree to the <a href="#" class="btn btn-outline-primary">Privacy Policy</a></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="orders.html" class="form form--login">
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input class="form-control" type="text" placeholder="First and last name" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input class="form-control" type="text" placeholder="Your e-mail" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input class="form-control" type="password" placeholder="Create a pass" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Confirm Password</label>
                                <input class="form-control" type="password" placeholder="Pass again" value="">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-double-border btn-primary btn-block" data-toggle="modal" data-target="#modal-success" data-dismiss="modal">Create User</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group--privacy">
                                <div class="row">
                                    <div class="col">
                                        <p>By clicking to Create User you agree to the <a href="#" class="btn btn-outline-primary">Privacy Policy</a></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-double-border btn-outline-secondary btn-block" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-forgot" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">&nbsp;</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="orders.html" class="form form--login">
                            <div class="form-group">
                                <i class="fa fa-lock"></i>
                                <img class="modal-logo" src="{{ asset('images/aora-logo-modal.png') }}" alt="">
                                <h5 class="modal-title">Forgot Password</h5>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input class="form-control" type="text" placeholder="Your e-mail" value="">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-double-border btn-primary btn-block" data-toggle="modal" data-target="#modal-success" data-dismiss="modal">Send</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group--sented">
                                <p class="text-muted">We will send the password to the specified mail</p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-double-border btn-outline-secondary btn-block">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">&nbsp;</h5>
                    </div>
                    <div class="modal-body">
                        <form action="orders.html" class="form form--login">
                            <div class="form-group">
                                <img class="modal-logo" src="{{ asset('images/aora-logo-modal.png') }}" alt="">
                                <i class="fa fa-check-circle-o"></i>
                                <h5 class="modal-title">Check your email</h5>
                            </div>
                            <div class="form-group form-group--sented">
                                <p class="text-muted">We sent the password to<br>the specified mail</p>
                            </div>
                            <div class="form-group form-group--ok">
                                <div class="row">
                                    <div class="col text-center">
                                        <a href="#" class="btn btn-double-border btn-primary" data-dismiss="modal">Ok, thanks</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-success2" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">&nbsp;</h5>
                    </div>
                    <div class="modal-body">
                        <form action="/" class="form form--login">
                            <div class="form-group">
                                <img class="modal-logo" src="{{ asset('images/aora-logo-modal.png') }}" alt="">
                                <i class="fa fa-check-circle-o"></i>
                                <h5 class="modal-title">Success</h5>
                            </div>
                            <div class="form-group form-group--sented">
                                <p class="text-muted"></p>
                            </div>
                            <div class="form-group form-group--ok">
                                <div class="row">
                                    <div class="col text-center">
                                        <a href="#" class="btn btn-double-border btn-primary" data-dismiss="modal">Ok, thanks</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

	    <script defer src="{{ asset('js/theme-base.js') }}"></script>
        <script defer src="{{ asset('js/theme-run.js') }}"></script>

        @stack('scripts')
    </body>
</html>