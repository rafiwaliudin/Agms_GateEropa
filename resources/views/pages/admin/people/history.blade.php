@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Vehicle Counting History</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                                <li class="breadcrumb-item active">Vehicle Counting History</li>
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
                                    <input type="text" class="form-control mr-2" name="start-date" id="startDate" />
                                    <input type="text" class="form-control ml-2" name="end" id="endDate"/>
                                    <div class="input-group-append ml-2">
                                        <button onclick="vehicleCountingHistoryTable()" id="search" class="btn btn-info">search</button>
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
                            <table id="people" class="display nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">No</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">License </th>
                                    <th scope="col">Occupant Id </th>
                                    <th scope="col">Cluster</th>
                                    <th scope="col">Plat Nomor</th>
                                    <th scope="col">Kawasan</th>
                                    <th scope="col">Cluster</th>
                                    <th scope="col">Time Stamp</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($list_people as $result)
                                    <tr>
                                        <td>{{ $result->id }}</td>
                                        <td>{{ $result->image }}</td>
                                        <td>{{ $result->license_plate }}</td>
                                        <td>{{ $result->occupant_id }}</td>
                                        <td>{{ $result->visitor_id }}</td>
                                        <td>{{ $result->residential_gate_id }}</td>
                                        <td>{{ $result->gate_number }}</td>
                                        <td>{{ $result->gate_type }}</td>
                                        <td>{{ $result->create_at }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- @include('Partials.loading', ['idLoading' => 'loadingVehicleCountingHistoryListTable', 'height' => 330]) --}}
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
    {{-- @include('Partials.verification-modal') --}}
@stop
@section('js')
    {{-- <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
    <script>
        $(document).ready(function () {
            vehicleCountingHistoryTable();
            $('#loadingVehicleCountingHistoryListTable').show();
        });
    </script> --}}
    <script type="text/javascript">
    $(document).ready(function(){
         $('#people').DataTable(
        //      {
        //     processing: true,
        //     serverSide: true,
        //     ajax : {
        //         url: '{{ route('admin.people.history') }}',
        //         type: 'GET'
        //     },
        //     columns: [
        //      { data: 'id',name:'id' },
        //     { data: 'image' },
        //     { data: 'license_plate' },
        //     { data: 'area' },
        //     { data: 'cluster' },
        //     { data: 'residentialGate' },
        //     { data: 'status' },
        //     { data: 'timestamp' },
        //     ],
        //     order: [[0,'asc']]
        //  }
         );
    });

    
    </script>
@endsection
