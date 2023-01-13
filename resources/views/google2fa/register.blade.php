@extends("auth.layouts.layout")
@section('content')
<main id="main">
    <section class="account-page login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">

                    <div class="account-form">
                        <div class="login-logo">
                            <a href="{{url('/')}}" class="logo mr-auto fixed-logo"><img src="{{ _asset('frontend/assets/img/logo.svg') }}" alt="{{SITE_NAME}}" width="150px" class="img-fluid" /></a>

                            <h3 class="acc-title">Set up Free OTP / Any Authenticator to enable 2FA</h3>

                            <div class="form-group">
                                <p>Set up you 2FA by scanning the barcode below. Alternatively, you can use the code {{ $secret }}</p>
                                <div>
                                {!!$QR_Image!!}
                                </div>
                                @if (!@$reauthenticating)
                                <p>You must set up your Free OTP / Any Authenticator app before continuing. You will be unable to login otherwise</p>
                                <div>
                                    <a href="{{route('register.complete')}}">
                                    <button class="btn btn-cripto-main btn-round btn-block">Complete Registration</button>
                                    </a>
                                </div>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection