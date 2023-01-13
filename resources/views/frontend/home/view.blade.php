@extends('frontend.layouts.master-layout')
@section('content')
<section id="hero" class="d-flex align-items-center index-banner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 col-lg-5 col-md-9 pt-3 pt-lg-0 order-2 order-lg-1 order-md-1 d-flex flex-column justify-content-center">
                <div class="hero-content animate__animated animate__fadeInLeft">
                   
                   
                    <h1>Never <span>Buy or Sell</span> Online without using</h1>
                    <p>If the path is beautiful, let us not ask where it leads. my religion is very simple. my religion is kindness. each of us has within our power the ability to disrupt</p>
                    @if(!empty(Auth::user()))
                        @if(Auth::user()->user_type == 1)
                            <div><a href="{{route('get-started')}}" class="btn btn-cripto-main btn-round">Create an Escrow Now</a></div>
                        @endif
                    @endif
                  
                </div>
            </div>
            <div class="col-xl-6 col-lg-7 col-md-3 text-right order-1 order-lg-2 order-md-2  p-0">
                <img src="{{ _asset('frontend/assets/img/hero-img.png') }}" alt="{{SITE_NAME}}" class="img-fluid" />
            </div>
        </div>
    </div>
</section>
<main id="main">
 
    <section id="services" class="services grey-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                    <h3>Safely buy and sell products and services from $100 to $10 million or more</h3>
                </div>

                @php $home_pages = home_cms_pages() @endphp
                @if($home_pages)
                @foreach($home_pages as $p)

                <div class="col-lg-4 col-md-6 d-flex align-items-stretch animate__animated animate__fadeInDown animate__delay-4s">
                    <div class="icon-box">
                        <div class="box-title">
                            
                            <h4><a href="{{url($p->seo_url)}}">{{$p->title}}</a></h4>
                        </div>
                        <p>{{$p->short_description}}</p>
                        <a href="{{url($p->seo_url)}}" class="link">Learn More</a>
                    </div>
                </div>
                @endforeach
                @endif

                <div class="col-lg-12 col-md-12 text-center mt-4 animate__animated animate__fadeInDown animate__delay-4s">
                    <p>Contact our friendly support team on {{SITE_PHONE}} to find out if your transaction can be covered.</p>
                    <div class="services-links">
                        <a href="{{url('/get-started')}}" class="btn btn-cripto-main btn-round">Get Start Now</a>
                        <a href="{{url('/about-escrow')}}" class="link">Learn More About Escrow</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
    <section id="companies" class="companies-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 ">
                    <div class=" content">
                        <h3>The recommended payment system by top eCommerce companies</h3>
                        <p>Escrow.com is the recommended payment system of top eCommerce companies including Uniregistry, GoDaddy, ClassicCars.com and Shopify Exchange. Talk to us about signing up today.</p>
                        <a href="{{url('/get-started')}}" class="btn btn-cripto-main btn-round">Get Start Now</a>
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <div class="comapanies-list">
                        <a href="#">
                            <img loading="lazy" class="img-fluid" src="{{ _asset('frontend/assets/img/company1.png') }}" />
                        </a>
                        <a href="#">
                            <img loading="lazy" class="img-fluid" src="{{ _asset('frontend/assets/img/company2.png') }}" />
                        </a>
                        <a href="#">
                            <img loading="lazy" class="img-fluid" src="{{ _asset('frontend/assets/img/company3.png') }}" />
                        </a>
                        <a href="#">
                            <img loading="lazy" class="img-fluid" src="{{ _asset('frontend/assets/img/company5.png') }}" />
                        </a>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 text-center">
                </div>
            </div>
        </div>
    </section>
    @endsection