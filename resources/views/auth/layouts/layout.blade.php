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
    <link href="{{ _asset('frontend/assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ _asset('frontend/assets/css/style.css') }}" rel="stylesheet" />


    <?php /* ?>
    <link type="text/css" href="{{ _asset('frontend/assets/css/app.min.css') }}" rel="stylesheet">
    <?php */ ?>
</head>

<body>

    @yield('content')
    <!-- End #main -->

</body>

</html>