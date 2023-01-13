@extends('admin.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sellers</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/sellers') }}">Sellers</a></li>
                    <li class="breadcrumb-item active">{!!$action!!} Seller</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <form id="profile-form" name="profile-form" method="POST" action="{{url('/admin/sellers')}}" class="form-horizontal form-validate setting-form" novalidate="novalidate" enctype="multipart/form-data">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                {!!$action!!} Seller
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->


                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        <input type="hidden" name="id" value="{!!@$seller->id!!}">
                        <input type="hidden" name="action" value="{!!$action!!}">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="col-md-10 col-md-offset-1  p-t-30 ">
                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label for="first_name" class="control-label">User Name</label>
                                            <div class="col-sm-12">

                                                <input type="text" id="username" name="username" class='form-control' placeholder="Enter Username" data-rule-required="true" aria-required="true" value="{!!@$seller['username']!!}" />
                                            </div>
                                        </div>


                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="col-sm-12">
                                                <input type="password" id="password" name="password" class='form-control' placeholder="Enter password" @if($action=="Add" ) data-rule-required="true" aria-required="true" data-rule-minlength="8" @endif value="" autocomplete="off" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation" class="control-label">Confirm Password</label>
                                            <div class="col-sm-12">
                                                <input type="password" id="password_confirmation" name="password_confirmation" class='form-control' placeholder="Retype new password" @if($action=="Add" ) data-rule-required="true" aria-required="true" data-rule-equalto="#password" data-rule-minlength="8" @endif value="" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>

                                </div>




                            </div>

                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Wallet Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="col-md-10 col-md-offset-1  p-t-30 ">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="phone" class="control-label">Bitcoin Wallet Address *</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="btc_address" name="btc_address" class='form-control' placeholder="Enter Bitcoin Wallet Address" data-rule-required="true" aria-required="true" validBTCAddress="true" value="{!!@$seller['btc_address']!!}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="phone" class="control-label">Monero Wallet Address *</label>
                                            <div class="col-sm-12">
                                                <input type="text" id="monero_address" name="monero_address" class='form-control' placeholder="Enter Monero Wallet Address" data-rule-required="true" aria-required="true" validXMRAddress="true" value="{!!@$seller['monero_address']!!}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                ETL Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="col-md-10 col-md-offset-1  p-t-30 ">
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="phone" class="control-label">ETL Information *</label>
                                            <div class="col-sm-12">
                                            {!!nl2br(@$seller['etl_information'])!!}
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="photo">ETL Images / Files</label>
                                            <div class="col-sm-12">

                                                @if(@$seller->etl_images != '')
                                                @php
                                                $images = explode(',',@$seller->etl_images);
                                                @endphp
                                                @if($images)
                                                @foreach($images as $img)
                                                @php
                                                $ext = last(explode('.',$img));
                                                @endphp
                                                @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'jfif']))
                                                <div class="col-xl-3 col-md-3">
                                                    <a href="{!!asset('storage/uploads/users/'.$seller->id.'/'.$img) !!}" target="_blank"><img loading="lazy" src="{!!asset('storage/uploads/users/'.$seller->id.'/'.$img) !!}" class="image-display" style="width:  150px;border:  1px solid #ccc;margin-right:10px;" /></a>
                                                </div>
                                                @else
                                                <div class="col-xl-3 col-md-3">
                                                    <a href="{!!asset('storage/uploads/users/'.$seller->id.'/'.$img) !!}" target="_blank">{{$img}}</a>
                                                </div>
                                                @endif
                                                @endforeach
                                                @endif
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">

                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="col-md-10 col-md-offset-1  p-t-30 ">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">Status</label>
                                            <div class="col-sm-12">

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="is_active" id="is_active1" value="1" @if(isset($seller->is_active) && $seller->is_active == 1) checked @endif required>
                                                    <label for="is_active1" class="custom-control-label">Active</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="is_active" id="is_active2" value="0" @if(isset($seller->is_active) && $seller->is_active == 0) checked @endif>
                                                    <label for="is_active2" class="custom-control-label">Inactive</label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label">ETL Status</label>
                                            <div class="col-sm-12">

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="approved_status" id="approved_status1" value="1" @if(isset($seller->approved_status) && $seller->approved_status == 1) checked @endif required>
                                                    <label for="approved_status1" class="custom-control-label">Verified</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="approved_status" id="approved_status2" value="0" @if(isset($seller->approved_status) && $seller->approved_status == 0) checked @endif>
                                                    <label for="approved_status2" class="custom-control-label">Unverified</label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="card card-primary">

                        <div class="card-body">
                            <div class="col-md-10 col-md-offset-1  p-t-30 ">
                                <div class="row">
                                    <div class="form-actions text-right">

                                        <input type="reset" class='btn btn-default' value="Discard changes">
                                        <input type="submit" class='btn btn-primary' value="Save Changes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- /.card -->
                </form>
            </div>

            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6"></div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection