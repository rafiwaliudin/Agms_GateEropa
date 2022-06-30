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
                            <div class="col-10">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">
                                            <input type="hidden" id="streamUrl" value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <input type="hidden" id="prefixPort" value="{{$camera->prefix_port}}">
                                            <input type="hidden" id="camera-id" value="{{$camera->id}}">
                                            <div class="card-body">
                                                <canvas id="camera-preview" class="card-img-top img-fluid">
                                                    Your browser does not support the canvas element.
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">

                                            <div class="card-body">
                                                <img class="img-fluid mx-auto d-block rounded" src="{{asset('assets/uploads/camera/'.$camera->thumbnail)}}" alt="">
                                            </div>
                                            <div class="card-footer">
                                                <h6 class="text-center"><span class="mdi mdi-comment-eye"></span> {{$camera->impression}} Impresi</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Chart Counting Tally</h4>
                        <div class="row justify-content-center">
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <p>Real Counting</p>
                                    <h4 id="tally-real">0 <span class="mdi mdi-counter mr-1"></span></h4>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <p>Accumulate Counting</p>
                                    <h4 id="tally-accumulate">0 <span class="mdi mdi-counter mr-1"></span></h4>
                                </div>
                            </div>
                        </div>
                        @include('Partials.loading', ['idLoading' => 'loadingTallyChart', 'height' => 330])
                        <div id="tally-chart" class="apex-charts" dir="ltr">
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
            streamCanvas();
            // tallyChart();
            impression({{$camera->id}});
        });
    </script>
@endsection
