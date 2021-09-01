<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{ url('') }}" class="logo" id="logo-tour">
            <img src="{{ URL::asset('public/assets/images/favicon.ico') }}" class="icon-c-logo" style="max-width: 28px">
            <span>MG Uplon</span></a>
    </div>
    <nav class="navbar navbar-custom">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <button class="button-menu-mobile open-left waves-light waves-effect">
                    <i class="fa fa-bars"></i>
                </button>
                <button class="button-menu-mobile toggle-fullscreen waves-light waves-effect">
                    <i class="fa fa-expand"></i>
                </button>
                <a class="button-menu-mobile waves-light waves-effect" href="/MGsis/" target="_blank">
			MGsis
                </a>
            </li>
            <!--
            <li class="nav-item hidden-mobile">
                <form role="search" class="app-search">
                    <input type="text" placeholder="Search..." class="form-control">
                    <a href=""><i class="fa fa-search"></i></a>
                </form>
            </li>
            -->
        </ul>

        <ul class="nav navbar-nav pull-right">
            <!--
            <li class="nav-item dropdown notification-list" id="notification-tour">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown"
                   href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="zmdi zmdi-notifications-none noti-icon"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-lg" aria-labelledby="Preview">
                    <!-- item ->
                    <div class="dropdown-item noti-title">
                        <h5>
                            <small><span class="label label-danger pull-xs-right">7</span>Notification</small>
                        </h5>
                    </div>

                    <!-- item ->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-success"><i class="icon-bubble"></i></div>
                        <p class="notify-details">Robert S. Taylor commented on Admin
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-info"><i class="icon-user"></i></div>
                        <p class="notify-details">New user registered.
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-danger"><i class="icon-like"></i></div>
                        <p class="notify-details">Carlos Crouch liked <b>Admin</b>
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- All->
                    <a href="javascript:void(0);" class="dropdown-item notify-item notify-all">
                        View All
                    </a>

                </div>
            </li>
            -->

            <!--
            <li class="nav-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-toggle="dropdown"
                   href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i class="zmdi zmdi-email noti-icon"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-arrow-success dropdown-lg"
                     aria-labelledby="Preview">
                    <!-- item->
                    <div class="dropdown-item noti-title bg-success">
                        <h5>
                            <small><span class="label label-danger pull-xs-right">7</span>Messages</small>
                        </h5>
                    </div>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-faded">
                            <img src="assets/images/users/avatar-2.jpg" alt="img" class="img-circle img-fluid">
                        </div>
                        <p class="notify-details">
                            <b>Robert Taylor</b>
                            <span>New tasks needs to be done</span>
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-faded">
                            <img src="assets/images/users/avatar-3.jpg" alt="img" class="img-circle img-fluid">
                        </div>
                        <p class="notify-details">
                            <b>Carlos Crouch</b>
                            <span>New tasks needs to be done</span>
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-faded">
                            <img src="assets/images/users/avatar-4.jpg" alt="img" class="img-circle img-fluid">
                        </div>
                        <p class="notify-details">
                            <b>Robert Taylor</b>
                            <span>New tasks needs to be done</span>
                            <small class="text-muted">1min ago</small>
                        </p>
                    </a>

                    <!-- All->
                    <a href="javascript:void(0);" class="dropdown-item notify-item notify-all">
                        View All
                    </a>

                </div>
            </li>
            -->

            <!--
            <li class="nav-item dropdown notification-list">
                <a class="nav-link waves-effect waves-light right-bar-toggle" href="javascript:void(0);">
                    <i class="zmdi zmdi-format-subject noti-icon"></i>
                </a>
            </li>
            -->

            <li class="nav-item dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light nav-user"
                   data-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                   <!--
                    <img src="assets/images/users/avatar-1.jpg" alt="user" class="img-circle">
                   -->
		   <i class="fa fa-user fa-lg"></i>

                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-arrow profile-dropdown "
                     aria-labelledby="Preview">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="text-overflow">
                            <small>Olá {{ Auth::user()->usuario }}</small>
                        </h5>
                    </div>

                    <!-- item-->
                    <a href="{{ url('usuario/' . Auth::user()->codusuario) }}" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-account-circle"></i> <span>Perfil</span>
                    </a>
                    <a href="{{ url('usuario/mudar-senha') }}" class="dropdown-item notify-item">
                        <i class="fa fa-exchange"></i> <span>Mudar senha</span>
                    </a>

                    <!--
                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-settings"></i> <span>Settings</span>
                    </a>

                    <!-- item->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="zmdi zmdi-lock-open"></i> <span>Lock Screen</span>
                    </a>
                    -->

                    <!-- item-->
                    <a href="{{ route('logout') }}" class="dropdown-item notify-item"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="zmdi zmdi-power"></i> <span>Sair</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>                    

                </div>
            </li>

        </ul>

    </nav>

</div>
<!-- Top Bar End -->
