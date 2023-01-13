@extends("auth.layouts.layout")
@section('content')
<main id="main">
    <section class="account-page login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">

                    <div class="account-form">
                        <div class="login-logo">
                            
                            <form action="{{ route('login') }}" id="login-form" name="login-form" method="POST" class="form">
                                <h3 class="acc-title">Log in to Safeland</h3>
                                @include('frontend.layouts.alert',['class' => 'hideAlertAuto1'])
                                @csrf
                                <div class="form-group">
                                    <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" placeholder="Please Enter your username" required />
                                    <span class="form-icon"><i class="bx bx-mail-send"></i></span>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Please Enter your Passowrd" required />
                                    <span class="form-icon"><i class="bx bx-lock"></i></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-cripto-main btn-round btn-block" type="submit">Secure Login</button>
                                </div>
                                <p class="mb-0">Don't have an account?</p>
                                <a href="{{url('register')}}" class="bottom-link">Register an Account</a>
                                <div class="for-pass">
                                    <a href="{{route('password.request')}}">Forgot Password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection