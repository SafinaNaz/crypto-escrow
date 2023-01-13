@extends("frontend.layouts.master-layout")
@section('content')

<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>About Us</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>About Us</li>
            </ol>
        </div>

    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="about-wrap">
            <div class="about-img">
                <img class="img-fluid" src="{{ _asset('frontend/assets/img/about-img.jpg') }}" alt="{{SITE_NAME}}"
            </div>
            <div class="about-content">
                <p> Sed scelerisque condimentum fringilla. Curabitur luctus, lorem cursus tincidunt ornare, dui turpis bibendum quam, ut blandit mauris orci eget risus. Integer luctus dui ac nunc gravida aliquam. Aenean ut tellus ac risus varius luctus. Curabitur eu ornare risus. Quisque bibendum sapien iaculis facilisis pulvinar. Nunc accumsan, orci vitae faucibus facilisis, ipsum purus porttitor ligula, et euismod justo diam in lacus. Vivamus quis diam nec ex vehicula tincidunt. Sed et nunc nec neque luctus dictum id vitae lorem. Nunc sed lacus velit. Vestibulum quis neque turpis. Aliquam volutpat pretium sodales. Vivamus cursus sapien dolor, eu ultrices libero vehicula id. </p>
            </div>
        </div>
    </div>
    
</section>
@endsection