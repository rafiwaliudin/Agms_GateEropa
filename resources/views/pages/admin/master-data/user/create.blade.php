@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Buat Penggua Baru</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat penggua
                        baru</p>

                    <form action="{{route('admin.user.store')}}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" placeholder="First name" name="name"
                                   value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif" placeholder="Email" name="email"
                                   value="{{old('email')}}" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="custom-select form-control @if ($errors->has('role')) is-invalid @endif" name="role" required>
                                <option value selected disabled>-- Choose Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">
                                        {{$role->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role'))
                                <span class="text-danger">
                                        {{ $errors->first('role') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @if ($errors->has('password')) is-invalid @endif" placeholder="Password" name="password"
                                   value="{{old('password')}}" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">
                                        {{ $errors->first('password') }}
                                    </span>
                            @endif
                        </div>
                        <button class="btn btn-primary" type="submit">Submit form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
