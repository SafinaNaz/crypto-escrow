@extends("frontend.layouts.dashboard_master")

@section('content')
<div class="user-panel">
    <form nanme="etl-form" action="{{url('support-ticket/reply')}}" method="POST" id="etl-form" class="form" enctype="multipart/form-data">
        @csrf

        <div class="heading-block d-flex justify-content-between">
            <h2>{!!$ticket->status()!!} Ticket#{{strtoUpper(encode($ticket->id))}}</h2>
            @if($ticket->status <> 2)
                <a class="btn btn-danger btn-sm" href="{{url('support-ticket/change-status/'.encode($ticket->id).'/close')}}"><i class="ti  ti-close"></i> Close Ticket</a>
                @endif

                @if($ticket->status == 2)
                <a class="btn btn-success btn-sm" href="{{url('support-ticket/change-status/'.encode($ticket->id).'/open')}}"><i class="ti  ti-open"></i> Open Ticket Again</a>
                @endif
        </div>

        @if($ticket->status <> 2)

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
                    <input type="hidden" value="{{$ticket->id}}" name="ticket_id" />
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
            <button type="submit" class="btn btn-primary">Reply</button>
            <div class="gaps-2x"></div>

            @endif

            <hr>
            @if($messages != null)
            <ul>
                @foreach($messages as $message)
                <li>
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <label class="input-item-label">
                                    @if($message->is_admin == 1)
                                    Admin
                                    @else
                                    {!!$message->user->full_name()!!}
                                    @endif
                                </label>
                                <label class="input-item-label" style="margin-left:20px">{!!$message->get_date()!!}</label>
                                <div>
                                    {{$message->message}}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        @if($message->files != '')
                        @php
                        $images = explode(',',$message->files);
                        @endphp
                        @if($images)
                        @foreach($images as $img)
                        @php
                        $ext = last(explode('.',$img));
                        @endphp
                        @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'jfif']))
                        <div class="col-xl-2 col-md-2">
                            <a href="{!!asset('storage/uploads/support/'.$message->ticket_id.'/'.$img) !!}" target="_blank"><img loading="lazy" src="{!!asset('storage/uploads/support/'.$message->ticket_id.'/'.$img) !!}" class="image-display" style="width:  100px;border:  1px solid #ccc;margin-right:10px;" /></a>
                        </div>
                        @else
                        <div class="col-xl-2 col-md-2">
                            <a href="{!!asset('storage/uploads/support/'.$message->ticket_id.'/'.$img) !!}" target="_blank">{{$img}}</a>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        @endif
                    </div>
                    <hr>
                </li>
                @endforeach
            </ul>

            <nav class="pull-right">{!! $messages->links( "pagination::bootstrap-4") !!}</nav>

            @endif

    </form>
</div>



@endsection