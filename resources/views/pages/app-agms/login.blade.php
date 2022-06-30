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

        @yield('content')
        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-lg-12">
                <h5 class="mb-4 text-center app-login">Sign in to continue to APP-AGMS</h5>
                @include('flash::message')
                <div class="card">
                    <div class="card-body p-4">
                        <div class="p-2">
                            <form action="{{route('app.login')}}" method="post" class="form-horizontal">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="phone">Phone Number</label>
                                            <input id="phone" type="text" placeholder="Masukan Nomor HP anda"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   name="phone"
                                                   value="{{ old('phone') }}" required autocomplete="phone" autofocus>

                                            @error('phone')
                                            <span role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="userpassword">Password</label>
                                            <input id="password" type="password" placeholder="Masukan password anda"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password"
                                                   required autocomplete="current-password">
                                            @error('password')
                                            <span role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                        <!-- <a class="forget-password" href="#">Forget password ? </a> -->
                                        <div class="mt-4">
                                            <button class="btn btn-info btn-block waves-effect waves-light"
                                                    type="submit">Log In
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>
@yield('js')

</body>
</html>


