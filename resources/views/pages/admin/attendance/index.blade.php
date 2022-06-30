@extends('layouts.app')
@section('css')
    <style>
        .img-hover-zoom img {
            transition: transform .5s ease;
        }
        .img-hover-zoom:hover img {
            transform: scale(9.5);
            position: sticky;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Attendance</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
                            </ol>
                        </div>
                </div>
            </div>

        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12 off-set-2">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-attendance" class="table table-hover table-nowrap mb-0">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 60px;">No</th>
                                    <th scope="col">Photo</th>
                                    <th scope="col">ID & Name</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Temperature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">First Detected</th>
                                    <th scope="col">Last Detected</th>
                                </tr>
                                </thead>
                            </table>
                            </table>
                            @include('Partials.loading', ['idLoading' => 'loadingAttendanceListTable', 'height' => 330])
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
            attendanceTable();
            $('#loadingAttendanceListTable').hide();
        });
    </script>
@endsection
