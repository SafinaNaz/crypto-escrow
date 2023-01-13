@extends("frontend.layouts.dashboard_master")
@section('content')
<style>
    .msgesLi {
        /* justify-content: space-between; */
        flex-direction: unset;
        align-items: center;
    }
</style>
<div class="user-panel">
    <div class="row">
        @if(count($threads) > 0)
        <div class="col-xl-4">
            <div class="message-sidebar mb-2 mb-xl-0">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="flex-fill mb-0">Escrow Messages <small><strong><span class="text-danger" data-toggle="tooltip" data-placement="right" title="We are using an encryption package with private and public key with an encryption password. We are using  Spatie Crypto package for encrypt and decrypt data. ">Note</span></strong></small></h3>
                    <div class="dropdown message-menu dropleft">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                          
                            <a class="dropdown-item cur-poi" href="{{url('/messages?message=unread')}}">Unread</a>
                          
                        </div>
                    </div>
                </div>
                <form action="{{url('/messages')}}" method="get" class="message-search-form d-flex aling-items-center mb-4">
                    <input class="form-control flex-fill" placeholder="Search message" name="search" value="{{$search}}" type="text">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <span class="view-all-message d-block d-xl-none pb-3 text-right">View All</span>
                <ul class="list-unstyled messages-list">
                    @php
                    $lastSender = '';
                    $lastSenderImg = '';
                    $lastMsg = '';
                    @endphp

                    @if($threads)
                    @foreach($threads as $k => $th)
                    @php $msger = 'sender';@endphp
                    <li class="d-flex mb-2 @if($thread_id == $th->id) active @endif">
                        <a href="{{route('messages',['id' => encode($th->product_id)]).$queryString}}">
                            <div class="flex-fill d-flex">
                                <div class="image-holder mr-2 mb-2">
                                    <img loading="lazy" src="{{$th->messages->first()->$msger->photo()}}" alt="{!!$th->messages->first()->$msger->full_name()!!}">
                                </div>
                                <div class="sender-detail d-flex flex-column">
                                    <strong class="sender-name">{!!$th->messages->first()->$msger->full_name()!!}</strong>
                                    <span class="sender-name">{{$th->messages->first()->message}}</span>
                                </div>
                            </div>
                            <time class="time-holder">{{$th->messages->first()->get_date()}}</time>
                        </a>
                    </li>
                    @if($k == 0)
                    @php
                    $lastSender = $th->messages->first()->$msger->full_name();
                    $lastSenderImg = $th->messages->first()->$msger->photo();
                    $lastMsg = $th->messages->first()->message;
                    @endphp
                    @endif
                    @endforeach

                    @else
                    <li class="d-flex mb-2 active">
                        <div class="flex-fill d-flex">
                            No record found.
                        </div>
                    </li>
                    @endif

                </ul>

            </div>
            <nav class="pull-right">{!! $threads->links("pagination::bootstrap-4") !!}</nav>
        </div>
        <div class="col-xl-8">
            <div class="chat-box">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if($tab == 'public' || $tab == '')active @endif" data-toggle="tab" href="{{url('/messages?tab=public')}}">Public</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($tab == 'private')active @endif" data-toggle="tab" href="{{url('/messages?tab=private')}}">Private</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="public" class="tab-pane @if($tab == 'public' || $tab == '') active @else fade @endif"><br>

                        <header class="chatbaox-header d-flex">
                            <div class="flex-fill d-flex">
                                <div class="image-holder mr-2 mb-2">
                                    <img loading="lazy" src="{{$lastSenderImg}}" alt="{{$lastSender}}">
                                </div>
                                <div class="sender-detail d-flex flex-column">
                                    <strong class="sender-name">{!!$lastSender!!}</strong>
                                    <span class="sender-name">Public Chat</span>
                                </div>
                            </div>
                        </header>
                        <div class="chat-holder p-3 mb-3">
                            <ul class="chat-messages list-unstyled" id="messages">

                                @foreach($current_thread->messages as $msg)
                                @if($msg->is_private == 1)
                                @php continue; @endphp
                                @endif

                                @php $msger = 'sender';@endphp

                                <li class="d-flex mb-2 msgesLi @if($msg->sender_id == Auth::user()->id) author-reply @endif">
                                    <div class="d-flex align-items-center img-name-wrapper">
                                        <div class="image-holder mr-2 mb-2">
                                            @if($msg->is_admin == 1)
                                            <img loading="lazy" src="{{asset('frontend/dashboard/images/admin.png')}}" alt="Admin">
                                            @else
                                            <img loading="lazy" src="{{$msg->$msger->photo()}}" alt="{{$msg->$msger->full_name()}}">
                                            @endif
                                        </div>
                                        <div class="sender-detail inner-chat-box d-flex flex-column p-2">
                                            <strong class="sender-name">
                                                @if($msg->is_admin == 1)
                                                Admin
                                                @else
                                                {!!$msg->$msger->full_name()!!}
                                                @endif
                                            </strong>
                                            <span class="sender-name">{{$msg->message}}</span>
                                        </div>
                                    </div>
                                    <time class="time-holder">{{$msg->get_date()}}</time>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="reply-box p-3">

                            <form class="form" name="msg-form" id="msg-form" action="{{route('sendMessage')}}" method="post">
                                <div class="d-flex">
                                    @csrf
                                    <textarea type="text" rows="5" cols="5" id="send_message" name="message" class="flex-fill p-2 mr-2" placeholder="Type Message here" required></textarea>
                                    <input type="hidden" name="thread_id" value="{{$current_thread->id}}" />
                                    <input type="hidden" name="is_private" value="0" />
                                    @if(Auth::user()->id == $current_thread->seller_id )
                                    <input type="hidden" name="receiver_id" value="{{$current_thread->buyer_id}}" />
                                    @endif
                                    @if(Auth::user()->id == $current_thread->buyer_id )
                                    <input type="hidden" name="receiver_id" value="{{$current_thread->seller_id}}" />
                                    @endif
                                    <button type="submit" id="submitBttn">Send <i class="far fa-paper-plane"></i></button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div id="private" class="tab-pane @if($tab == 'private')active @else fade @endif"><br>
                        <header class="chatbaox-header d-flex">
                            <div class="flex-fill d-flex">
                                <div class="image-holder mr-2 mb-2">
                                    <img loading="lazy" src="{{$lastSenderImg}}" alt="{{$lastSender}}">
                                </div>
                                <div class="sender-detail d-flex flex-column">
                                    <strong class="sender-name">{{$lastSender}}</strong>
                                    <span class="sender-name">Private Chat</span>
                                </div>
                            </div>
                        </header>
                        <div class="chat-holder p-3 mb-3">
                            <ul class="chat-messages list-unstyled" id="messagesp">

                                @foreach($current_thread->messages as $msg)
                                @if($msg->is_private == 0)
                                @php continue; @endphp
                                @endif

                                @php $msger = 'sender';@endphp

                                <li class="d-flex mb-2 msgesLi @if($msg->sender_id == Auth::user()->id) author-reply @endif">
                                    <div class="d-flex align-items-center img-name-wrapper">
                                        <div class="image-holder mr-2 mb-2">
                                            <img loading="lazy" src="{{$msg->$msger->photo()}}" alt="{{$msg->$msger->full_name()}}">
                                        </div>
                                        <div class="sender-detail d-flex flex-column p-2">
                                            <strong class="sender-name">{!!$msg->$msger->full_name()!!}</strong>
                                            <span class="sender-name">{!!decryptText($msg->message)!!}</span>
                                        </div>
                                    </div>
                                    <time class="time-holder">{{$msg->get_date()}}</time>
                                </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="reply-box p-3">

                            <form class="form" name="msg-form-private" id="msg-form-private" action="{{route('sendMessage')}}" method="post">
                                <div class="d-flex">
                                    @csrf
                                    <textarea type="text" rows="5" cols="5" id="send_messagep" name="message" class="flex-fill p-2 mr-2" placeholder="Type Message here" required></textarea>
                                    <input type="hidden" name="thread_id" value="{{$current_thread->id}}" />
                                    <input type="hidden" name="is_private" value="1" />
                                    @if(Auth::user()->id == $current_thread->seller_id )
                                    <input type="hidden" name="receiver_id" value="{{$current_thread->buyer_id}}" />
                                    @endif
                                    @if(Auth::user()->id == $current_thread->buyer_id )
                                    <input type="hidden" name="receiver_id" value="{{$current_thread->seller_id}}" />
                                    @endif
                                    <button type="submit" id="submitBttnp">Send <i class="far fa-paper-plane"></i></button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        @else
        <div class="col-xl-12">
            <div class="alert alert-warning">No message found</div>
        </div>
        @endif
    </div>
</div><!-- .user-content -->

@endsection
