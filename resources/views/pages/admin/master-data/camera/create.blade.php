@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Buat Kamera Baru</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk membuat kamera
                        baru</p>

                    <form action="{{route('admin.camera.store')}}" method="POST" class="needs-validation"
                          enctype="multipart/form-data" novalidate>
                        @csrf
                        @include('flash::message')
                        <input type="hidden" name="camera_type" value="{{$type}}">

                        <div class="form-group is-invalid">
                            <label for="name">Camera Name</label>
                            <input type="text" class="form-control  @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="Camera Name" name="name"
                                   value="{{old('name')}}" required>
                            @if ($errors->has('name'))
                                <span >
                                        {{ $errors->first('name') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="camera_link">Camera Link</label>
                            <input type="text" class="form-control @if ($errors->has('camera_link')) is-invalid @endif"
                                   placeholder="Camera Link" name="camera_link"
                                   value="{{old('camera_link')}}" required>
                            @if ($errors->has('camera_link'))
                                <span >
                                        {{ $errors->first('camera_link') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea type="text" class="form-control @if ($errors->has('address')) is-invalid @endif"
                                      placeholder="Address" name="address"
                                      required>{{old('address')}}</textarea>
                            @if ($errors->has('address'))
                                <span >
                                        {{ $errors->first('address') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control @if ($errors->has('longitude')) is-invalid @endif"
                                   placeholder="Longitude" name="longitude"
                                   value="{{old('longitude')}}">
                            @if ($errors->has('longitude'))
                                <span >
                                        {{ $errors->first('longitude') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control @if ($errors->has('latitude')) is-invalid @endif"
                                   placeholder="Latitude" name="latitude"
                                   value="{{old('latitude')}}">
                            @if ($errors->has('latitude'))
                                <span >
                                        {{ $errors->first('latitude') }}
                                    </span>
                            @endif
                        </div>
                        @if ($type !== 'recognize')
                            <div class="form-group">
                                <label for="object">Object</label>
                                <select name="object[]"
                                        class="form-control js-example-basic-multiple  @if ($errors->has('object')) is-invalid @endif"
                                        multiple="multiple" required>
                                    @foreach($objectVehicles as $objectVehicle)
                                        <option value="{{$objectVehicle->code}}">
                                            {{$objectVehicle->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('object'))
                                    <span >
                                        {{ $errors->first('object') }}
                                    </span>
                                @endif
                            </div>
                            @if ($type == 'masking')
                                <div class="form-group">
                                    <label for="intruder_start">Intruder Start</label>
                                    <input type="time" class="form-control @if ($errors->has('intruder_start')) is-invalid @endif"
                                           placeholder="Latitude" name="intruder_start"
                                           value="{{old('intruder_start')}}" required>
                                    @if ($errors->has('intruder_start'))
                                        <span >
                                        {{ $errors->first('intruder_start') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="intruder_end">Intruder End</label>
                                    <input type="time" class="form-control @if ($errors->has('intruder_end')) is-invalid @endif"
                                           placeholder="Latitude" name="intruder_end"
                                           value="{{old('intruder_end')}}" required>
                                    @if ($errors->has('intruder_end'))
                                        <span >
                                        {{ $errors->first('intruder_end') }}
                                    </span>
                                    @endif
                                </div>
                            @endif
                        @endif
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            <input type="file" class="form-control @if ($errors->has('thumbnail')) is-invalid @endif"
                                   placeholder="Upload Thumbnail" name="thumbnail"
                                   value="{{old('thumbnail')}}">
                            # upload with size 800 px x 533 px
                            @if ($errors->has('thumbnail'))
                                <span >
                                        {{ $errors->first('thumbnail') }}
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
