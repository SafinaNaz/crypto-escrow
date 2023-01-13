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
                            <form action="{{ route('2fa') }}" id="login-form" name="login-form" method="POST" class="form">
                                <h3 class="acc-title">Safeland 2FA</h3>
                                @include('frontend.layouts.alert',['class' => 'hideAlertAuto1'])
                                @csrf
                                <div class="form-group">
                                    <input class="form-control @error('one_time_password') is-invalid @enderror" type="number" id="one_time_password" name="one_time_password" placeholder="Please Enter OTP" required />
                                    <span class="form-icon"><i class="bx bx-mail-send"></i></span>
                                    @error('one_time_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-cripto-main btn-round btn-block" type="submit">Secure Login</button>
                                </div>
                                <a href="{{url('logout')}}" class="bottom-link">Back to Login Page</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection