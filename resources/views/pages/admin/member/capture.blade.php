@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Capture</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master data</a></li>
                            <li class="breadcrumb-item">Guest</li>
                            <li class="breadcrumb-item active">Capture</li>
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
                                            <input type="hidden" id="dataPort" value="{{$camera->prefix_port}}2">
                                            <input type="hidden" id="dataUrl" value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <input type="hidden" id="member-id" value="{{$member->id}}">

                                            <div class="card-body">
                                                <canvas id="camera-preview" class="card-img-top img-fluid">
                                                    Your browser does not support the canvas element.
                                                </canvas>
                                            </div>
                                            <div class="card-footer">
                                                <h4 class="card-title font-size-16 mt-0 text-center">Preview Capture
                                                    Camera</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title text-center">Data Detail
                                                    Profile</h4>
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Phone</th>
    <?
                                                            /* <th>Gender</th> */
                                                            /* <th>Email</th> */
    ?>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>{{$member->name}}</td>
                                                            <td>{{$member->phone}}</td>
<?
                                                            /* <td>{{$member->gender->name}}</td> */
                                                            /* <td>{{$member->email}}</td> */
?>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
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
                                                <input id="capture1File" type="hidden" name="capture1File" value="">
                                                <input id="capture2File" type="hidden" name="capture2File" value="">
                                                <input id="capture3File" type="hidden" name="capture3File" value="">
                                                <input id="capture4File" type="hidden" name="capture4File" value="">
                                                <div class="row">
                                                    <div class="card card-body col-6">
                                                        <img class="img-fluid mx-auto d-block rounded"
                                                             src="{{asset('assets/images/head-default.png')}}"
                                                             id="capture1" alt="">
                                                    </div>
                                                    <div class="card card-body col-6">
                                                        <img class="img-fluid mx-auto d-block rounded"
                                                             src="{{asset('assets/images/head-default.png')}}"
                                                             id="capture2" alt="">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="card card-body col-6">
                                                        <img class="img-fluid mx-auto d-block rounded"
                                                             src="{{asset('assets/images/head-default.png')}}"
                                                             id="capture3" alt="">
                                                    </div>
                                                    <div class="card card-body col-6">
                                                        <img class="img-fluid mx-auto d-block rounded"
                                                             src="{{asset('assets/images/head-default.png')}}"
                                                             id="capture4" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('admin.member.index')}}" type="button"
                           class="btn btn-success text-white float-left"><i
                                class="mdi mdi-face-recognition"></i> View All Guest</a>
                        <button type="button" class="btn btn-primary text-white waves-effect waves-light float-right" style="margin-left: 5px" id="updatePhotoButton"
                                onclick="updatePhotoMember()">Upload
                            Photo
                        </button>
                        <button type="button" class="btn btn-warning text-white float-right" onclick="reload()">Clear
                            Face
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
            dataCapture();
        });
    </script>
@endsection
