<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title> @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <!-- Favicons -->
    <link href="{{ asset('frontend/img/logo_data_center.png')}}" rel="icon">
    <link href="{{ asset('frontend/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">
    <!-- Bootstrap CSS File -->
    <link href="{{ asset('frontend/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Libraries CSS Files -->
    <link href="{{ asset('frontend/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/animate/animate.min.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
    <!-- Main Stylesheet File -->
    <script src="{{ asset('frontend/lib/jquery/jquery.min.js')}}"></script>
    <link href="{{ asset('frontend/css/style.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/css/custom.css')}}" rel="stylesheet">
</head>
<style>
    body{
        background: #fff;
    }
</style>
<body>
<!--==========================
  Header
============================-->
<header id="header">
    <div class="container">
        <div class="row">
        <div class="col-lg-3"><a href="{{url('/user/dashboard')}}" class="scrollto"><img style="width: 75px" src="{{ asset('frontend/img/logo_data_center.png')}}"/></a></div>
        <div class="col-lg-6">
                <h2 class="text-center pt-2">VOTERLIST SYSTEM</h2>
        </div>
        <div class="col-lg-3" >
            <p style="margin:20px 0px 0px 0px; float: right"><b><a style="color: #63b190" href="{{ url('logout-user') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">  <i class="fa fa-sign-out"></i> LOGOUT</a></b></p>
            <form id="logout-form" action="{{ url('logout-user') }}" method="POST" style="display: none;">@csrf</form>
        </div>

        </div>
    </div>
</header>
<main id="main">
    @yield('content')
</main>



<script src="{{ asset('frontend/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>


<!-- Template Main Javascript File -->
<script src="{{ asset('frontend/js/main.js')}}"></script>
@yield('jsfiles')
</body >


</html>
