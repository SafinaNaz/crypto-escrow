@extends("frontend.layouts.dashboard_master")

@section('content')
@if($profile->approved_status == '1')
    <div class="alert alert-success">
    <h3>You are verified</h3>
    </div>
@else
    <div class="alert alert-danger">
        <h3>Unverified</h3>
    </div>
@endif
<div class="user-panel">
    <form nanme="etl-form" action="{{route('verification-status')}}" method="POST" id="etl-form" class="form" enctype="multipart/form-data">
        @csrf
        <div class="from-step">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="from-step-item">
            <div class="from-step-heading">
                <div class="from-step-number">V</div>
                <div class="from-step-head">
                    <h4>Indentity Verify</h4>
                    <p>Upload documents to verify your indentity.</p>
                </div>
            </div>
            <div class="from-step-content">
                <div class="note note-md note-info note-plane">
                    <em class="fas fa-info-circle"></em>
                    <p>Please upload any of the following personal document.</p>
                </div>
                <div class="gaps-2x"></div>

                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                            <label for="etl_information" class="input-item-label">Verification Information *</label>

                            <textarea row="10" style="height:200px" id="etl_information" name="etl_information" class="input-bordered summernote" required>{!!$profile->etl_information!!}</textarea>
                            <p>Write any information / links about Verification.</p>
                        </div>
                    </div>
                </div>


                <div class="gaps-2x"></div>

                <h5 class="kyc-upload-title">To avoid delays when verifying account, Please make sure bellow:</h5>
                <ul class="kyc-upload-list">
                    <li>Chosen credential must not be expired.</li>
                    <li>Document should be good condition and clearly visible.</li>
                    <li>Make sure that there is no light glare on the Images.</li>
                </ul>
                <div class="gaps-4x"></div>
                <span class="upload-title">Select multiple images / Documents using CTRL button</span>
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="upload-box">

                            <div class="dz-message" data-dz-message="">

                                <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/jfif,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, text/plain,application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation,application/pdf,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" id="etl_images" name="etl_images[]" multiple class="btn btn-primary">
                            </div>
                            @if($profile->etl_images != '')
                            @php
                            $images = explode(',',$profile->etl_images);
                            @endphp
                            @if($images)
                            @foreach($images as $img)
                            @php
                            $ext = last(explode('.',$img));
                            @endphp
                            @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'jfif']))
                            <div class="col-xl-3 col-md-3">
                                <a href="{!!asset('storage/uploads/users/'.$profile->id.'/'.$img) !!}" target="_blank"><img loading="lazy" src="{!!asset('storage/uploads/users/'.$profile->id.'/'.$img) !!}" class="image-display" style="width:  150px;border:  1px solid #ccc;margin-right:10px;" /></a>
                            </div>
                            @else
                            <div class="col-xl-3 col-md-3">
                                <a href="{!!asset('storage/uploads/users/'.$profile->id.'/'.$img) !!}" target="_blank">{{$img}}</a>
                            </div>
                            @endif
                            @endforeach
                            @endif
                            @endif

                        </div>
                    </div>
                </div>
                <div class="gaps-1x"></div>


            </div>
        </div>

        <div class="gaps-2x"></div>
        <button type="submit" class="btn btn-primary">Submit Details</button>
        <div class="gaps-2x"></div>
</div>
</form>
</div>

@endsection
