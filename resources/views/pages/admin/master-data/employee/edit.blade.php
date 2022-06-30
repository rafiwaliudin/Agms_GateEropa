@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Ubah Data Pegawai</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data
                        pegawai</p>

                    <form action="{{route('admin.employee.update', $employee->id)}}" method="POST"
                          enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')
                        <div class="form-group @if ($errors->has('nik')) was-validated @endif">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control" placeholder="NIK" name="nik"
                                   value="{{$employee->nik}}" required>
                            @if ($errors->has('nik'))
                                <span>
                                        {{ $errors->first('nik') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('name')) was-validated @endif">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" placeholder="Your name" name="name"
                                   value="{{$employee->name}}" required>
                            @if ($errors->has('name'))
                                <span>
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('pob')) was-validated @endif">
                            <label for="pob">Place of Birth</label>
                            <input type="text" class="form-control" placeholder="Place of Birth" name="pob"
                                   value="{{$employee->pob}}" required>
                            @if ($errors->has('pob'))
                                <span>
                                        {{ $errors->first('pob') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('dob')) was-validated @endif">
                            <label for="dob">Date of Birth</label>
                            <input type="date" class="form-control" name="dob"
                                   value="{{$employee->dob}}" required>
                            @if ($errors->has('dob'))
                                <span>
                                        {{ $errors->first('dob') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('gender')) was-validated @endif">
                            <label for="gender">Gender</label>
                            <select class="custom-select form-control" name="gender">
                                <option value selected disabled>-- Choose Gender --</option>
                                @foreach($genders as $gender)
                                    <option
                                        value="{{$gender->id}}"
                                        @if($employee->gender_id == $gender->id) selected @endif>
                                        {{$gender->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('gender'))
                                <span>
                                        {{ $errors->first('gender') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('bloodType')) was-validated @endif">
                            <label for="bloodType">Blood Type</label>
                            <select class="custom-select form-control" name="bloodType">
                                <option value selected disabled>-- Choose Blood Type --</option>
                                @foreach($bloodTypes as $bloodType)
                                    <option
                                        value="{{$bloodType->id}}"
                                        @if($employee->blood_type_id == $bloodType->id) selected @endif>
                                        {{$bloodType->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('bloodType'))
                                <span>
                                        {{ $errors->first('bloodType') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('religion')) was-validated @endif">
                            <label for="religion">Religion</label>
                            <select class="custom-select form-control" name="religion">
                                <option value selected disabled>-- Choose Religion --</option>
                                @foreach($religions as $religion)
                                    <option
                                        value="{{$religion->id}}"
                                        @if($employee->religion_id == $religion->id) selected @endif>
                                        {{$religion->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('religion'))
                                <span>
                                        {{ $errors->first('religion') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('province')) was-validated @endif">
                            <label for="province">Province</label>
                            <input type="text" class="form-control" placeholder="Province" name="province"
                                   value="{{$employee->province}}" required>
                            @if ($errors->has('province'))
                                <span>
                                        {{ $errors->first('province') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('city')) was-validated @endif">
                            <label for="city">City</label>
                            <input type="text" class="form-control" placeholder="City" name="city"
                                   value="{{$employee->city}}" required>
                            @if ($errors->has('city'))
                                <span>
                                        {{ $errors->first('city') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('address')) was-validated @endif">
                            <label for="address">Address</label>
                            <textarea type="text" class="form-control" placeholder="City" name="address"
                                      required>{{$employee->address}}</textarea>
                            @if ($errors->has('address'))
                                <span>
                                        {{ $errors->first('address') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('position')) was-validated @endif">
                            <label for="position">Position</label>
                            <select class="custom-select form-control" name="position">
                                <option value selected disabled>-- Choose Position --</option>
                                @foreach($positions as $position)
                                    <option
                                        value="{{$position->id}}"
                                        @if($employee->position_id == $position->id) selected @endif>
                                        {{$position->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('position'))
                                <span>
                                        {{ $errors->first('position') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('department')) was-validated @endif">
                            <label for="department">Department</label>
                            <select class="custom-select form-control" name="department">
                                <option value selected disabled>-- Choose Department --</option>
                                @foreach($departments as $department)
                                    <option
                                        value="{{$department->id}}"
                                        @if($employee->department_id == $department->id) selected @endif>
                                        {{$department->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('department'))
                                <span>
                                        {{ $errors->first('department') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('photo')) was-validated @endif">
                            <label for="photo">Foto</label>
                            <input type="file" class="form-control" placeholder="Upload Photo" name="photo"
                                   value="{{$employee->photo}}">
                            @if ($errors->has('photo'))
                                <span>
                                        {{ $errors->first('photo') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email')) was-validated @endif">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                   value="{{$employee->user->email}}" required>
                            @if ($errors->has('email'))
                                <span>
                                        {{ $errors->first('email') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('password')) was-validated @endif">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password"
                                   value="{{old('password')}}">
                            @if ($errors->has('password'))
                                <span>
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
