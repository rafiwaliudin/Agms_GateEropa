@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Ubah Data Scheduler</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data scheduler</p>

                    <form action="{{route('admin.scheduler.update', $scheduler->id)}}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" placeholder="Name" name="name"
                                   value="{{$scheduler->name}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_to')) is-invalid @endif">
                            <label for="email_to">Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_to"
                                   value="{{$scheduler->email_to}}" required>
                            @if ($errors->has('email_to'))
                                <span class="text-danger">
                                        {{ $errors->first('email_to') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_cc_1')) is-invalid @endif">
                            <label for="email_cc_1">Email Cc 1</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_cc_1"
                                   value="{{$scheduler->email_cc_1}}">
                            @if ($errors->has('email_cc_1'))
                                <span class="text-danger">
                                        {{ $errors->first('email_cc_1') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_cc_2')) is-invalid @endif">
                            <label for="email_cc_2">Email Cc 2</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_cc_2"
                                   value="{{$scheduler->email_cc_2}}">
                            @if ($errors->has('email_cc_2'))
                                <span class="text-danger">
                                        {{ $errors->first('email_cc_2') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_cc_3')) is-invalid @endif">
                            <label for="email_cc_3">Email Cc 3</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_cc_3"
                                   value="{{$scheduler->email_cc_3}}">
                            @if ($errors->has('email_cc_3'))
                                <span class="text-danger">
                                        {{ $errors->first('email_cc_3') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_cc_4')) is-invalid @endif">
                            <label for="email_cc_4">Email Cc 4</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_cc_4"
                                   value="{{$scheduler->email_cc_4}}">
                            @if ($errors->has('email_cc_4'))
                                <span class="text-danger">
                                        {{ $errors->first('email_cc_4') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('email_cc_5')) is-invalid @endif">
                            <label for="email_cc_5">Email Cc 5</label>
                            <input type="email" class="form-control" placeholder="Email" name="email_cc_5"
                                   value="{{$scheduler->email_cc_5}}">
                            @if ($errors->has('email_cc_5'))
                                <span class="text-danger">
                                        {{ $errors->first('email_cc_5') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('schedule_time')) is-invalid @endif">
                            <label for="schedule_time">Schedule Time</label>
                            <input type="time" class="form-control" placeholder="Schedule time" name="schedule_time"
                                   value="{{$scheduler->schedule_time}}" required>
                            @if ($errors->has('schedule_time'))
                                <span class="text-danger">
                                        {{ $errors->first('schedule_time') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group @if ($errors->has('range')) is-invalid @endif">
                            <label for="range">Range</label>
                            <input type="number" class="form-control" placeholder="Range" name="range"
                                   value="{{$scheduler->range}}" required>
                            @if ($errors->has('range'))
                                <span class="text-danger">
                                        {{ $errors->first('range') }}
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
