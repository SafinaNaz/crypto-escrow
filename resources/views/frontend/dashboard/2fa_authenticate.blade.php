@extends("frontend.layouts.dashboard_master")

@section('content')
<div class="user-content">

    <div class="user-panel">
        <form class="profile-form form form-des-wrapper" action="{{route('2fa-setup')}}" method="POST" id="profile-form">
            @csrf
            <div class="from-step">
                <div class="from-step-item">
                    <div class="from-step-heading">
                        <div class="from-step-head">
                            <h4>Set up Free OTP / Any Authenticator to enable 2FA</h4>
                        </div>
                    </div>
                    <div class="from-step-content">

                        <div class="gaps-2x"></div>
                        <div class="row">
                            <input type="hidden" name="google2fa" value="{{ $secret }}" />
                            <div class="col-xl-12 col-md-12">
                                <div class="form-group">
                                    <p>Set up you 2FA by scanning the barcode below. Alternatively, you can use the code <br><br> <strong>{{ $secret }}</strong></p>
                                    <div>
                                        {!!$QR_Image!!}
                                    </div>
                                </div><!-- .input-item -->
                            </div>
                        </div><!-- .col -->
                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="first-name" class="input-item-label">One Time Password</label>
                                    <input class="input-bordered" type="number" id="one_time_password" name="one_time_password" required value="" autocomplete="off">
                                </div><!-- .input-item -->
                            </div><!-- .col -->

                        </div><!-- .row -->
                    </div><!-- .from-step-content -->
                </div>
                <div class="form-step-item pt-3">
                    <button type="submit" class="btn btn-primary">Setup 2fa</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection