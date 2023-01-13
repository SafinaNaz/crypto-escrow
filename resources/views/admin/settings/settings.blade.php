@extends('admin.app')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Settings</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Site Settings</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="{!! url('/admin/site-settings/update') !!}" method="post" class="form-horizontal form-validate setting-form" name="settingsForm" id="settingsForm" novalidate="novalidate" enctype="multipart/form-data">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- jquery validation -->
                    <!-- form start -->

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Site Settings
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                            @csrf
                            <input type="hidden" name="id" value="{!!@$settings->id!!}">

                            <div class="form-group">
                                <label for="site_logo">Site Logo</label>
                                <div class="col-sm-8">
                                    <img src="{!! checkImage(asset('storage/uploads/images/'.@$settings->site_logo)) !!}" class="image-display " id="site_logo_image" style="width:  150px;border:  1px solid #ccc;" />
                                    <input type="file" accept="image/*" onchange="change_image(this, 'site_logo_image', 'imgShow')" class="form-control site_logo_change" name="site_logo" id="site_logo">
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="site_name">Site Name *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="site_name" id="site_name" placeholder="Enter Site Name" value="{{ @$settings->site_name }}" required>
                                </div>
                            </div>
                            <div class="form-group">

                                <label for="lastname">Site Title *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="site_title" id="site_title" placeholder="Enter Site Title" value="{{ @$settings->site_title }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="site_email">Site Email *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="site_email" id="site_email" placeholder="Enter Site Email" value="{{ @$settings->site_email }}" required>
                                </div>
                            </div>

                         {{--    <div class="form-group">
                                <label for="inquiry_email">Inquiry Email *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="inquiry_email" id="inquiry_email" placeholder="Enter Inquiry Email" value="{{ @$settings->inquiry_email }}" required>
                                </div>
                            </div> --}}


                         {{--    <div class="form-group">
                                <label for="lastname">Mobile# *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="site_mobile" id="site_mobile" placeholder="Enter Site Mobile#" value="{{ @$settings->site_mobile }}" required>
                                </div>
                            </div> --}}

                        {{--     <div class="form-group">
                                <label for="lastname">Site Phone# *</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="site_phone" id="site_phone" placeholder="Enter Site Mobile#" value="{{ @$settings->site_phone }}" required>
                                </div>
                            </div> --}}

                         {{--    <div class="form-group">
                                <label for="site_address">Site Address </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="site_address" id="site_address" placeholder="Enter Address" value="{{ @$settings->site_address }}">
                                </div>
                            </div> --}}



                        </div>
                        <!-- /.card-body -->


                    </div>

                    <!-- /.card -->
                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Meta Keywords & Description
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="form-group">
                                <label for="site_keywords">Site Keywords</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="site_keywords" style="height: 150px;resize:  none;" id="site_keywords" placeholder="Enter Keywords" value="">{{ @$settings->site_keywords }}</textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="	site_description">Site Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="site_description" style="height: 150px;resize:  none;" id="site_description" placeholder="Enter Site Description..." value="">{{ @$settings->site_description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- right column -->
                <div class="col-md-6" style="display:none">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Social Settings
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                            <div class="form-group">
                                <label class="control-label" for="facebook">Facebook </label>
                                <input type="url" class="form-control" name="facebook" id="site_address" placeholder="Enter Facebook Link" value="{{ @$settings->facebook }}">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="lastname">Twitter </label>
                                <input type="url" class="form-control" name="twitter" id="twitter" placeholder="Enter Twitter Link" value="{{ @$settings->twitter }}">
                            </div>


                            <div class="form-group">
                                <label class="control-label" for="linkedin">Linkedin </label>
                                <input type="url" class="form-control" name="linkedin" id="linkedin" placeholder="Enter Linkedin" value="{{ @$settings->linkedin }}">
                            </div>
                            <div class="form-group">

                                <label class="control-label" for="lastname">Instagram </label>
                                <input type="url" class="form-control" name="insta" id="insta" placeholder="Enter instagram Link" value="{{ @$settings->insta }}">
                            </div>

                            <div class="form-group">

                                <label class="control-label" for="lastname">Skype </label>
                                <input type="text" class="form-control" name="skype" id="skype" placeholder="Enter Skype" value="{{ @$settings->skype }}">
                            </div>


                        </div>


                    </div>

                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Offer Time
                            </h3>
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">

                            <div class="form-group">
                                <label for="immediate_release_hours">Immediate Release Time (Hours) </label>
                                <div class="col-sm-10">
                                    <input type="number" min="0" step="1" class="form-control" name="immediate_release_hours" id="immediate_release_hours" placeholder="Enter Immediate Release Time" value="{{ @$settings->immediate_release_hours }}"> Hours
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="facebook">Level 1 Time *</label>
                                <select name="level1_time" id="level1_time" class='select2-me form-control'>
                                    @for($i = 24; $i<= 240; $i+=24) <option @if(isset($settings) && $i==$settings->level1_time) selected @endif value="{!!$i!!}">{!!$i!!} hours</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="facebook">Level 2 Time *</label>
                                <select name="level2_time" id="level2_time" class='select2-me form-control'>
                                    @for($i = 24; $i<= 240; $i+=24) <option @if(isset($settings) && $i==$settings->level2_time) selected @endif value="{!!$i!!}">{!!$i!!} hours</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="facebook">Level 3 Time *</label>
                                <select name="level3_time" id="level3_time" class='select2-me form-control'>
                                    @for($i = 24; $i<= 240; $i+=24) <option @if(isset($settings) && $i==$settings->level3_time) selected @endif value="{!!$i!!}">{!!$i!!} hours</option>
                                        @endfor
                                </select>
                            </div>


                        </div>

                    </div>


                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    Save Settings
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--/.col (right) -->
                </div>
                   <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Deposit Amount
                                    </h3>
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="immediate_release_hours">Set Deposit Amount for Dispute Level 3 </label>
                                        <div class="col-sm-10">
                                            <input type="number" min="0" step="1" class="form-control" name="deposit_amount" id="deposit_amount" placeholder="Enter Deposit Amount in %" value="{{ @$settings->deposit_amount }}"> 
                                        </div>
                                    </div>

                                 
                                  
                                    


                                </div>

                            </div>

                        </div>
        </form>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection