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
                            <li class="breadcrumb-item active">Camera</li>
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
                        <div class="header-item">
{{--                            <button type="button" data-target="#camera-type-modal" data-toggle="modal"--}}
{{--                                    class="btn btn-info btn-lg waves-effect waves-light ">Tambah--}}
{{--                                Camera--}}
{{--                            </button>--}}
{{--                            <a href="{{route('admin.camera.create','recognize')}}" type="button" class="btn btn-info btn-lg waves-effect waves-light ">Tambah--}}
{{--                                Camera--}}
{{--                            </a>--}}
{{--                            <button id="stop-button" type="button" onclick="stopCameraAll()"--}}
{{--                                    class="btn btn-danger btn-lg waves-effect waves-light float-right">Stop All--}}
{{--                                Camera--}}
{{--                            </button>--}}
                        </div>
                        <div class="table-responsive">
                            <table id="table-camera" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Link Camera</th>
                                    <th>Prefix Port</th>
                                    <th>Thumbnail</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            @include('Partials.loading', ['idLoading' => 'loadingCameraListTable', 'height' => 330])
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
    @include('Partials.verification-modal')
    @include('Partials.camera-type-modal')
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            cameraTable();
            $('#loadingCameraListTable').show();
        });
        $(document).on("click", ".delete-button", function () {
            var parseId = $('#id').val($(this).data('id'));
            var parseRoute = $('#route').val($(this).data('route'));
        });
    </script>
@endsection
