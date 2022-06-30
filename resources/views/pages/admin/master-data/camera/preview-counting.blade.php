@extends('layouts.app')
@section('content')
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
                            <div class="col-6">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">
                                            <input type="hidden" id="streamUrl" value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <input type="hidden" id="prefixPort" value="{{$camera->prefix_port}}">
                                            <input type="hidden" id="cameraId" value="{{$camera->id}}">
                                            <input type="hidden" id="coordinate" name="coordinate[]" value="">

                                            <div class="card-body">
                                                <canvas id="camera-preview" class="card-img-top img-fluid">
                                                    Your browser does not support the canvas element.
                                                </canvas>
                                            </div>
                                            <div class="card-footer">
                                                <h4 class="card-title font-size-16 mt-0 text-center">Preview Camera
                                                    Counting</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">

                                            <div class="card-body">
                                                <canvas id="draw-canvas" class="card-img-top img-fluid">
                                                    Your browser does not support the canvas element.
                                                </canvas>
                                            </div>
                                            <div class="card-footer">
                                                <h4 class="card-title font-size-16 mt-0 text-center">Draw Your Counting
                                                    Line</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('admin.cctv.index','counting')}}" type="button"
                           class="btn btn-success text-white float-left"><i
                                class="mdi mdi-face-recognition"></i> View All Camera
                            Counting</a>
                        <a href="{{route('admin.camera.index')}}" type="button"
                           class="btn btn-info text-white float-right" style="margin-left: 5px">Finish</a>
                        <button type="button" class="btn btn-primary text-white float-right" style="margin-left: 5px"
                                id="update-line-button">Update
                            Line
                        </button>
                        <button type="button" class="btn btn-warning text-white float-right" onclick="reload()">Clear
                            Line
                        </button>
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
            streamCanvas();
            setTimeout(screenshotCanvas, 5000);
        });
    </script>
@endsection
