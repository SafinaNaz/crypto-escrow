@extends("frontend.layouts.dashboard_master")

@section('content')
<div class="user-content">

    <div class="user-panel">
        <form class="profile-form form form-des-wrapper"  action="{{route('change-password')}}" method="POST" id="profile-form">
            @csrf
            <div class="from-step">
                <div class="from-step-item">
                    <div class="from-step-heading">
                        <div class="from-step-head">
                            <h4>Change Password</h4>
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
                        <div class="row">

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="first-name" class="input-item-label">Password</label>
                                    <input class="input-bordered" type="password" id="password" name="password" data-rule-required="true" data-rule-minlength="8" aria-required="true">
                                </div><!-- .input-item -->
                            </div><!-- .col -->

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="first-name" class="input-item-label">Confirm Password</label>
                                    <input class="input-bordered" type="password" id="password_confirmation" name="password_confirmation" required data-rule-equalto="#password" data-rule-minlength="8" value="" autocomplete="off">
                                </div><!-- .input-item -->
                            </div><!-- .col -->

                        </div><!-- .row -->
                    </div><!-- .from-step-content -->
                </div>
                <div class="form-step-item pt-3">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </form>
    </div><!-- .user-content -->
    @endsection