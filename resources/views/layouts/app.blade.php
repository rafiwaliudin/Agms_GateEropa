<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard | AGMS - Try an amazing experience in this application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/agms-thumbnail.png')}}">

    <!-- slick css -->
    <link href="{{asset('assets/libs/slick-slider/slick/slick.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/libs/slick-slider/slick/slick-theme.css')}}" rel="stylesheet" type="text/css" />

    <!-- jvectormap -->
    <link href="{{asset('assets/libs/jqvmap/jqvmap.min.css')}}" rel="stylesheet" />

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link href="{{asset('assets/libs/token-field/css/bootstrap-tokenfield.min.css')}}" rel="stylesheet" />

    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Data tables Css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/data-tables/datatables.min.css')}}" />
    <link rel="stylesheet" type="text/css"
        href="{{asset('https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css')}}" />
    <link rel="stylesheet" type="text/css"
        href="{{asset('https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css')}}" />

    <!-- Fancybox Css-->
    <link rel="stylesheet" type="text/css"
        href="{{asset('assets/libs/fancybox-master/dist/jquery.fancybox.min.css')}}" />

    <!-- Symple Lib -->
    <script type="text/javascript" src="{{asset('assets/libs/symple/socket.io.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/libs/symple/symple.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/libs/symple/symple.client.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/libs/symple/symple.player.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/libs/symple/symple.player.webrtc.js')}}"></script>

</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            @include('Includes.topbar')
        </header>

        <!-- ========== Left Sidebar Start ========== -->

        <?php
$recognize = \App\Camera::where('camera_type_id', 1)->count();
$counting = \App\Camera::where('camera_type_id', 2)->count();
$masking = \App\Camera::where('camera_type_id', 3)->count();
?>
        @include('Includes.left-sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div id="soundNotification"></div>
            <!-- Page-content -->
            <div class="page-content">
                @include('flash::message')
                @yield('content')
            </div>
            <!-- End Page-content -->
            <!-- Footer Content -->
            @include('Includes.footer')
            <!-- End Footer Content -->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="{{asset('assets/libs/token-field/bootstrap-tokenfield.min.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <script src="{{asset('assets/libs/slick-slider/slick/slick.min.js')}}"></script>

    <!-- Jq vector map -->
    <script src="{{asset('assets/libs/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('assets/libs/jqvmap/maps/jquery.vmap.usa.js')}}"></script>

    <!-- Required datatable js -->
    <script src="{{asset('assets/libs/data-tables/datatables.min.js')}}"></script>

    <script src="{{asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <!-- jsmpeg -->
    <script src="{{asset('assets/jsmpeg/jsmpeg.min.js')}}"></script>

    <script src="//js.pusher.com/3.1/pusher.min.js"></script>

    @yield('js')

    <!-- fancybox -->
    <script src="{{asset('assets/libs/fancybox-master/dist/jquery.fancybox.min.js')}}"></script>

    <script src="{{asset('assets/js/app.js')}}"></script>

    <script>
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    </script>

    <script type="text/javascript">
        var notificationsWrapper = $('.dropdown-notifications');
    var notificationsCount = parseInt($('#countNotification').text());
    var notifications = notificationsWrapper.find('#dataNotification');

    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: '{{env('PUSHER_APP_CLUSTER')}}',
        encrypted: true
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('notification-was-created');

    // Bind a function to a Event (the full Laravel class)
    channel.bind('App\\Events\\NotificationWasCreated', function (data) {
        playSoundNotification();
        var existingNotifications = notifications.html();
        var newNotificationHtml = `
           <a href="#" class="text-reset notification-item">
            <div class="media">
                <div class="avatar-xs mr-3">
                    <span class="avatar-title bg-primary rounded-circle font-size-16"><i class="mdi mdi-information-outline"></i></span>
                </div>
                <div class="media-body"><h6 class="mt-0 mb-1">` + data.action + `</h6>
                    <div class="font-size-12 text-muted">
                        <p class="mb-1">` + data.message + `</p>
                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> a minutes ago</p>
                    </div>
                </div>
            </div>
           </a>
           `;
        notifications.html(newNotificationHtml + existingNotifications);

        notificationsCount += 1;
        $('#countNotification').html('<div id="countNotification">' + notificationsCount + '</div>');
        $('#countNotificationNew').html('<div id="countNotificationNew">' + notificationsCount + '</div>');
    });
    </script>

</body>

</html>
