@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Buat Cluster Baru</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat cluster
                        baru</p>

                    <form action="{{route('admin.cluster.store')}}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @include('flash::message')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif" placeholder="Nama" name="name"
                                   value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control @if ($errors->has('longitude')) is-invalid @endif" placeholder="Longitude" name="longitude"
                                   value="{{old('longitude')}}" required>
                            @if ($errors->has('longitude'))
                                <span class="text-danger">
                                        {{ $errors->first('longitude') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control @if ($errors->has('latitude')) is-invalid @endif" placeholder="Latitude" name="latitude"
                                   value="{{old('latitude')}}" required>
                            @if ($errors->has('latitude'))
                                <span class="text-danger">
                                        {{ $errors->first('latitude') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="area">Area</label>
                            <select class="custom-select form-control @if ($errors->has('area')) is-invalid @endif" name="area" required>
                                <option value selected disabled>-- Choose Area --</option>
                                @foreach($areas as $area)
                                    <option value="{{$area->id}}">
                                        {{$area->name}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('area'))
                                <span class="text-danger">
                                        {{ $errors->first('area') }}
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
