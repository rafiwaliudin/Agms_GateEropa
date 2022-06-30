@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Ubah Data Penggua</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data
                        pengguna</p>

                    <form action="{{route('admin.user.update', $user->id)}}" method="POST" class="needs-validation"
                          novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="First name" name="name"
                                   value="{{$user->name}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email')) is-invalid @endif">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                   value="{{$user->email}}" required>
                            @if ($errors->has('email'))
                                <span class="text-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('role')) is-invalid @endif">
                            <label for="role">Role</label>
                            <select class="custom-select form-control" name="role">
                                <option disabled>-- Choose Role --</option>
                                @foreach($roles as $role)
                                    <option
                                        value="{{$role->id}}"
                                        @if($user->roles->first()->id == $role->id) selected @endif">
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
                        <div class="form-group @if ($errors->has('password')) is-invalid @endif">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password">
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
