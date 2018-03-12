<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>Aora</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/css/animate.min.css" rel="stylesheet" />
    <link href="/assets/css/style.min.css" rel="stylesheet" />
    <link href="/assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="/assets/css/theme.css" rel="stylesheet" id="theme" />
    <link href="/assets/css/customs.css" rel="stylesheet" />

    <script src="/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
</head>
<body>
<div id="page-loader" class="fade in"><span class="spinner"></span></div>

<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
    <div id="header" class="header navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="/admin" class="navbar-brand">
                    <img src="/images/aora-logo.png" style="height: 33px;">
                </a>
                <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <form class="navbar-form full-width">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Enter keyword" />
                            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                        </div>
                    </form>
                </li>
                <li class="dropdown">
                    <a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
                        <i class="fa fa-bell-o"></i>
                        <span id="top_notification" class="label" style="display: none"></span>
                    </a>
                    <ul class="dropdown-menu media-list pull-right animated fadeInDown">
                        <li class="dropdown-header">Notifications (5)</li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-bug media-object bg-red"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Server Error Reports</h6>
                                    <div class="text-muted f-s-11">3 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="/assets/img/user-1.jpg" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">John Smith</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">25 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><img src="/assets/img/user-2.jpg" class="media-object" alt="" /></div>
                                <div class="media-body">
                                    <h6 class="media-heading">Olivia</h6>
                                    <p>Quisque pulvinar tellus sit amet sem scelerisque tincidunt.</p>
                                    <div class="text-muted f-s-11">35 minutes ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-plus media-object bg-green"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New User Registered</h6>
                                    <div class="text-muted f-s-11">1 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="media">
                            <a href="javascript:;">
                                <div class="media-left"><i class="fa fa-envelope media-object bg-blue"></i></div>
                                <div class="media-body">
                                    <h6 class="media-heading"> New Email From John</h6>
                                    <div class="text-muted f-s-11">2 hour ago</div>
                                </div>
                            </a>
                        </li>
                        <li class="dropdown-footer text-center">
                            <a href="javascript:;">View more</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown navbar-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Auth::user()->get_avatar() }}" alt="" />
                        <span class="hidden-xs">{{ Auth::user()->name }}</span> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu animated fadeInLeft">
                        <li class="arrow"></li>
                        <li><a href="javascript:;">Edit Profile</a></li>
                        <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
                        <li><a href="javascript:;">Calendar</a></li>
                        <li><a href="javascript:;">Setting</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div id="sidebar" class="sidebar">
        <div data-scrollbar="true" data-height="100%">
            <ul class="nav">
                <li class="nav-profile">
                    <div class="image">
                        <a href="javascript:;"><img src="{{ Auth::user()->get_avatar() }}" alt="" /></a>
                    </div>
                    <div class="info">
                        {{ Auth::user()->name }}
                        <small>Administrator</small>
                    </div>
                </li>
            </ul>
            <ul class="nav">
                <li class="nav-header">Navigation</li>
                <li {{Request::is('admin') || Request::is('admin/dashboard') ? 'class=active' : ''}}><a href="{{ route('admin.dashboard') }}"><i class="fa fa-laptop"></i> <span>Dashboard</span></a></li>
                <li {{Request::is('admin/orders') || Request::is('admin/orders/*') ? 'class=active' : ''}}><a href="{{ route('admin.orders') }}"><i class="fa fa-laptop"></i> <span>Orders</span></a></li>

                <li class="has-sub {{Request::is('admin/users') || Request::is('admin/users/*') ? 'active' : ''}}">
                    <a href="{{ route('admin.users') }}">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-group"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub-menu">
                        <li {{Request::is('admin/users') ? 'class=active' : ''}}><a href="{{ route('admin.users') }}">List</a></li>
                        <li {{Request::is('admin/users/create') ? 'class=active' : ''}}><a href="{{ route('admin.users.create') }}">Add</a></li>
                        <li {{Request::is('admin/users/deleted') ? 'class=active' : ''}}><a href="{{ route('admin.users.deleted') }}">Deleted</a></li>
                    </ul>
                </li>
                <li class="has-sub {{Request::is('admin/emails') || Request::is('admin/emails/*') ? 'active' : ''}}">
                    <a href="{{ route('admin.emails') }}">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-envelope"></i>
                        <span>Emails</span>
                    </a>
                    <ul class="sub-menu">
                        <li {{Request::is('admin/emails') ? 'class=active' : ''}}><a href="{{ route('admin.emails') }}">List</a></li>
                        <li {{Request::is('admin/emails/create') ? 'class=active' : ''}}><a href="{{ route('admin.emails.create') }}">Add</a></li>
                        <li {{Request::is('admin/emails/sent') || Request::is('admin/emails/sent/*') ? 'class=active' : ''}}><a href="{{ route('admin.emails.sent') }}">Sent</a></li>
                    </ul>
                </li>
                <li class="has-sub {{Request::is('admin/marketplaces') || Request::is('admin/marketplaces/*') ? 'active' : ''}}">
                    <a href="{{ route('admin.marketplaces') }}">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-shopping-bag"></i>
                        <span>Marketplaces</span>
                    </a>
                    <ul class="sub-menu">
                        <li {{Request::is('admin/marketplaces') ? 'class=active' : ''}}><a href="{{ route('admin.marketplaces') }}">List</a></li>
                        <li {{Request::is('admin/marketplaces/create') ? 'class=active' : ''}}><a href="{{ route('admin.marketplaces.create') }}">Add</a></li>
                    </ul>
                </li>
                <li class="has-sub {{Request::is('admin/blacklist') || Request::is('admin/blacklist/*') ? 'active' : ''}}">
                    <a href="{{ route('admin.blacklist') }}">
                        <b class="caret pull-right"></b>
                        <i class="fa fa-ban"></i>
                        <span>Blacklist</span>
                    </a>
                    <ul class="sub-menu">
                        <li {{Request::is('admin/blacklist') ? 'class=active' : ''}}><a href="{{ route('admin.blacklist') }}">List</a></li>
                        <li {{Request::is('admin/blacklist/create') ? 'class=active' : ''}}><a href="{{ route('admin.blacklist.create') }}">Add</a></li>
                    </ul>
                </li>

                <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="sidebar-bg"></div>
    <div id="content" class="content">
        @yield('content')
    </div>
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
</div>

<script src="/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/assets/crossbrowserjs/html5shiv.js"></script>
<script src="/assets/crossbrowserjs/respond.min.js"></script>
<script src="/assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>

<script src="/assets/js/apps.js"></script>

<script>
    $(document).ready(function() {
        App.init();
    });
</script>
@stack('scripts')
</body>
</html>