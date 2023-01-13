@php
$segment2 = Request::segment(2);
$segment3 = Request::segment(3);
@endphp
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/admin') }}" class="brand-link">
        <img src="{{ _asset('frontend/assets/img/favicon.png') }}" alt="{{SITE_NAME}}" class="brand-image img-circle elevation-3" style="opacity: .8">

        <span class="brand-text font-weight-light">{{SITE_NAME}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{!! checkImage(asset('storage/uploads/admins/'.Auth::user()->id.'/'.Auth::user()->photo)) !!}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ url('admin/profile') }}" class="d-block">{!! Auth::user()->firstname!!}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link @if ($segment2 == 'dashboard') active @endif">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @if (auth()->user()->can('Update Site Settings') || auth()->user()->can('Update Escrow Settings'))
                <li class="nav-item @if ($segment2 == 'site-settings' || $segment2 == 'escrow-settings') menu-open @endif">
                    <a href="#" class="nav-link @if ($segment2 == 'site-settings' || $segment2 == 'escrow-settings') open active @endif">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'site-settings' || $segment2 == 'escrow-settings') block @endif">
                        @if (auth()->user()->can('Update Site Settings'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/site-settings') }}" class="nav-link  @if ($segment2 == 'site-settings') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Site Settings</p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('Update Escrow Settings'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/escrow-settings') }}" class="nav-link  @if ($segment2 == 'escrow-settings') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Escrow Settings</p>
                            </a>
                        </li>
                        @endif

                        @if (auth()->user()->can('Update Announcement Settings'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/announcement-settings') }}" class="nav-link  @if ($segment2 == 'announcement-settings') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Announcement Settings</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Roles') || auth()->user()->can('View Permissions'))

                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'roles' || $segment2 == 'permissions') active @endif">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Roles & Permissions
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'roles' || $segment2 == 'permissions') block @endif">
                        @if (auth()->user()->can('View Roles'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/roles') }}" class="nav-link  @if ($segment2 == 'roles') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('View Permissions'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/permissions') }}" class="nav-link  @if ($segment2 == 'permissions') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                @endif
                @if (auth()->user()->can('View Admin Users'))

                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'users') active @endif">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Admin Users
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'users') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/users') }}" class="nav-link  @if ($segment2 == 'users') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Admin Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Buyers'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'buyers') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Buyers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'buyers') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/buyers') }}" class="nav-link  @if ($segment2 == 'buyers') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Buyers</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Sellers'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'sellers') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Sellers
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'sellers') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/sellers') }}" class="nav-link  @if ($segment2 == 'sellers') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Sellers</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Escrow Products'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'escrows' || $segment2 =='transaction-status') active @endif">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Escrow
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'escrows' || $segment2 =='transaction-status') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/escrows') }}" class="nav-link  @if ($segment2 == 'escrows') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Escrow</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Reviews'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'reviews') active @endif">
                        <i class="nav-icon fas fa-star"></i>
                        <p>
                            Products Reviews
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'reviews') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/reviews') }}" class="nav-link  @if ($segment2 == 'reviews') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reviews</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Transactions'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'transactions') active @endif">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Transactions
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'transactions') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/transactions') }}" class="nav-link  @if ($segment2 == 'transactions') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transactions</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Messages'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'messages') active @endif">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Escrow Messages
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'messages') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/messages') }}" class="nav-link  @if ($segment2 == 'messages') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Messages</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View CMS Pages'))

                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'cms-pages') active @endif">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            CMS Pages
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'cms-pages') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/cms-pages') }}" class="nav-link  @if ($segment2 == 'cms-pages') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage CMS pages</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Contactus Log'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'contactus-log') active @endif">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Contact Us Log
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'contactus-log') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/contactus-log') }}" class="nav-link  @if ($segment2 == 'contactus-log') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contact Us Log</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Tickets'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'support-ticket') active @endif">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Support Tickets
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'support-ticket') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/support-ticket') }}" class="nav-link  @if ($segment2 == 'support-ticket') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Support Tickets</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (auth()->user()->can('View Admin Logs'))
                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'logs') active @endif">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Admin Logs
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'logs') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/logs') }}" class="nav-link  @if ($segment2 == 'logs') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Admin Logs</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if (auth()->user()->can('View Templates'))
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'templates') active @endif">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Email Templates
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'templates') block @endif">
                        <li class="nav-item">
                            <a href="{{ url('/admin/templates') }}" class="nav-link  @if ($segment2 == 'templates') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Manage Templates</p>
                            </a>
                        </li>
                    </ul>
                </li> -->
                @endif
                @if (auth()->user()->can('View Faqs') || auth()->user()->can('View Faq Categories'))

                <li class="nav-item">
                    <a href="#" class="nav-link @if ($segment2 == 'faq-categories' || $segment2 == 'faqs') active @endif">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            FAQ & Categories
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="@if ($segment2 == 'faq-categories' || $segment2 == 'faqs') block @endif">
                        @if (auth()->user()->can('View Faq Categories'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/faq-categories') }}" class="nav-link  @if ($segment2 == 'faq-categories') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Faq Categories</p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('View Faqs'))
                        <li class="nav-item">
                            <a href="{{ url('/admin/faqs') }}" class="nav-link  @if ($segment2 == 'faqs') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQs</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                @endif
                <li class="nav-item">
                    <a href="{{ url('/admin/logout') }}" class="nav-link">
                        <i class="fas fa-sign-out-alt nav-icon"></i>
                        <p>logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
