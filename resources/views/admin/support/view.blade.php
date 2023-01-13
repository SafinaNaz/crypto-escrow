@extends('admin.app')
@section('content')


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Messages</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/messages') }}">Messages</a></li>
                    <li class="breadcrumb-item active">Support Ticket</li>
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
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            {!!$ticket->status()!!} Ticket#{{strtoUpper(encode($ticket->id))}}
                            @if($ticket->status <> 2)
                                <a class="btn btn-danger btn-sm" href="{{url('admin/support-ticket/change-status/'.encode($ticket->id).'/close')}}"><i class="fa  fa-close"></i> Close Ticket</a>
                            @endif

                            @if($ticket->status == 2)
                                <a class="btn btn-success btn-sm" href="{{url('admin/support-ticket/change-status/'.encode($ticket->id).'/open')}}"><i class="fa  fa-close"></i> Open Ticket Again</a>
                            @endif
                               
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class=" col-md-10 col-md-offset-1  p-t-30 ">
                        <div class="chat-holder p-3 mb-3">
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
                        </div>
                        @if($ticket->status <> 2)
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Reply Ticket
                                </h3>
                            </div>

                            <form nanme="etl-form" action="{{url('admin/support-ticket/reply')}}" method="POST" id="etl-form" class="form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    @csrf

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">Message</label>

                                            <textarea rows="5" cols="5" class="form-control" name="message" id="message" placeholder="Type Message here" data-rule-required="true" aria-required="true" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label class="col-sm-12 control-label">File(s)</label>

                                            <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/jfif,application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, text/plain,application/vnd.openxmlformats-officedocument.presentationml.slideshow, application/vnd.openxmlformats-officedocument.presentationml.presentation,application/pdf,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" id="files" name="files[]" multiple class="btn btn-primary">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <input type="hidden" value="{{$ticket->id}}" name="ticket_id" />

                                            <button type="submit" id="submitBttn" class="btn btn-success btn-block">Reply <i class="far fa-paper-plane"></i></button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                        @endif

                    </div>
                </div>
                <!-- /.card -->
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