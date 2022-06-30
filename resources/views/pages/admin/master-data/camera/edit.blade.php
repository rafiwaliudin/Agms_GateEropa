@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Kamera</h4>
                    <p class="card-title-desc">Lengkapi form dibawah dan klik tombol submit untuk mengubah kamera</p>

                    <form action="{{route('admin.camera.upgrade', $camera->id)}}" method="POST" class="needs-validation"
                          enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('patch')
                        @include('flash::message')

                        <input type="hidden" name="camera_type" value="recognize">
                        <div class="form-group is-invalid">
                            <label for="name">Camera Name</label>
                            <input type="text" class="form-control  @if ($errors->has('name')) is-invalid @endif"
                                   placeholder="Camera Name" name="name"
                                   value="{{$camera->name}}" required>
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
                                   value="{{$camera->input_link}}" required>
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
                                      required>{{$camera->address}}</textarea>
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
                                   value="{{$camera->longitude}}">
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
                                   value="{{$camera->latitude}}">
                            @if ($errors->has('latitude'))
                                <span >
                                        {{ $errors->first('latitude') }}
                                    </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail</label>
                            <input type="file" class="form-control @if ($errors->has('thumbnail')) is-invalid @endif"
                                   placeholder="Upload Thumbnail" name="thumbnail"
                                   value="{{$camera->thumbnail}}">
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
