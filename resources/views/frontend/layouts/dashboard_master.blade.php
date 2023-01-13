<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="js">

<head>
    @php
    $segment2 = Request::segment(1);
    $segment3 = Request::segment(2);
    $segment4 = Request::segment(3);
    @endphp
    <!-- Site Title  -->
    @if(isset($meta_title) && $meta_title != '')
    <title>{{$meta_title}}</title>
    @else
    <title>{{SITE_NAME}}@php echo ' - '.ucwords($segment2); @endphp</title>
    @endif
    @if(isset($meta_descrition) && $meta_descrition != '')
    <meta name="description" content="{!!$meta_descrition!!}">
    @else
    <meta name="description" content="{!!SITE_DESCRIPTION!!}" />
    @endif
    @if(isset($meta_keywords) && $meta_keywords != '')
    <meta name="keywords" content="{!!$meta_keywords!!}">
    @else
    <meta name="keywords" content="{!!SITE_KEYWORDS!!}" />
    @endif

    <meta charset="utf-8">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Favicon-->
    <link rel="shortcut icon" href="{{ _asset('frontend/assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ _asset('frontend/assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Vendor Bundle CSS -->
    <link rel="stylesheet" href="{{_asset('frontend/dashboard/css/vendor.bundle.css?ver=100')}}">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="{{_asset('frontend/dashboard/css/style.css?ver=100')}}">

    @yield('styles')

</head>

<body class="user-dashboard">

    <!-- TopBar End -->

    <div class="user-wraper left-menu open">
        <div class="site-logo">
            <a href="{{url('/')}}" class="site-brand">
                <img loading="lazy" src="{{asset('frontend/dashboard/images/logo.svg')}}" alt="{{SITE_NAME}}">
            </a>
        </div><!-- .site-logo -->
        <div class="user-sidebar">
            <div class="user-sidebar-overlay"></div>
            <ul class="user-icon-nav">
                <li class="@if($segment2 == 'escrows' || $segment2 == 'transaction-detail') active @endif"><a href="{{url('/escrows')}}"><em class="ti ti-files"></em>ESCROWS</a></li>
                <li class="@if($segment2 == 'messages') active @endif"><a href="{{url('/messages')}}"><em class="ti ti-comment-alt"></em>Messaging</a></li>
                <li class="@if($segment2 == 'feedback') active @endif"><a href="{{url('/feedback')}}"><em class="ti ti-thought"></em>feedback</a></li>
                 <li class="@if($segment2 == 'pending-feedback') active @endif"><a href="{{url('/pending-feedback')}}"><em class="ti ti-thought"></em>pending feedback</a></li>
                  <li class="@if($segment2 == 'reviews') active @endif"><a href="{{url('/reviews')}}"><em class="ti ti-thought"></em>public reviews</a></li>
                @if(Auth::user()->user_type == 1)
                <li class="@if($segment2 == 'verification-status') active @endif"><a href="{{url('/verification-status')}}"><em class="ti ti-write"></em>Verification Status</a></li>
               
                @endif
                <li class="@if($segment2 == 'escalate-decision' || $segment2 == 'dispute-messages') active @endif"><a href="{{url('/escalate-decision')}}"><em class="ti ti-filter"></em>Escalate Decision</a></li>

                <hr />

                <li class="@if($segment2 == 'support-ticket' || $segment2 == 'support-messages') active @endif"><a href="{{url('/support-ticket')}}"><em class="ti ti-ticket"></em>Support Tickets</a></li>
                <hr />

                <li><a href="{{route('profile')}}"><i class="ti ti-id-badge"></i>My Profile</a></li>
                <li><a href="{{route('change-password')}}"><i class="ti ti-lock"></i>Password Settings</a></li>

                <li><a href="{{route('2fa-setup')}}"><i class="ti ti-settings"></i>Setup 2fa</a></li>

                <li><a href="{{ route('logout') }}"><i class="ti ti-power-off"></i>Logout</a></li>
            </ul><!-- .user-icon-nav -->
        </div><!-- .user-sidebar -->
    </div>
    <section class="right-canvas min_height expanded">
        @include('frontend.layouts.dashboard_header')
        <!-- .topbar-action -->



        <div class="user-content">
            
            <div class="text-left">
                @if(Auth::user()->user_type == 1)
                <h1>Seller 
                @else 
                <h1>Buyer
                @endif
                 Dashboard </h1>
            </div>
        
                @include('frontend.layouts.alert',['class' => 'hideAlertAuto'])

            @yield('content')

            <!-- @include('sweetalert::alert') -->
        </div>
    </section>

    <!-- UserWraper End -->

    <!-- ======= Footer ======= -->
    @include('frontend.layouts.dashboard_footer')
    <!-- FooterBar End -->

</body>

</html>