@extends('layouts.app')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                                <li class="breadcrumb-item active">Report</li>
                            </ol>
                        </div>
                </div>
            </div>

        </div>
        <!-- end page title -->

        <div class="row mb-n4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18"></h4>

                    <div class="page-title-right">
                        <div class="form-group align-items-sm-end">
                            <label>Filter Date</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="mm/dd/yyyy" data-provide="datepicker" name="filter-date" id="filterDate" data-date-autoclose="true">
                                    <div class="input-group-append ml-2">
                                        <button onclick="allReportTable()" id="search" class="btn btn-info">search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-n2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="report-table" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">No</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Jam</th>
                                    <th scope="col">View</th>
                                    <th scope="col">Pengunjung</th>
                                    <th scope="col">Laki - Laki</th>
                                    <th scope="col">Perempuan</th>
                                    <th scope="col">Avg. Umur</th>
                                    <th scope="col">Avg. Laki - Laki Umur</th>
                                    <th scope="col">Avg. Perempuan Umur</th>
                                </tr>
                                </thead>
                            </table>
                            @include('Partials.loading', ['idLoading' => 'loadingReportListTable', 'height' => 330])
                        </div>

                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
    @include('Partials.verification-modal')
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            allReportTable();
            $('#loadingReportListTable').hide();
        });
    </script>
@endsection
