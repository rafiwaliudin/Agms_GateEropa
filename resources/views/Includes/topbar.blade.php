<div class="navbar-header">
    <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <a href="/" class="logo logo-light">
                <span class="logo-lg">
                                    <img src="{{asset('assets/images/agms-logo.png')}}" alt="" width="150"
                                         style="margin-top:15px">
                                </span>
            </a>
            <a href="/" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/agms-thumbnail.png')}}" alt="" height="22">
                                </span>
            </a>
        </div>

        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect"
                id="vertical-menu-btn" style="margin-top:10px">
            <i class="mdi mdi-backburger"></i>
        </button>
    </div>

    <div class="d-flex">
        <div class="dropdown d-inline-block d-lg-none ml-2">
            <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-search-dropdown"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="mdi mdi-magnify"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                 aria-labelledby="page-header-search-dropdown">

                <form class="p-3">
                    <div class="form-group m-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search ..."
                                   aria-label="Recipient's username">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="dropdown d-none d-lg-inline-block ml-1">
            <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                <i class="mdi mdi-fullscreen"></i>
            </button>
        </div>
            <div class="dropdown d-inline-block dropdown-notifications">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="mdi mdi-bell-outline"></i>
                    @if (auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge badge-danger badge-pill">
                            <div id="countNotification">{{ auth()->user()->unreadNotifications->count() }}</div>
                        </span>
                    @endif

                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 font-weight-medium text-uppercase"> Notifications </h6>
                            </div>
                            <div class="col-auto">

                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <span class="badge badge-pill badge-danger">New <div id="countNotificationNew">
                                            {{ auth()->user()->unreadNotifications->count() }}
                                        </div></span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div data-simplebar="init" style="max-height: 230px;">
                        <div class="simplebar-wrapper" style="margin: 0px;">
                            <div class="simplebar-height-auto-observer-wrapper">
                                <div class="simplebar-height-auto-observer"></div>
                            </div>
                            <div class="simplebar-mask">
                                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                    <div class="simplebar-content-wrapper"
                                        style="height: auto; overflow: hidden scroll;">
                                        <div class="simplebar-content" style="padding: 0px;" id="dataNotification">
                                            @foreach (auth()->user()->unreadNotifications()->take(10)->get() as $notification)
                                                <a href="#" class="text-reset notification-item">
                                                    <div class="media">
                                                        <div class="avatar-xs mr-3">
                                                            <span
                                                                class="avatar-title bg-primary rounded-circle font-size-16">
                                                                <i class="mdi mdi-information-outline"></i>
                                                            </span>
                                                        </div>
                                                        <div class="media-body">
                                                            <h6 class="mt-0 mb-1">{{ $notification->data['action'] }}
                                                            </h6>
                                                            <div class="font-size-12 text-muted">
                                                                <p class="mb-1">{{ $notification->data['message'] }}</p>
                                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i>
                                                                    {{ \Carbon\Carbon::createFromTimeStamp(strtotime($notification->created_at))->diffForHumans() }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="simplebar-placeholder" style="width: auto; height: 372px;"></div>
                        </div>
                        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                        </div>
                        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                            <div class="simplebar-scrollbar"
                                style="height: 142px; display: block; transform: translate3d(0px, 88px, 0px);"></div>
                        </div>
                    </div>

                    <div class="p-2 border-top">
                        <a class="btn-link btn btn-block text-center" href="{{ route('admin.notification.index') }}">
                            <i class="mdi mdi-arrow-down-circle mr-1"></i> Load More..
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{asset('assets/images/users/avatar-1.jpg')}}"
                         alt="Header Avatar">
                    <span
                        class="d-none d-sm-inline-block ml-1">{{\Illuminate\Support\Facades\Auth::user()->name}}</span>
                    <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();"><i
                            class="mdi mdi-logout font-size-16 align-middle mr-1"></i>
                        Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
    </div>
</div>
