@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Ubah Data Kendaraan</h4>
                <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengganti data
                    kendaraan</p>

                <form action="{{route('admin.vehicle.update',  $vehicle->id)}}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @method('patch')
                    @include('flash::message')
                    <div class="form-group">
                        <label for="license_plate">Plat Nomor</label>
                        <input type="text" class="form-control @if ($errors->has('license_plate')) is-invalid @endif"
                            placeholder="Plat Nomor" name="license_plate" value="{{$vehicle->license_plate}}" required>
                        @if ($errors->has('license_plate'))
                        <span class="text-danger">
                            {{ $errors->first('license_plate') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="car_type">Jenis Kendaraan</label>
                        <input type="text" class="form-control @if ($errors->has('car_type')) is-invalid @endif"
                            placeholder="Jenis Kendaraan" name="car_type" value="{{$vehicle->car_type}}" required>
                        @if ($errors->has('car_type'))
                        <span class="text-danger">
                            {{ $errors->first('car_type') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="car_color">Warna Kendaraan</label>
                        <input type="text" class="form-control @if ($errors->has('car_color')) is-invalid @endif"
                            placeholder="Warna Kendaraan" name="car_color" value="{{$vehicle->car_color}}" required>
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
                            <option value="{{$cluster->id}}" @if($vehicle->clusters()->find($cluster->id)) selected
                                @endif>
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
                            value="{{strftime('%Y-%m-%dT%H:%M:%S', strtotime($vehicle->time_status))}}">
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
