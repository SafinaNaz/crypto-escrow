@extends('admin.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Announcements</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Announcements Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.announcement-settings.update') }}" method="post" class="form-horizontal" name="escrowSettingsForm" id="escrowSettingsForm">
            @csrf
            <input type="hidden" name="id" value="{!!@$settings->id!!}">
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Announcements
                            </h3>
                        </div>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card-body">

                            <div class="form-group">
                                <label class="col-sm-6 control-label">Show Announcement on Home page</label>
                                <div class="col-sm-6">

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_site_announcement" id="show_site_announcement1" value="1" @if(isset($settings->show_site_announcement) && $settings->show_site_announcement == 1) checked @endif required>
                                        <label for="show_site_announcement1" class="custom-control-label">Yes</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_site_announcement" id="show_site_announcement2" value="0" @if(isset($settings->show_site_announcement) && $settings->show_site_announcement == 0) checked @endif>
                                        <label for="show_site_announcement2" class="custom-control-label">No</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="site_announcement">Home Page Announcement </label>
                                <textarea rows="7" class="form-control" name="site_announcement" id="site_announcement" placeholder="Enter Home Page Announcement" required>{{ @$settings->site_announcement }}</textarea>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-6 control-label">Show Announcement on Seller Dashboard</label>
                                <div class="col-sm-6">

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_seller_announcement" id="show_seller_announcement1" value="1" @if(isset($settings->show_seller_announcement) && $settings->show_seller_announcement == 1) checked @endif required>
                                        <label for="show_seller_announcement1" class="custom-control-label">Yes</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_seller_announcement" id="show_seller_announcement2" value="0" @if(isset($settings->show_seller_announcement) && $settings->show_seller_announcement == 0) checked @endif>
                                        <label for="show_seller_announcement2" class="custom-control-label">No</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="seller_announcement">Seller Announcement </label>
                                <textarea rows="7" class="form-control" name="seller_announcement" id="seller_announcement" placeholder="Enter Seller Announcement" required>{{ @$settings->seller_announcement }}</textarea>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-6 control-label">Show Announcement on Home page</label>
                                <div class="col-sm-6">

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_buyer_announcement" id="show_buyer_announcement1" value="1" @if(isset($settings->show_buyer_announcement) && $settings->show_buyer_announcement == 1) checked @endif required>
                                        <label for="show_buyer_announcement1" class="custom-control-label">Yes</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" name="show_buyer_announcement" id="show_buyer_announcement2" value="0" @if(isset($settings->show_buyer_announcement) && $settings->show_buyer_announcement == 0) checked @endif>
                                        <label for="show_buyer_announcement2" class="custom-control-label">No</label>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="buyer_announcement">Buyer Announcement </label>
                                <textarea rows="7" class="form-control" name="buyer_announcement" id="buyer_announcement" placeholder="Enter Buyer Announcement" required>{{ @$settings->buyer_announcement }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Save Announcement Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function() {
        $('#escrowSettingsForm').validate({
            errorElement: "span",
            errorPlacement: function(error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>
@endsection