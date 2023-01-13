@php
$segment1 = Request::segment(1);
$cls = ' header-scrolled';
$logocls = '';
$logowdth = '';
@endphp
@if($segment1 != '')
@php
$cls = 'header-inner-pages';
$logocls = '-light';
$logowdth = 'width="111px"';
@endphp
@endif
<div class="topbar">
    <div class="container">
        <div class="text-center">
            <p>Tor onion address : <a href="javascript:void(0);" class="copyTextBtn" data-clipboard-text="hc66kswutaprgp6jowf4kerdgteyxeqo5owjylvlmxsbk7ngquf4fcid.onion">hc66kswutaprgp6jowf4kerdgteyxeqo5owjylvlmxsbk7ngquf4fcid.onion</a> | <a href="{{url('/')}}">Click here for a no-JavaScript version.</a></p>
        </div>
    </div>
</div>
<header id="header" class="fixed-top {{$cls}}">
    <div class="container-fluid">
        <div class="row justify-content-center d-flex align-items-center">
            <div class="col-lg-3 col-md-7 col-sm-6 ">
                <div class="ticker-wrap">
                    <div class="ticker">
                        @if(SHOW_SITE_ANNOUNCEMENT)
                        <div class="ticker__item">{{SITE_ANNOUNCEMENT}}</div>
                        @endif    
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-6 pl-0">
                <div class="news-ticker">
                    <ul class="news-list" data-length="5">
                        <li class="news">
                            <time class="news__date">DOGE/BUSD</time>
                            <p class="news__title">$0.315259 <span style="color: red;">-8.20%</span></p>
                        </li>
                        <li class="news">
                            <time class="news__date">FIL/BUSD</time>
                            <p class="news__title">$0.000008 <span style="color: red;">-1.20%</span></p>
                        </li>
                        <li class="news">
                            <time class="news__date">ETH/BUSD</time>
                            <p class="news__title">$0.315259 <span style="color: red;">-8.20%</span></p>
                        </li>
                        <li class="news">
                            <time class="news__date">SHIB/BUSD</time>
                            <p class="news__title">$2,559.45 <span style="color: red;">-9.20%</span></p>
                        </li>
                        <li class="news">
                            <time class="news__date">ADA/BUSD</time>
                            <p class="news__title">$1,559.45 <span style="color: red;">-6.20%</span></p>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="col-lg-6 col-md-12">
                <div class="menu-wrap">

                    <nav class="nav-menu d-lg-block">
                        <ul>
                            <li class="@if($segment1 == '') active @endif"><a href="{{url('/')}}">Home</a></li>
                            <li class=""><a href="{{url('about-us')}}">About Us</a></li>
                            <li class=""><a href="{{url('faqs')}}">FAQs</a></li>
                            <li class=""><a href="{{url('forum')}}">Forum</a></li>
                            @if(!Auth::user())
                            <li><a href="{{url('/login')}}">Login</a></li>
                            <li><a href="{{url('/register')}}">Sign Up</a></li>
                            @else
                            <li><a href="{{url('/reviews')}}">Reviews</a></li>
                            <li><a href="{{url('/escrows')}}">Dashboard</a></li>
                            @endif
                        </ul>
                    </nav><!-- .nav-menu -->
                   <!--- <a href="{{url('/get-started')}}" class="get-started-btn scrollto btn-round">Create an Escrow Now</a>--->
                </div>
            </div>


        </div>
    </div>
</div>
</header>

