@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Chart</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                                <li class="breadcrumb-item active">Chart</li>
                            </ol>
                        </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row mb-n4">
            <div class="col-12">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                
                                <h4 class="header-title">Kawasan Donut chart</h4>
                                <p class="card-title-desc">Chart Total Kawasan.</p>

                                <canvas id="donut-chart"></canvas>
                
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-body">
                
                                <h4 class="header-title">Kawasan Pie chart</h4>
                                <p class="card-title-desc">Chart Total Kawasan</p>

                                <canvas id="pieChart"></canvas>
                
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div>
            </div>
        </div>
        <div class="row mt-n2">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
@stop
@section('js')
<script src="{{asset('assets/js/Chart.bundle.min.js')}}"></script>
<script>
    var pieChartCanvas = $("#donut-chart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: {
                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        @foreach($chart as $data)
                            '{{ $data->nama_kawasan }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($chart as $data)
                                {{ $data->jumlah }},
                            @endforeach
                        ],
                        backgroundColor: ['#3d8ef8','#7c8a96','#11c46e','#f1b44c'],
                        borderColor: ['#3d8ef8','#7c8a96','#11c46e','#f1b44c']
                    }]
                },
                options: {
                    responsive: true,
                    cutoutPercentage: 70,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
</script>
<script>
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: [
                            @foreach($chart as $data)
                                {{ $data->jumlah }},
                            @endforeach
                        ],
                        backgroundColor: [
                            '#0db4d6',
                            '#f1b44c',
                            '#fb4d53',
                            '#343a40'
                        ],
                        borderColor: [
                            '#0db4d6',
                            '#f1b44c',
                            '#fb4d53',
                            '#343a40'
                        ],
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        @foreach($chart as $data)
                            '{{ $data->nama_kawasan }}',
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
</script>
@endsection

