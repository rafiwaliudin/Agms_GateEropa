@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Buat Gate Baru</h4>
                <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat gate
                    baru</p>

                <form action="{{route('admin.residential-gate.store')}}" method="POST" class="needs-validation"
                    novalidate>
                    @csrf
                    @include('flash::message')
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                            placeholder="Nama" name="name" value="{{old('name')}}" required>
                        @if ($errors->has('name'))
                        <span class="text-danger">
                            {{ $errors->first('name') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control @if ($errors->has('longitude')) is-invalid @endif"
                            placeholder="Longitude" name="longitude" value="{{old('longitude')}}" required>
                        @if ($errors->has('longitude'))
                        <span class="text-danger">
                            {{ $errors->first('longitude') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control @if ($errors->has('latitude')) is-invalid @endif"
                            placeholder="Latitude" name="latitude" value="{{old('latitude')}}" required>
                        @if ($errors->has('latitude'))
                        <span class="text-danger">
                            {{ $errors->first('latitude') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="cluster">Cluster</label>
                        <select class="custom-select form-control @if ($errors->has('cluster')) is-invalid @endif"
                            name="cluster" required>
                            <option value selected disabled>-- Choose Cluster --</option>
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
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control @if ($errors->has('phone')) is-invalid @endif"
                            placeholder="Phone Number" name="phone" id="phonefield" value="{{old('phone')}}">
                        @if ($errors->has('phone'))
                        <span class="text-danger">
                            {{ $errors->first('phone') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_mata">IP Mata</label>
                        <input type="text" class="form-control @if ($errors->has('ip_mata')) is-invalid @endif"
                            placeholder="IP Mata" name="ip_mata" value="{{old('ip_mata')}}">
                        @if ($errors->has('ip_mata'))
                        <span class="text-danger">
                            {{ $errors->first('ip_mata') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_dc_data">IP Data</label>
                        <input type="text" class="form-control @if ($errors->has('ip_dc_data')) is-invalid @endif"
                            placeholder="IP Data" name="ip_dc_data" value="{{old('ip_dc_data')}}">
                        @if ($errors->has('ip_dc_data'))
                        <span class="text-danger">
                            {{ $errors->first('ip_dc_data') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_dc_image">IP Data Image</label>
                        <input type="text" class="form-control @if ($errors->has('ip_dc_image')) is-invalid @endif"
                            placeholder="IP Data Image" name="ip_dc_image" value="{{old('ip_dc_image')}}">
                        @if ($errors->has('ip_dc_image'))
                        <span class="text-danger">
                            {{ $errors->first('ip_dc_image') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_mysql">IP Mysql</label>
                        <input type="text" class="form-control @if ($errors->has('ip_mysql')) is-invalid @endif"
                            placeholder="IP Mysql" name="ip_mysql" value="{{old('ip_mysql')}}">
                        @if ($errors->has('ip_mysql'))
                        <span class="text-danger">
                            {{ $errors->first('ip_mysql') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_mongo">IP Mongo</label>
                        <input type="text" class="form-control @if ($errors->has('ip_mongo')) is-invalid @endif"
                            placeholder="IP Mongo" name="ip_mongo" value="{{old('ip_mongo')}}">
                        @if ($errors->has('ip_mongo'))
                        <span class="text-danger">
                            {{ $errors->first('ip_mongo') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="ip_dvr">IP DVR</label>
                        <input type="text" class="form-control @if ($errors->has('ip_dvr')) is-invalid @endif"
                            placeholder="IP DVR" name="ip_dvr" value="{{old('ip_dvr')}}">
                        @if ($errors->has('ip_dvr'))
                        <span class="text-danger">
                            {{ $errors->first('ip_dvr') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_mata">PORT Mata</label>
                        <input type="text" class="form-control @if ($errors->has('port_mata')) is-invalid @endif"
                            placeholder="PORT Mata" name="port_mata" value="{{old('port_mata')}}">
                        @if ($errors->has('port_mata'))
                        <span class="text-danger">
                            {{ $errors->first('port_mata') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_dc_data">PORT Data</label>
                        <input type="text" class="form-control @if ($errors->has('port_dc_data')) is-invalid @endif"
                            placeholder="PORT Data" name="port_dc_data" value="{{old('port_dc_data')}}">
                        @if ($errors->has('port_dc_data'))
                        <span class="text-danger">
                            {{ $errors->first('port_dc_data') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_dc_image">PORT Data Image</label>
                        <input type="text" class="form-control @if ($errors->has('port_dc_image')) is-invalid @endif"
                            placeholder="PORT Data Image" name="port_dc_image" value="{{old('port_dc_image')}}">
                        @if ($errors->has('port_dc_image'))
                        <span class="text-danger">
                            {{ $errors->first('port_dc_image') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_mysql">PORT Mysql</label>
                        <input type="text" class="form-control @if ($errors->has('port_mysql')) is-invalid @endif"
                            placeholder="PORT Mysql" name="port_mysql" value="{{old('port_mysql')}}">
                        @if ($errors->has('port_mysql'))
                        <span class="text-danger">
                            {{ $errors->first('port_mysql') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_mongo">PORT Mongo</label>
                        <input type="text" class="form-control @if ($errors->has('port_mongo')) is-invalid @endif"
                            placeholder="PORT Mongo" name="port_mongo" value="{{old('port_mongo')}}">
                        @if ($errors->has('port_mongo'))
                        <span class="text-danger">
                            {{ $errors->first('port_mongo') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="port_dvr">PORT DVR</label>
                        <input type="text" class="form-control @if ($errors->has('port_dvr')) is-invalid @endif"
                            placeholder="PORT DVR" name="port_dvr" value="{{old('port_dvr')}}">
                        @if ($errors->has('port_dvr'))
                        <span class="text-danger">
                            {{ $errors->first('port_dvr') }}
                        </span>
                        @endif
                    </div>
                    <button class="btn btn-primary" type="submit">Submit form</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $(document).ready(function () {
        $('#phonefield').tokenfield();
    });
</script>
@endsection
