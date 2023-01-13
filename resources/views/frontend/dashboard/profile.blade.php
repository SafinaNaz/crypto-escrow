@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <form name="profile-form" action="{{route('profile-update')}}" method="POST" id="profile-form" class="form form-des-wrapper" enctype="multipart/form-data">
        @csrf
        <div class="from-step">
            <div class="from-step-item">
                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>My Profile</h4>
                    </div>
                </div>
                <div class="from-step-content">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="gaps-2x"></div>
                                       

                    @if(Auth::user()->user_type == 2 && Auth::user()->is_active == 0)
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label for="first-name" class="input-item-label">Password</label>
                                <input class="input-bordered" type="password" id="password" name="password" data-rule-required="true" data-rule-minlength="8" aria-required="true" required>
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                        <input type="hidden" id="is_active" name="is_active" value="1" />
                        <div class="col-xl-4 col-md-6">
                            <div class="form-group">
                                <label for="first-name" class="input-item-label">Confirm Password</label>
                                <input class="input-bordered" type="password" id="password_confirmation" name="password_confirmation" required data-rule-equalto="#password" data-rule-minlength="8" value="" autocomplete="off" required>
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                    </div>
                    @endif

                    <div class="from-step-content note-wrapper">
                        <div class="note note-md note-info note-plane">
                            <em class="fas fa-info-circle"></em>
                            <p>DO NOT USE your exchange wallet address such as Kraken, Bitfinex, Bithumb, Binance etc.</p>
                        </div>
                        <div class="gaps-2x"></div>
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="country" class="input-item-label">Bitcoin Wallet Address</label>
                                    @if($profile->btc_address == '' || $profile->btc_address == null)
                                    <input class="input-bordered" type="text" id="btc_address" placeholder="Enter Bitcoin Wallet Address" name="btc_address" value="" required validBTCAddress="true" required>
                                    @else
                                    <input class="input-bordered" type="text" disabled value="{{$profile->btc_address}}" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label for="city" class="input-item-label">Monero Wallet Address</label>

                                    @if($profile->monero_address == '' || $profile->monero_address == null)
                                    <input class="input-bordered" type="text" id="monero_address" placeholder="Enter Monero Wallet Address" name="monero_address" value="" required validXMRAddress="true" required>
                                    @else
                                    <input class="input-bordered" type="text" disabled value="{{$profile->monero_address}}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                </div><!-- .from-step-content -->
            </div>
            <div class="form-step-item pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</div><!-- .user-content -->

@endsection