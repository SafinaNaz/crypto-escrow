<header>
    <nav class="navbar navbar-expand-lg navbar-light site-nav fixed" role="navigation">
        <div class="container-fluid pad_0">
            <!-- Brand and toggle get grouped for better mobile display -->
            <button class="navbar-toggler" type="button">
                <span class="navbar-toggler-icon">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </span>
            </button>
            <div class="topbar-action d-none d-lg-block nav-top collapse navbar-collapse" id="navbar-main">
                <ul class="topbar-action-list navbar-nav nav-mid ml-auto">


                    <li class="dropdown topbar-action-item topbar-action-user">
                        <a href="javascript:void(0)" data-toggle="dropdown">
                            <img loading="lazy" class="icon" src="{!! checkProfileImage(Auth::user()->user_type) !!}" alt="{{ Auth::user()->username }}">
                            {!! Auth::user()->full_name() !!}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="user-dropdown">
                                <div class="user-dropdown-head">

                                    <h6 class="user-dropdown-name"> {!!Auth::user()->full_name()!!}</h6>
                                    <span class="user-dropdown-email">{{ Auth::user()->email }}</span>
                                </div>
                                <ul class="user-dropdown-links">
                                    <li><a href="{{route('profile')}}"><i class="ti ti-id-badge"></i>My Profile</a></li>
                                    <li><a href="{{route('change-password')}}"><i class="ti ti-lock"></i>Password Settings</a></li>
                                    <li><a href="{{ route('logout') }}"><i class="ti ti-power-off"></i>Logout</a></li>

                                </ul>
                            </div>
                        </div>
                    </li><!-- .topbar-action-item -->

                    <li style="margin-left: 10px;"><a class="btn btn-danger btn-sm" href="{{ route('logout') }}"><i class="ti ti-power-off"></i>Logout</a></li>
                </ul><!-- .topbar-action-list -->
            </div>
            <!-- .topbar-action -->
        </div>
    </nav>
</header>
<!-- for mobile view -->
<div class="topbar-action nav-top mbl-topbar" id="navbar-main">
    <ul class="topbar-action-list navbar-nav nav-mid ml-auto">
        <li class="dropdown topbar-action-item topbar-action-user">
            <a href="javascript:void(0)" data-toggle="dropdown">
                <img loading="lazy" class="icon" src="{!! checkProfileImage(Auth::user()->user_type) !!}" alt="{{ Auth::user()->username }}">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="user-dropdown">
                    <div class="user-dropdown-head">

                        <h6 class="user-dropdown-name"> {!!Auth::user()->full_name()!!}</h6>
                        <span class="user-dropdown-email">{{ Auth::user()->email }}</span>
                    </div>
                    <ul class="user-dropdown-links">
                        <li><a href="{{route('profile')}}"><i class="ti ti-id-badge"></i>My Profile</a></li>
                        <li><a href="{{route('change-password')}}"><i class="ti ti-lock"></i>Password Settings</a></li>
                        <li><a href="{{ route('logout') }}"><i class="ti ti-power-off"></i>Logout</a></li>

                    </ul>
                </div>
            </div>
        </li><!-- .topbar-action-item -->
    </ul><!-- .topbar-action-list -->
</div>