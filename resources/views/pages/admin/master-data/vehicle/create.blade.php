@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Buat Kendaraan Baru</h4>
                <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat kendaraan
                    baru</p>

                <form action="{{route('admin.vehicle.store')}}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    @include('flash::message')
                    <div class="form-group">
                        <label for="license_plate">Plat Nomor</label>
                        <input type="text" class="form-control @if ($errors->has('license_plate')) is-invalid @endif"
                            placeholder="Plat Nomor" name="license_plate" value="{{old('license_plate')}}" required>
                        @if ($errors->has('license_plate'))
                        <span class="text-danger">
                            {{ $errors->first('license_plate') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="car_type">Jenis Kendaraan</label>
                        <input type="text" class="form-control @if ($errors->has('car_type')) is-invalid @endif"
                            placeholder="Jenis Kendaraan" name="car_type" value="{{old('car_type')}}" required>
                        @if ($errors->has('car_type'))
                        <span class="text-danger">
                            {{ $errors->first('car_type') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="car_color">Warna Kendaraan</label>
                        <input type="text" class="form-control @if ($errors->has('car_color')) is-invalid @endif"
                            placeholder="Warna Kendaraan" name="car_color" value="{{old('car_color')}}" required>
                        @if ($errors->has('car_color'))
                        <span class="text-danger">
                            {{ $errors->first('car_color') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cluster">Cluster</label>
                        <select
                            class="custom-select form-control selectpicker @if ($errors->has('cluster')) is-invalid @endif"
                            multiple data-live-search="true" name="cluster[]" id="multipleSelect" required>
                            @foreach($clusters as $cluster)
                            <option value="{{$cluster->id}}">
                                {{$cluster->name}}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('cluster'))
                        <span class="text-danger">
                            {{ $errors->first('cluster') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="time_status">Status Expired</label>
                        <input type="datetime-local"
                            class="form-control @if ($errors->has('time_status')) is-invalid @endif" name="time_status"
                            value="{{old('time_status')}}">
                        @if ($errors->has('time_status'))
                        <span class="text-danger">
                            {{ $errors->first('time_status') }}
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
