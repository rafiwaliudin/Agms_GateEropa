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
                                <div class="input-daterange input-group" data-provide="datepicker">
                                    <input type="text" class="datepicker form-control mr-2" name="start-date" id="startDate" />
                                    <input type="text" class="datepicker form-control ml-2" name="end" id="endDate"/>
                                    <div class="input-group-append ml-2">
                                        <button data-target="#download-type-modal" data-toggle="modal" id="downloadReport" class="btn btn-info">Download Report</button>
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
                            <table id="report-download-table" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
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
    @include('Partials.download-type-modal')
@stop
@section('js')
    <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            allReportDownloadTable();
            $('#loadingReportListTable').hide();

            $.each($('.datepicker'), function() {
                $(this).datepicker({
                    dateFormat: "dd MM yy",
                    minDate: new Date('-1m'),
                    maxDate: new Date(), // max date is ToDay, current date;
                });
            });
        });
    </script>
@endsection
