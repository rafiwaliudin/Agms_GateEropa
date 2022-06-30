@extends('layouts.app')
@section('content')
    <div id="webrtc-video" class="">
        <div class="video-player">
        </div>
    </div>

    <div id="soundWelcome"></div>

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
                            <div class="col-7">
                                <div class="card-deck-wrapper">
                                    <div class="card-deck">
                                        <div class="card">
                                            <input type="hidden" id="streamUrl"
                                                   value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <input type="hidden" id="prefixPort" value="{{$camera->prefix_port}}">
                                            <input type="hidden" id="camera-id" value="{{$camera->id}}">
                                            <input type="hidden" id="dataPort" value="{{$camera->prefix_port + 2}}">
                                            <input type="hidden" id="dataUrl" value="ws://{{env('IP_STREAMER_MASK')}}:">
                                            <div class="card-body">
                                                <canvas id="camera-preview"
                                                        class="card-img-top img-fluid mx-auto d-block rounded">
                                                    Your browser does not support the canvas element.
                                                </canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <!-- <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title text-center">Data Profile</h4>
                                                <div class="table-responsive">
                                                    <table class="table mb-0">
                                                        <thead>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>NIK</td>
                                                                <td id="nik"></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-6">
                                        <h4 class="card card-header header-title text-center">Face Detection</h4>
                                        <div class="card-deck-wrapper">
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <img class="img-fluid mx-auto d-block rounded" id="imgFace"
                                                             src="{{asset('assets/images/head-default.png')}}"
                                                             style="width: 100%">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--                                        <div class="card-deck-wrapper">--}}
                                        {{--                                            <div class="card-deck">--}}
                                        {{--                                                <div class="card">--}}
                                        {{--                                                    <div class="card-body text-center">--}}
                                        {{--                                                        <button type="button" onclick="onNoteClick()" data-toggle="modal" class="btn btn-info btn-sm waves-effect waves-light "> Tambah--}}
                                        {{--                                                            Catatan--}}
                                        {{--                                                        </button>--}}
                                        {{--                                                    </div>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="header-title text-center">Data Detail Profile</h4>
                                                    <div class="table-responsive">
                                                        <table class="table mb-0">
                                                            <thead>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Name</td>
                                                                <td id="namePerson"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Gender</td>
                                                                <td id="Gender"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Age</td>
                                                                <td id="Age"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Emotion</td>
                                                                <td id="Emotion"></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--                                            <div class="card">--}}
                                            {{--                                                <div class="card-body">--}}
                                            {{--                                                    <h4 class="header-title text-center">Catatan Customer</h4>--}}
                                            {{--                                                    <div class="table-responsive">--}}
                                            {{--                                                        <table class="table mb-0">--}}
                                            {{--                                                            <thead>--}}
                                            {{--                                                            </thead>--}}
                                            {{--                                                            <tbody>--}}
                                            {{--                                                                <tr>--}}
                                            {{--                                                                    <td id="personNotes"></td>--}}
                                            {{--                                                                </tr>--}}
                                            {{--                                                            </tbody>--}}
                                            {{--                                                        </table>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}
                                            {{--                                            </div>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-body">
                            <h4 class="card-title text-center font-size-16 mt-0">Face History</h4>
                            <div class="row">
                                <div class="col-lg-1 mt-4 mdi mdi-face-recognition" style="font-size: 30px;"></div>
                                @for ($i = 1; $i < 12 ; $i++)
                                    <div class="col-lg-1 mt-3">
                                        <img src="{{$images[$i - 1]}}" id="img{{$i}}"
                                             class="img-fluid mx-auto d-block rounded">
                                    </div>
                                @endfor
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Partials.add-note-modal')
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            streamCanvas();
            dataFaceRecognize();

            // Symple client options
            {{--    CLIENT_OPTIONS = {--}}
            {{--    url: 'https://symple.alfabeta.co.id',--}}
            {{--    secure: true,--}}
            {{--    peer: {--}}
            {{--        user: "{{$camera->input_link}}",--}}
            {{--        name: 'Advision User',--}}
            {{--        group: 'public'--}}
            {{--        }--}}
            {{--    }--}}
            {{--prepareSympleClient(CLIENT_OPTIONS);--}}

        });

        function onNoteClick() {
            $("#add-note-modal").modal("show");

            $('#capture').attr('src', $('#imgFace').attr('src'));
            $('#captureFile').val($('#capture').attr('src'));
        }
    </script>
@endsection
