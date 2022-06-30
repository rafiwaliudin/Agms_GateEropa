@extends('layouts.app')
@section('content')
    <div class="row">
        @foreach($cameras as $camera)
{{--            <script>--}}
{{--                $(document).ready(function () {--}}
{{--                    impression({{$camera->id}});--}}
{{--                });--}}
{{--            </script>--}}
            <div class="col-lg-3 col-md-6">
                <div class="gallery-box text-center card p-2">
                    <a href="{{route('admin.cctv.detail',[$camera->cameraType->name,$camera->id])}}"
                       class="text-dark gallery-popup">
                        <div class="position-relative gallery-content">
                            <div class="demo-img align-center">
                                @if($camera->thumbnail)
                                    <img src="{{asset('assets/uploads/camera/'.$camera->thumbnail)}}"
                                         class="img-fluid mx-auto d-block rounded">
                                @else
                                    <img src="{{asset('assets/images/camera-thumbnail.jpg')}}"
                                         class="img-fluid mx-auto d-block rounded">
                                @endif
                            </div>
{{--                            @if ($camera->id_proc)--}}
                                <div class="gallery-overlay">
                                    <div class="gallery-overlay-icon">
                                        <i class="mdi mdi-comment-eye text-white"> {{$camera->impression}}</i>
                                    </div>
                                </div>
                                <div class="overlay-content">
                                    <h5 class="font-size-14 text-truncate mb-0">CAMERA ON</h5>
                                </div>
{{--                            @else--}}
{{--                                <div class="gallery-overlay" style="background: red">--}}
{{--                                    <div class="gallery-overlay-icon">--}}
{{--                                        <i class="mdi mdi-comment-eye text-white"> {{$camera->impression}}</i>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="overlay-content">--}}
{{--                                    <h5 class="font-size-14 text-truncate mb-0">CAMERA OFF</h5>--}}
{{--                                </div>--}}
{{--                            @endif--}}
                            <div class="card-footer">
                                {{$camera->name}}
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
@endsection
