@extends("frontend.layouts.dashboard_master")

@section('content')
<div class="user-panel">
    <form nanme="etl-form" action="{{url('support-ticket')}}" method="POST" id="etl-form" class="form" enctype="multipart/form-data">
        @csrf

        <div class="heading-block d-flex justify-content-between">
            <h2>Create Ticket</h2>
        </div>

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

            <div class="from-step-content">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                            <label for="subject" class="input-item-label">Subject *</label>

                            <input type="text" id="subject" name="subject" class="input-bordered" required />

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                            <label for="message" class="input-item-label">Your Message *</label>

                            <textarea row="10" style="height:200px" id="message" name="message" class="input-bordered summernote" required></textarea>

                        </div>
                    </div>
                </div>


                <div class="gaps-2x"></div>

                <div class="gaps-4x"></div>
                <span class="upload-title">Select File(s)</span>
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="upload-box">

                            <div class="dz-message" data-dz-message="">

                                <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/jfif,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, text/plain,application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation,application/pdf,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" id="files" name="files[]" multiple class="btn btn-primary">
                            </div>

                        </div>
                    </div>

                </div>
                <div class="gaps-1x"></div>


            </div>
        </div>

        <div class="gaps-2x"></div>
        <button type="submit" class="btn btn-primary">Create Ticket</button>
        <div class="gaps-2x"></div>
</div>
</form>
</div>

@endsection