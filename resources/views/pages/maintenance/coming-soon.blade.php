@extends('layouts.auth')
@section('content')
    <div class="home-btn d-none d-sm-block">
        <a href="{{route('admin.dashboard.index')}}"><i class="mdi mdi-home-variant h2 text-white"></i></a>
    </div>

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <a href="{{route('admin.dashboard.index')}}" class="logo"><img src="{{asset('assets/images/logo-advision-white.png')}}" height="24" alt="logo"></a>
                        <h5 class="font-size-16 text-white-50 mb-4">Try an amazing experience in this application.</h5>

                        <div class="row justify-content-center mt-5">
                            <div class="col-md-8">
                                <div data-countdown="2020/03/05" class="counter-number"></div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/libs/jquery-countdown/jquery.countdown.min.js')}}"></script>
    <script>
        $("[data-countdown]").each(function () {
            var n = $(this), s = $(this).data("countdown");
            n.countdown(s, function (n) {
                $(this).html(n.strftime('<div class="coming-box">%D <span>Days</span></div> <div class="coming-box">%H <span>Hours</span></div> <div class="coming-box">%M <span>Minutes</span></div> <div class="coming-box">%S <span>Seconds</span></div> '))
            })
        });
    </script>
@endsection
