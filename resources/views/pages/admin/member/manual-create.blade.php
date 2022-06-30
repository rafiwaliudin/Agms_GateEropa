@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Buat Tamu Baru</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat member
                        baru</p>

                    <form action="{{route('admin.member.store')}}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @include('flash::message')
                        <input type="hidden" name="member_type" value="{{$type}}">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control  @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="Name" name="name"
                                   value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span >
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" class="form-control @if ($errors->has('phone')) is-invalid @endif"
                                   placeholder="Phone" name="phone"
                                   value="{{old('phone')}}" required>
                            @if ($errors->has('phone'))
                                <span >
                                        {{ $errors->first('phone') }}
                                    </span>
                            @endif
                        </div>

                        <button class="btn btn-primary float-right" type="submit">Submit form</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
