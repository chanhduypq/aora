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

        <!-- favicons for all devices -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}"/>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}"/>
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}"/>
        <link rel="mask-icon" href="{{ asset('images/safari-pinned-tab.svg') }}" color="#5bbad5"/>
        <meta name="theme-color" content="#ffffff"/>
        <!-- favicons for all devices -->
        <script src="//code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
    <div class="main main--admin">

        <nav class="navbar navbar-expand-lg">
            <a href="#" class="navbar-hmbrgr js-toggle-hamburger">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <a class="navbar-brand" href="/admin">
                <img src="{{ asset('images/aora-logo.png') }}" alt="">
            </a>
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="navbar-nav navbar-nav--admin">
                    <li class="nav-item {{Request::is('admin/orders') || Request::is('admin/orders/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.orders') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item {{Request::is('admin/users') || Request::is('admin/users/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.users') }}">Users</a>
                    </li>
                    <li class="nav-item {{Request::is('admin/emails') || Request::is('admin/emails/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.emails') }}">Emails</a>
                    </li>
                    <li class="nav-item {{Request::is('admin/marketplaces') || Request::is('admin/marketplaces/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.marketplaces') }}">Market Places</a>
                    </li>
                    <li class="nav-item {{Request::is('admin/blacklist') || Request::is('admin/blacklist/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.blacklist') }}">Black List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>
            <div class="user ml-auto">
                <div class="logged">
                    <i class="logged-pic">
                        <img src="{{ asset('images/img-user.png') }}" alt="">
                    </i>
                    <div class="logged-data">
                        <span class="logged-name">{{ Auth::user()->name }}</span>
                        <a class="logged-out" href="{{ route('logout') }}">Log out</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="hamburger">
            <div class="hamburger-wrap">
                <div class="hamburger-title">Menu</div>
                <ul class="navbar-nav">
                    <li class="nav-item {{Request::is('admin/orders') || Request::is('admin/orders/*') ? 'active' : ''}}">
                        <a class="nav-link" href="{{ route('admin.orders') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
                <div class="logged logged--mobile">
                    <i class="logged-pic">
                        <img src="{{ asset('images/img-user.png') }}" alt="">
                    </i>
                    <div class="logged-data">
                        <span class="logged-name">{{ Auth::user()->name }}</span>
                        <a class="logged-out" href="{{ route('logout') }}"><i class="logged-ico"></i>Log Out</a>
                    </div>
                </div>
            </div>
        </div>

            @yield('content')

            <div class="main__push"></div>
        </div><!-- /.main -->

        @stack('scripts')
    </body>
</html>