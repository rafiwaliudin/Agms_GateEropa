@extends('layouts.app')
@section('content')

<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Gate</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master data</a></li>
                        <li class="breadcrumb-item active">Gate</li>
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
                        <a href="{{route('admin.residential-gate.create')}}" type="button"
                            class="btn btn-info btn-lg waves-effect waves-light float-right">Tambah
                            Gate
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="table-residential-gate" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Longitude</th>
                                    <th>Latitude</th>
                                    <th>Cluster</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        @include('Partials.loading', ['idLoading' => 'loadingResidentialGateListTable','height' => 330])
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
            residentialGateTable();
            $('#loadingResidentialGateListTable').show();
        });

        $(document).on("click", ".delete-button", function () {
            var parseId = $('#id').val($(this).data('id'));
            var parseRoute = $('#route').val($(this).data('route'));
        });
</script>
@endsection
