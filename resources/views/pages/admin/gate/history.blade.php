@extends('layouts.app')
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Gate History</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                                <li class="breadcrumb-item active">Gate History</li>
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
                                    <input type="text" class="form-control mr-2" name="start-date" id="startDate" placeholder="Pilih tanggal"/>
                                    <input type="text" class="form-control ml-2" name="end" id="endDate" placeholder="Pilih Tanggal"/>
                                    <div class="input-group-append ml-2">
                                        <button onclick="gateHistoryTable()" id="search" class="btn btn-info">search</button>
                                    </div>
                                </div>
                                
                            </div>
                            <label>Export File</label>
                            <div>
                                <div class="input-daterange input-group" data-provide="datepicker">
                                    <input type="text" class="form-control mr-2" name="start-date" id="startDateExport" placeholder="Pilih tanggal"/>
                                    <input type="text" class="form-control ml-2" name="end" id="endDateExport" placeholder="Pilih Tanggal"/>
                                    <div class="input-group-append ml-2">
                                        <button onclick="openExport(0)" id="search" class="btn btn-info">Export PDF</button>
                                    </div>
                                    <div class="input-group-append ml-2">
                                        <button onclick="openExport(1)" id="search" class="btn btn-info">Export CSV</button>
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
                            <table id="gate-history-table" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">No</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Plat Nomor</th>
                                    <th scope="col">Kawasan</th>
                                    <th scope="col">Cluster</th>
                                    <th scope="col">Gate</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Timestamp</th>
                                </tr>
                                </thead>
                            </table>
                            @include('Partials.loading', ['idLoading' => 'loadingGateHistoryListTable', 'height' => 330])
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
            gateHistoryTable();
            $('#loadingGateHistoryListTable').show();
            // $.ajax({
            // type: "POST",
            // beforeSend: function(request) {
            //     request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr("content"));
            // },
            // url: "/gate/history/list",
            // data: {
            //         start   : 0,
            //         length : 10,
            //         startDate: "02/04/2022",
            //         endDate: "02/04/2022"
            //     },
            // success: function(res){
            //     console.log(res);
            // },
            // dataType : "json"
            
            // });
            
        });
    </script>
@endsection
