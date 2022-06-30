<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Dashboard | AGMS - Try an amazing experience in this application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/agms-thumbnail.png')}}">

    <!-- slick css -->
    <link href="{{asset('assets/libs/slick-slider/slick/slick.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/libs/slick-slider/slick/slick-theme.css')}}" rel="stylesheet" type="text/css"/>

    <!-- jvectormap -->
    <link href="{{asset('assets/libs/jqvmap/jqvmap.min.css')}}" rel="stylesheet"/>

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css"/>

    <link href="{{asset('assets/css/css-qrcode.css')}}" rel="stylesheet" type="text/css"/>

</head>

<body>
<div class="d-flex justify-content-center ">
    <div class="card bg-secondary">
        <img class="card-img-top logo" src="{{asset('assets/images/agms-logo.png')}}" alt="Card image cap">
        @include('flash::message')
        @yield('content')
        <div class="flex">
            <a href="{{route('app.index')}}" class="link nav-icon">
                <i class="fas fa-home fa-4x"></i>
                <p class="title-home">HOME</p>
            </a>
            <a href="{{route('app.list')}}" class="link nav-icon">
                <i class="fas fa-car fa-4x"></i>
                <p class="title-home">Vehicle</p>
            </a>
            <a href="{{route('app.visitor')}}" class="link nav-icon">
                <i class="far fa-clipboard fa-4x"></i>
                <p class="title-home">Visitor</p>
            </a>
            <a href="{{ route('logout') }}" class="link nav-icon"
               onclick="event.preventDefault(); document.getElementById('logout-form-app').submit();">
                <i class="fas fa-sign-out-alt fa-4x"></i>
                <p class="title-home">Logout</p>
            </a>
            <form id="logout-form-app" action="{{ route('logout') }}" method="POST"
                  style="display: none;">
                @csrf
            </form>
        </div>

    </div>
</div>
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>
@yield('js')

</body>
</html>


