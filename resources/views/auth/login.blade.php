@extends('layouts.auth')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center mb-5">
                <a href="#" class="logo"><img src="{{asset('assets/images/agms-logo.png')}}"
                                                       width="400"
                                                       alt="logo" style="margin-bottom:-120px;margin-top:-100px"></a>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row justify-content-center mt-5">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="p-2">
                        <h5 class="mb-5 text-center">Sign in to continue to AGMS</h5>
                        <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label for="username">Email</label>
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span  role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="userpassword">Password</label>
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password"
                                               required autocomplete="current-password">
                                        @error('password')
                                        <span  role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success btn-block waves-effect waves-light"
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
    <!-- end row -->
@endsection
