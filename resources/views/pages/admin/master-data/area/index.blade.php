@extends('layouts.app')
@section('content')

    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Area</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master data</a></li>
                            <li class="breadcrumb-item active">Area</li>
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
                            <a href="{{route('admin.area.create')}}" type="button"
                               class="btn btn-info btn-lg waves-effect waves-light float-right">Tambah
                                Kawasan
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table id="table-area" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                            @include('Partials.loading', ['idLoading' => 'loadingAreaListTable','height' => 330])
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('Partials.verification-modal')
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            areaTable();
            $('#loadingAreaListTable').show();
        });

        $(document).on("click", ".delete-button", function () {
            var parseId = $('#id').val($(this).data('id'));
            var parseRoute = $('#route').val($(this).data('route'));
        });
    </script>
@endsection
