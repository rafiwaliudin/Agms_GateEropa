@extends('layouts.app')
@section('content')

    <div id="webrtc-video" class="">
        <div class="video-player">
        </div>
    </div>

    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Camera</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master data</a></li>
                            <li class="breadcrumb-item">Camera</li>
                            <li class="breadcrumb-item active">Preview</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">
                                            <input type="hidden" id="streamUrl" value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <input type="hidden" id="prefixPort" value="{{$camera->prefix_port}}">
                                            <canvas id="camera-preview" class="card-img-top img-fluid">
                                                Your browser does not support the canvas element.
                                            </canvas>

                                            <div class="card-body">
                                                <h4 class="card-title font-size-16 mt-0 text-center">Preview Camera
                                                    Recognition</h4>
                                            </div>
                                            <div class="card-footer">
                                                <a href="{{route('admin.cctv.index','recognize')}}" type="button"
                                                   class="btn btn-success text-white float-left"><i
                                                        class="mdi mdi-face-recognition"></i> View All Camera
                                                    Recognize</a>
                                                <a href="{{route('admin.camera.index')}}" type="button"
                                                   class="btn btn-info text-white float-right">Finish</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>    
        $(document).ready(function () {

                // Symple client options
                CLIENT_OPTIONS = {
                url: 'https://symple.alfabeta.co.id',
                secure: true,
                peer: {
                    user: "{{$camera->input_link}}",
                    name: 'Advision User',
                    group: 'public'
                    }
                }
            prepareSympleClient(CLIENT_OPTIONS);

            streamCanvas();
        });
    </script>
@endsection
