@extends("frontend.layouts.dashboard_master")
@section('content')

<div class="user-panel vert-center-block">
    <div class="withdraw-address-block">
        <header class="withdraw-header d-flex align-items-center">
            <h2 class="flex-fill mr-2 mb-0"><i class="fas fa-file-alt"></i> Withdraws Address Management</h2>
            <span class="address-count">
                Address (0) <a class="ml-3" href="#"><i class="fa fa-angle-right"></i></a>
            </span>
        </header>
        <div class="withdraw-body">
            <h3>Whitelist</h3>
            <ul class="list-unstyled withdraw-list">
                <li class="d-flex">
                    <span class="white-list-text">When this function is turned on, your account will only be able to withdraw to whitelisted withdrawl addresses.</span>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </li>
            </ul>
        </div>
    </div>
</div><!-- .user-content -->

@endsection