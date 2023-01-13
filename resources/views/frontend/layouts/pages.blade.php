@extends("frontend.layouts.master-layout")
@section('content')

<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>{!!$cmsPage->title!!}</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>{!!$cmsPage->title!!}</li>
            </ol>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="page-content">
    <div class="container">
        <div class="about-wrap">
            <div class="about-img">
                <img class="img-fluid" src="{{ _asset('frontend/assets/img/about-img.jpg') }}" alt="{{SITE_NAME}}" />
            </div>
            <div class="about-content">
                {!!$cmsPage->description!!}
            </div>
        </div>
    </div>

</section>


@endsection