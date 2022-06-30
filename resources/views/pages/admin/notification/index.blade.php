@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Report Notification</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                        <li class="breadcrumb-item active">Report Notification</li>
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
                    <div class="table-responsive">
                        <table id="table-notification" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Action Data</th>
                                    <th class="text-center">Message</th>
                                    <th class="text-center">Location</th>
                                    <th class="text-center">Status Camera</th>
                                    <th class="text-center">Time</th>
                                </tr>
                            </thead>
                        </table>
                        @include('Partials.loading', ['idLoading' => 'loadingNotificationListTable', 'height' => 330])
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/js/pages/core-page.js')}}"></script>
<script>
    $(document).ready(function () {
            notificationTable();
            $('#loadingNotificationListTable').show();
        });
</script>
@endsection
