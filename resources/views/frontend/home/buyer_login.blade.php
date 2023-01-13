<!DOCTYPE html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    @php
    $segment2 = Request::segment(1);
    $segment3 = Request::segment(2);
    $segment4 = Request::segment(3);
    @endphp
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
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Favicon-->
    <link rel="shortcut icon" href="{{ _asset('frontend/assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ _asset('frontend/assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="{{ _asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ _asset('frontend/assets/vendor/icofont/icofont.min.css') }}" rel="stylesheet" />
    <link href="{{ _asset('frontend/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet" />

    <link href="{{ _asset('frontend/assets/vendor/aos/aos.css') }}" rel="stylesheet" />
    <!--  Main CSS File -->
    <link href="{{ _asset('frontend/assets/css/style.css') }}" rel="stylesheet" />
</head>

<body>
    <main id="main">
        <section class="account-page buyer-login">
            <div class="container-fluid">
                <div class="row justify-content-lg-center">
                    <div class="col-lg-6">
                        <div class="buy-title-wrap">
                            <a href="{{url('/')}}" class="logo mr-auto fixed-logo"><img src="{{ _asset('frontend/assets/img/logo-light.svg') }}" alt="{{SITE_NAME}}" width="150px" class="img-fluid "></a>
                            <h3 class="text-white">To access the escrow page buyer needs to have the transaction ID generated (given by the seller).</h3>
                            @if(Session::has('error'))
                            <div class="alert alert-danger">{{Session::get('error') }}</div>
                            @endif
                            @if(Session::has('success'))
                            <div class="alert alert-success">{{Session::get('success') }}</div>
                            @endif
                        </div>

                        <div class="account-form">
                            <div class="login-logo">
                                <form action="{{ route('buyer-login') }}" id="login-form" name="login-form" method="POST" class="form">
                                    <div class="form-group">
                                        @csrf
                                        <input class="form-control" type="text" name="login_id" id="login_id" value="" placeholder="Enter Transaction ID" required>
                                        <span class="form-icon"><i class='bx bx-message-dots'></i></span>
                                        @error('login_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">

                                        <input class="form-control" type="password" name="password" id="password" value="" placeholder="Create Password (for 1st Time) / Enter Password" required>
                                        <span class="form-icon"><i class='bx bx-key'></i></span>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-cripto-sec btn-round btn-block" type="submit">Submit</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <!-- End #main -->

</body>

</html>