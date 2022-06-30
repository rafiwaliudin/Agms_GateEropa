@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Ubah Data Kawasan</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data
                        kawasan</p>

                    <form action="{{route('admin.area.update',  $area->id)}}" method="POST"
                          class="needs-validation" novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text"
                                   class="form-control @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="Nama" name="name"
                                   value="{{$area->name}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control @if ($errors->has('longitude')) is-invalid @endif"
                                   placeholder="Longitude" name="longitude"
                                   value="{{$area->longitude}}" required>
                            @if ($errors->has('longitude'))
                                <span class="text-danger">
                                        {{ $errors->first('longitude') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control @if ($errors->has('latitude')) is-invalid @endif"
                                   placeholder="Latitude" name="latitude"
                                   value="{{$area->latitude}}" required>
                            @if ($errors->has('latitude'))
                                <span class="text-danger">
                                        {{ $errors->first('latitude') }}
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
