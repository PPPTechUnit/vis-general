<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DATA CENTER @yield('title')</title>
    <link href="{{ asset('frontend/img/favicon.png')}}" rel="icon">
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('admin/global_assets/main/jquery.min.js')}}"></script>
    <script src="{{ asset('admin/global_assets/main/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('admin/global_assets/plugins/loaders/blockui.min.js')}}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
<!--    <script src="../../../../global_assets/js/plugins/visualization/d3/d3.min.js"></script>
    <script src="../../../../global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script src="../../../../global_assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script src="../../../../global_assets/js/plugins/ui/moment/moment.min.js"></script>
    <script src="../../../../global_assets/js/plugins/pickers/daterangepicker.js"></script>-->

    <script src="{{ asset('admin/assets/js/app.js')}}"></script>
<!--    <script src="../../../../global_assets/js/demo_pages/dashboard.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/streamgraph.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/sparklines.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/lines.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/areas.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/donuts.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/bars.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/progress.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/heatmaps.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/pies.js"></script>
    <script src="../../../../global_assets/js/demo_charts/pages/dashboard/light/bullets.js"></script>-->
    <!-- /theme JS files -->

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <a href="{{route('backend_dashboard')}}" class="d-inline-block">
            <img src="{{ asset('admin/assets/images/login-img.png')}}" alt="" style="height: 35px;">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        <span class="badge ml-md-3 mr-md-auto" style="color: #324148; background: #324148;">_</span>

        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('admin/assets/images/logo.png')}}" class="rounded-circle mr-2" height="34" alt="">
                    <span>{{ Auth::user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="icon-switch2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
@yield('csssection')


<!-- Page content -->
<div class="page-content">

    <!-- Main sidebar -->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

        <!-- Sidebar mobile toggler -->
        <div class="sidebar-mobile-toggler text-center">
            <a href="#" class="sidebar-mobile-main-toggle">
                <i class="icon-arrow-left8"></i>
            </a>
            Navigation
            <a href="#" class="sidebar-mobile-expand">
                <i class="icon-screen-full"></i>
                <i class="icon-screen-normal"></i>
            </a>
        </div>
        <!-- /sidebar mobile toggler -->


        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- User menu -->
            <div class="sidebar-user">
                <div class="card-body">
                    <div class="media">
                        <div class="mr-3">
                            <a href="{{route('backend_dashboard')}}"><img src="{{ asset('admin/assets/images/logo.png')}}" width="38" height="38" class="rounded-circle" alt=""></a>
                        </div>

                        <div class="media-body">
                            <div class="media-title font-weight-semibold">{{ Auth::user()->name }}</div>
                            <div class="font-size-xs opacity-50">
                                <i class="icon-pin font-size-sm"></i> &nbsp;Karachi
                            </div>
                        </div>

                        <div class="ml-3 align-self-center">
                            <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /user menu -->


            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
                    @php
                    $url = url()->current();
                    $full_url =explode("/",$url);

                //print_r($full_url)
                @endphp
                    <!-- Main -->
                    <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main Menu</div> <i class="icon-menu" title="Main"></i></li>

                    <li class="nav-item"><a href="{{route('backend_dashboard')}}" class="nav-link @if(in_array( "dashboard" ,$full_url )) active @endif"><i class="icon-home4"></i><span>Dashboard</span></a></li>
                        <li class="nav-item"><a href="{{url('backend/ppp-users')}}" class="nav-link @if(in_array( "ppp-users" ,$full_url )) active @endif"><i class="icon-users"></i><span>PPP Users</span></a></li>
                        <li class="nav-item"><a href="{{route('app-web-users.index')}}" class="nav-link @if(in_array( "app-web-users" ,$full_url )) active @endif"><i class="icon-users"></i><span>Polling Agents</span></a></li>
                        <li class="nav-item"><a href="{{url('backend/searched-voters')}}" class="nav-link @if(in_array( "searched-voters" ,$full_url )) active @endif"><i class="icon-users"></i><span>Searched Voters</span></a></li>

                </ul>
            </div>

        </div>
        <!-- /sidebar content -->

    </div>
    <!-- /main sidebar -->


    <!-- Main content -->
    <div class="content-wrapper">

    @yield('content')
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
                    <i class="icon-unfold mr-2"></i>
                    Footer
                </button>
            </div>
            <div class="navbar-collapse collapse" id="navbar-footer">
                <span class="navbar-text">
                    &copy; {{date('Y')}}. <a href="{{route('backend_dashboard')}}">PPP - Data Center</a>
                </span>
            </div>
        </div>
    </div>
</div>
</body>
@yield('jsfiles')
</html>
