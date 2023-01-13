@extends("auth.layouts.layout")
@section('content')
<main id="main">
    <section class="account-page login">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="account-form">
                        <div class="login-logo">
                            <form action="{{ route('register') }}" id="register-form" name="register-form" method="POST" class="form">
                                @csrf
                                <h3 class="acc-title">Register to Safeland</h3>
                                <p >OTP based 2FA is composulory when signing up but can be disabled later if needed(not advised!)</p>
                                @include('frontend.layouts.alert',['class' => 'hideAlertAuto1'])
                                <div class="form-group">
                                    <input class="form-control @error('username') is-invalid @enderror" type="text" id="username" name="username" placeholder="Choose a username" required />
                                    <span class="form-icon"><i class="bx bx-mail-send"></i></span>
                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Choose a Passowrd" required />
                                    <span class="form-icon"><i class="bx bx-lock"></i></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">

                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your Password">
                                    <span class="form-icon"><i class="bx bx-lock"></i></span>
                                    @error('new-password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group text-left  @error('user_type') is-invalid @enderror">
                                     <label> Do you want to register as a Buyer or Seller? </label>
                                    <div class="radio">
                                       
                                      <label class="d-flex align-items-center"> <span class="form-icon form-icon-custom mr-2"><i class="bx bx-user"></i></span> Seller &nbsp;<input type="radio" name="user_type" value="1"></label>
                                  </div>
                                  <div class="radio">
                                     
                                      <label class="d-flex align-items-center"> <span class="form-icon form-icon-custom mr-2"><i class="bx bx-user"></i></span>  Buyer &nbsp;<input type="radio" name="user_type" value="2"></label>
                                  </div>
                                   @error('user_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                               



                                <small> By registering you agree to Safeland's <a href="{{url('terms-of-use')}}">Terms of Using the Escrow Platform</a> and <a href="{{url('privacy-policy')}}">Privacy Policy.</a>

                                    <div class="form-group">
                                        <button class="btn btn-cripto-main btn-round btn-block" type="submit">Signup</button>
                                    </div>
                                    <a href="{{url('login')}}" class="bottom-link">Already have an Account?</a>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection