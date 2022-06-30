<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{route('admin.dashboard.index')}}" class="waves-effect">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @role('Administrator')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-folder-settings-variant-outline"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{route('admin.user.index')}}">Pengguna</a></li>
                        <li><a href="{{route('admin.employee.index')}}">Pegawai</a></li>
                        <li><a href="{{route('admin.camera.index')}}">Kamera</a></li>
                        <li><a href="{{route('admin.vehicle.index')}}">Kendaraan</a></li>
                        <li><a href="{{route('admin.area.index')}}">Kawasan</a></li>
                        <li><a href="{{route('admin.cluster.index')}}">Cluster</a></li>
                        <li><a href="{{route('admin.residential-gate.index')}}">Gate</a></li>
                    </ul>
                </li>
                @endrole
                <li>
                    <a href="{{route('admin.visitor.index')}}" class="waves-effect">
                        <i class="mdi mdi-human-greeting"></i>
                        <span>Visitor</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.gate.history')}}" class="waves-effect">
                        <i class="mdi mdi-gate-open"></i>
                        <span>Open Gate History</span>
                    </a>
                </li>
                <!-- <li>
                    <a href="{{route('admin.vehicle_counting.history')}}" class="waves-effect">
                        <i class="mdi mdi-car"></i>
                        <span>Vehicle Counting</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.people_counting.history')}}" class="waves-effect">
                        <i class="mdi mdi-account"></i>
                        <span>People Counting</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.intruder_counting.history')}}" class="waves-effect">
                        <i class="mdi mdi-color-helper"></i>
                        <span>Intruder Counting</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a href="{{route('admin.chart.history')}}" class="waves-effect">
                        <i class="mdi mdi-chart-arc"></i>
                        <span>Chart</span>
                    </a>
                </li> -->
                <!-- <li>
                    <a href="{{route('admin.camera-management.index')}}" class="waves-effect">
                        <i class="mdi mdi-camera"></i>
                        <span>Camera Management</span>
                    </a>
                </li> -->
                <li>
                    <a href="{{route('admin.notification.index')}}" class="waves-effect">
                        <i class="mdi mdi-bell"></i>
                        <span>Notification</span>
                    </a>
                </li>
            </ul>
            <!-- Left Menu End -->
        </div>
        <!-- Sidebar -->
    </div>
</div>
