@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0 font-size-18">Dashboards</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">AGMS</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-size-14">Visitor Today</h5>
                            </div>
                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="dripicons-user-group"></i>
                                                </span>
                            </div>
                        </div>
                        <h4 class="m-0 align-self-center" id="total-visitor-current-daily">0</h4>
                        <p class="mb-0 mt-3 text-muted"><span class="text-info" id="total-visitor-difference-daily">0<i
                                    class="mdi mdi-trending-neutral mr-1"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-size-14">Visitor This Week</h5>
                            </div>
                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="dripicons-user-group"></i>
                                                </span>
                            </div>
                        </div>
                        <h4 class="m-0 align-self-center" id="total-visitor-current-weekly">0 </h4>
                        <p class="mb-0 mt-3 text-muted"><span class="text-info"
                                                              id="total-visitor-difference-weekly">0 <i
                                    class="mdi mdi-trending-neutral mr-1"></i></span></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-size-14">Visitor This Month</h5>
                            </div>
                            <div class="avatar-xs">
                                                <span class="avatar-title rounded-circle bg-primary">
                                                    <i class="dripicons-user-group"></i>
                                                </span>
                            </div>
                        </div>
                        <h4 class="m-0 align-self-center" id="total-visitor-current-monthly">0 </h4>
                        <p class="mb-0 mt-3 text-muted"><span class="text-info"
                                                              id="total-visitor-difference-monthly">0 <i
                                    class="mdi mdi-trending-neutral mr-1"></i></span></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <input type="hidden" id="countCameras" value="{{$cameras->count()}}">
            @foreach($cameras as $key => $camera)
                <input type="hidden" id="prefixPort{{$key+1}}" value="{{$camera->ws_port}}">
                <input type="hidden" id="ipStreamer{{$key+1}}" value="{{$camera->ws_url}}">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="header-title text-center">{{$camera->name}}</h5>
                            <canvas id="camera-preview-{{$key+1}}" class="card-img-top img-fluid" width="1280"
                                    height="720">
                                Your browser does not support the canvas element.
                            </canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    <!-- end row -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Line Chart Number of Visitors</h4>
                        <div class="row justify-content-center">
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <p>This Month</p>
                                    <h4 id="visitor-this-month">0 <span class="mdi mdi-human-greeting mr-1"></span></h4>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="text-center">
                                    <p>Last Month</p>
                                    <h4 id="visitor-last-month">0 <span class="mdi mdi-human-greeting mr-1"></span></h4>
                                </div>
                            </div>
                        </div>
                        @include('Partials.loading', ['idLoading' => 'loadingVisitorChart', 'height' => 330])
                        <div id="revenue-chart" class="apex-charts" dir="ltr">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- end row -->
        @stop
        @section('js')
            <script src="{{asset('assets/js/pages/core-page.js')}}"></script>
            <script>
                $(document).ready(function () {
                    streamCanvasDashboard();
                    visitorRate('daily');
                    visitorRate('weekly');
                    visitorRate('monthly');
                    visitorChart();
                    $('#loadingVisitorChart').show();
                    
                });
            </script>
            <script>
        
           cameras.forEach(function (camera) {
           var desc = $('#description-' + camera.id);
           var timestamp = $('#timestamp-' + camera.id);
           var canvas = document.getElementById(`canvas-${camera.id}`);
           var url = `ws://${camera.ws_url}:${camera.ws_port}0`;
           //- var url = `ws://159.89.206.10:${camera.ws_port}0`;
           var player = new JSMpeg.Player(url, { canvas: canvas, audio: false });
           timestamp.append(`[${moment().format('DD/MM/yyyy HH:mm:ss')}]`)
           desc.append('Belum ada notifikasi pesan baru')
         
           var card = $('#card-' + camera.id);
           //- var animate = $('#animate-' + camera.id);
           //- var classAnimate = 'animate-pulse'
           var successClassColors = ['animate-pulse', 'border-green-500', 'text-green-500']
           var failClassColors = ['animate-pulse', 'border-red-500', 'text-red-500']
         
           var wsc = new WebSocket(`ws://localhost:${camera.ws_port}4`);
           //- var wsc = new WebSocket(`ws://159.89.206.10:${camera.ws_port}4`);
           var timeout;
         
           function startTimeout() {
             timeout = setTimeout(function(){
               //- animate.removeClass(classAnimate);
               card.removeClass([...successClassColors, ...failClassColors]);
             }, 3000);
           }
         
           function stopTimeout() {
             clearTimeout(timeout);
           }
         
           wsc.onmessage = function (event) {
             const data = JSON.parse(event.data);
             if (data && data.type === 'notification') {
               //- timestamp.empty()
               //- timestamp.append(`[${moment().format('DD/MM/yyyy HH:mm:ss')}]`)
               stopTimeout();
               //- animate.addClass(classAnimate);
               if (data.status === 'success') {
                 card.addClass(successClassColors);
               } else {
                 card.addClass(failClassColors);
               }
               desc.empty()
               desc.append(`<span class="font-medium mr-1">[${moment(data.date).format('DD/MM/yyyy HH:mm:ss')}]</span> ${data.text}`)
               startTimeout();
             }
           }
            </script>
@endsection
