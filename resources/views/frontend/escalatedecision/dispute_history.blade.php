@extends("frontend.layouts.dashboard_master")
@section('content')
@php 
    $amount_text = '';
@endphp
@php
if(auth()->user()->user_type == 1) {
    $amount_text = 'Receivable';
} if(auth()->user()->user_type == 2) {
    $amount_text = 'Payable';
}
@endphp


@if(count($dispute_transaction) > 0)
@if($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id == $dispute_transaction[0]->user_id)
@php
    $amount_text = 'Receivable';
@endphp
@elseif($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != $dispute_transaction[0]->user_id )
@php
    $amount_text = 'Payable';
@endphp
@endif
@if($dispute_transaction[0]->winner_user_by_admin != null && $dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[0]->user_id)
@php
    $amount_text = 'Receivable';
@endphp
@elseif($dispute_transaction[0]->winner_user_by_admin != null && count($dispute_transaction) > 1 && $dispute_transaction[0]->winner_user_by_admin != $dispute_transaction[0]->user_id)
@php
    $amount_text = 'Payable';
@endphp
@endif
@endif
<div class="user-panel">
    <div class="row">


        <div class="col-xl-8">
            <div class="chat-box">

                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>Transaction Details</h4>

                    </div>
                </div>

                <div class="from-step-content">

                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <label class="input-item-label">Transaction ID:</label>
                            <span>@php echo $product->transaction_id @endphp</span>
                        </div>

                    </div>
                    @if($product->term_conditions != null)
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <label class="input-item-label">Term & Conditions:</label>
                            <span>{!!nl2br($product->term_conditions)!!}</span>
                        </div>
                    </div>
                    @endif
                    <div class="row">

                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">{{$amount_text}} Amount:</label>
                            <span>{{$product->productTransaction->total_amount}} {{$product->productCurrency->currency}}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Currency:</label>
                            <span>{{$product->productCurrency->currency}}</span>
                        </div>
                        {{-- <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Escrow Fee Payer:</label>
                            <span>@if ($product->escrow_fee_payer == 1)
                                <span class="badge badge-primary">Buyer</span>
                                @elseif ($product->escrow_fee_payer == 2)
                                <span class="badge badge-success">Seller</span>
                                @elseif ($product->escrow_fee_payer == 3)
                                <span class="badge badge-warning">50% Buyer & 50% Seller</span>
                                @endif</span>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Transaction Status:</label>
                            @if($current_status == 6 && auth()->user()->id == $product->buyer_id)
                            <span><label class="badge badge-primary">Completed</label></span>
                            @else
                            <span><label class="badge badge-primary">{{$product->productTransaction->transactionStatus->status}}</label></span>
                            @endif
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Completion Time:</label>
                            <span>{{$product->completion_days()}}</span>
                        </div>
                        @if (in_array($product->productTransaction->status_id, [2, 3]))
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Completion Date:</label>
                            <span>{{date('D d F, Y', strtotime($product->completion_time))}}</span>
                        </div>
                        @endif
                    </div>
                    @if($product->productTransaction->status_id != 9)
                   {{--  <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Buyer Approved Status:</label>
                            <span>
                                @if ($product->status == 0)
                                <label class="badge badge-warning">Pending for Approval</label>
                                @elseif ($product->status == 2)
                                <label class="badge badge-danger">In-Dispute</label>
                                @elseif ($product->status == 1)
                                <label class="badge badge-success">Buyer Approved</label>
                                @endif
                            </span>
                        </div>
                    </div> --}}
                    @endif

                    @if(in_array($current_status, [6, 7]) && auth()->user()->id == $product->seller_id)
                    <div class="row">
                        <div class="col-xl-12 col-md-12">
                            <label class="input-item-label">Transfered to Seller Reference no / Transaction Hash:</label>
                            <span>{{$product->productTransaction->admin_reference_no}}</span>
                        </div>
                    </div>
                    @endif

                </div><!-- .from-step-content -->

                @if($product->pocRequest != null)
                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>Request for POC Details</h4>
                    </div>
                </div>

                <div class="from-step-content">
                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">{{$amount_text}} Amount:</label>
                            <span>{{$product->pocRequest->poc_amount}} {{$product->productCurrency->currency}}</span>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Request POC Percentage:</label>
                            <span>{{$product->pocRequest->poc_percentage}}%</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Status:</label>
                            <span>
                                @if($product->pocRequest->status == 0)
                                Pending for Seller
                                @else
                                Seller already Responded
                                @endif
                            </span>
                        </div>

                    </div>

                </div>
                @endif

                <header class="chatbaox-header d-flex">
                    <div class="flex-fill d-flex">
                        <div class="image-holder mr-2 mb-2">
                            <img loading="lazy" src="{{$lastSenderImg}}" alt="{{$lastSender}}">
                        </div>
                        <div class="sender-detail d-flex flex-column">
                            <strong class="sender-name">{!!$lastSender!!}</strong>
                            <span class="sender-name">Dispute History</span>
                        </div>
                    </div>
                </header>
                <div class="chat-holder p-3 mb-3">
                    <ul class="chat-messages list-unstyled dispute-message" id="messages">

                        @foreach($current_thread->messages as $msg)
                        @if($msg->is_private == 1)
                        @php continue; @endphp
                        @endif

                        @php $msger = 'sender';@endphp

                        <li class="d-flex mb-2 msgesLi @if($msg->sender_id == Auth::user()->id) author-reply1 @endif">
                            <time class="time-holder">{{$msg->get_date()}}</time>
                            <div class="d-flex align-items-center img-name-wrapper">
                                <!-- <div class="image-holder mr-2 mb-2">
                                    @if($msg->is_admin == 1)
                                    <img loading="lazy" src="{{asset('frontend/dashboard/images/admin.png')}}" alt="Admin">
                                    @else
                                    <img loading="lazy" src="{{$msg->$msger->photo()}}" alt="{{$msg->$msger->full_name()}}">
                                    @endif
                                </div> -->
                                <div class="sender-detail inner-chat-box d-flex flex-column p-2">
                                    <strong class="sender-name">
                                        @if($msg->is_admin == 1)
                                        Admin
                                        @else
                                        {!!$msg->$msger->full_name()!!}
                                        @endif
                                    </strong>
                                    <span class="sender-name">{!!nl2br($msg->message)!!}</span>
                                </div>
                            </div>

                        </li>

                        @endforeach

                    </ul>
                </div>
                <hr>
                <div class="reply-box p-3">



                    <form class="form" name="msg-form" id="msg-form" action="{{route('send_dispute_message')}}" method="post">

                        @if($current_status == 8)
                        <div class="d-flex">

                            @csrf
                            <textarea type="text" rows="5" cols="5" id="send_message" name="message" class="flex-fill p-2 mr-2" placeholder="Type Message here" required></textarea>
                            <input type="hidden" name="ddd" value="123" />
                            <input type="hidden" name="thread_id" value="{{$current_thread->id}}" />
                            <input type="hidden" name="id" value="{{encode($current_thread->product_id)}}" />
                            <input type="hidden" name="buyer_id" value="{{$current_thread->buyer_id}}" />
                            <input type="hidden" name="seller_id" value="{{$current_thread->seller_id}}" />
                            @if(Auth::user()->id == $current_thread->seller_id )
                            <input type="hidden" name="receiver_id" value="{{$current_thread->buyer_id}}" />

                            @endif
                            @if(Auth::user()->id == $current_thread->buyer_id )
                            <input type="hidden" name="receiver_id" value="{{$current_thread->seller_id}}" />
                            @endif
                            <button type="submit" id="submitBttn">Send <i class="far fa-paper-plane"></i></button>
                        </div>
                        @endif
                    </form>



                </div>
            </div>

        </div>

        <div class="col-xl-4">
            <div class="chat-box">

                <form class="form" name="msg-form-dis" id="msg-form-dis" action="{{route('update_dispute_status')}}" method="post">
                    @csrf
                    @if(Auth::user()->id == $current_thread->buyer_id )
                    @if(count($dispute_transaction) > 0)
                    <div class="from-step-heading">
                        <div class="from-step-head">
                            <h4>Buyer Offer</h4>
                        </div>
                    </div>

                    <div class="from-step-content">

                        <div class="form-group">
                            <h4>Level: <strong>{{$dispute_transaction[0]->level}}</strong>
                                @if($product->productTransaction->status_id != 9)
                                @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp
                                @if($offer_expire_time > 0 && $dispute_transaction[0]->level == 1)
                                @if($dispute_transaction[0]->status != 1)<span class="btc-time">{{\Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time)}} hours left</span>@endif

                                @endif
                                @endif

                            </h4>
                        </div>
                        <div class="form-group">
                            <h4>Buyer offer: <strong>{{$dispute_transaction[0]->discount_offer}} {{$product->productCurrency->currency}}</strong></h4>
                        </div>
                        @if(count($dispute_transaction) > 1)
                        <div class="form-group">
                            <h4>Seller offer: <strong>{{$dispute_transaction[1]->discount_offer}} {{$product->productCurrency->currency}}</strong></h4>
                        </div>
                        @endif

                        @if(count($dispute_transaction) == 1)
                        @if($dispute_transaction[0]->status == 1)
                        <div class="form-group">
                            <strong>Offer Accepted By Seller</strong>
                        </div>
                        @elseif($dispute_transaction[0]->status == 2)
                        <div class="form-group">
                            <strong>Offer Rejected By Seller</strong>
                        </div>
                        @endif
                        @endif

                        @if(count($dispute_transaction) > 1)
                        @if($dispute_transaction[0]->status == 1 && $dispute_transaction[1]->discount_offer != null)
                        <div class="form-group">
                            <strong>Offer Accepted By Buyer</strong>
                        </div>
                        @elseif($dispute_transaction[0]->status == 2 && $dispute_transaction[1]->discount_offer != null)
                        <div class="form-group">
                            <strong>Offer Rejected By Buyer</strong>
                        </div>
                        @endif
                        @endif


                        @if($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id == $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Buyer (Sub-Admin decision)</strong>
                        </div>
                        @elseif($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != $dispute_transaction[0]->user_id )
                        <div class="form-group">
                            <strong>Winner: Seller (Sub-Admin decision)</strong>
                        </div>
                        @endif
                        @if($dispute_transaction[0]->winner_user_by_admin != null && $dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Buyer (Super-Admin decision)</strong>
                        </div>
                        @elseif($dispute_transaction[0]->winner_user_by_admin != null && count($dispute_transaction) > 1 && $dispute_transaction[0]->winner_user_by_admin != $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Seller (Super-Admin decision)</strong>
                        </div>
                        @endif


                        @if(count($dispute_transaction) > 1 && $dispute_transaction[0]->status == 0 && $dispute_transaction[0]->level == 1)

                        <div class="acc-btns">
                            <button type="submit" name="btn_status" value="accept" class="btn btn-sm btn-success">Accept</button>
                            <button type="submit" name="btn_status" value="reject" class="btn btn-sm btn-danger">Reject</button>
                        </div>
                        <br>
                        @endif


                        @if(count($dispute_transaction) == 1 && $dispute_transaction[0]->level == 1 && $dispute_transaction[0]->status == 0 )
                        @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp
                        @if($offer_expire_time > 0)


                        <div class="form-group">
                            <input type="hidden" name="level" value="1">
                            <label><input class="" type="checkbox" id="level" name="level" value="2">&nbsp;Skip to level 2</label>
                        </div><!-- .input-item -->
                        <div class="">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @endif
                        @endif



                        @if(count($dispute_transaction) > 0 && $dispute_transaction[0]->level == 2 && $dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != auth()->user()->id )
                        @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp

                        <div class="form-group">
                            <input type="hidden" name="level" value="2">
                            <label><input class="" type="checkbox" id="level" name="level" value="3">&nbsp;Skip to level 3</label>
                        </div><!-- .input-item -->
                        <div class="">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        @endif

                    </div>

                    @endif
                    @endif

                    @if(Auth::user()->id == $current_thread->seller_id )
                    @if(count($dispute_transaction) > 0)
                    <div class="from-step-heading">
                        <div class="from-step-head">
                            <h4>Buyer Offer</h4>
                        </div>
                    </div>
                    <div class="from-step-content">

                        <div class="form-group">
                            <h4>Level: <strong>{{$dispute_transaction[0]->level}}</strong>

                                @if($product->productTransaction->status_id != 9)
                                @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp
                                @if($offer_expire_time > 0 && $dispute_transaction[0]->level == 1)
                                @if($dispute_transaction[0]->status != 1)<span class="btc-time">{{\Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time)}} hours left</span>@endif

                                @endif
                                @endif
                            </h4>
                        </div>
                        <div class="form-group">
                            <h4>Buyer offer: <strong>{{$dispute_transaction[0]->discount_offer}} {{$product->productCurrency->currency}}</strong></h4>
                        </div>
                        @if(count($dispute_transaction) > 1)
                        <div class="form-group">
                            <h4>Seller offer: <strong>{{$dispute_transaction[1]->discount_offer}} {{$product->productCurrency->currency}}</strong></h4>
                        </div>
                        @endif
                        @if(count($dispute_transaction) == 1)
                        @if($dispute_transaction[0]->status == 1)
                        <div class="form-group">
                            <strong>Offer Accepted By Seller</strong>
                        </div>
                        @elseif($dispute_transaction[0]->status == 2)
                        <div class="form-group">
                            <strong>Offer Rejected By Seller</strong>
                        </div>
                        @endif
                        @endif
                        @if(count($dispute_transaction) > 1)
                        @if($dispute_transaction[0]->status == 1 && $dispute_transaction[1]->discount_offer != null)
                        <div class="form-group">
                            <strong>Offer Accepted By Buyer</strong>
                        </div>
                        @elseif($dispute_transaction[0]->status == 2 && $dispute_transaction[1]->discount_offer != null)
                        <div class="form-group">
                            <strong>Offer Rejected By Buyer</strong>
                        </div>
                        @endif
                        @endif

                        @if($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id == $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Buyer (Sub-Admin decision)</strong>
                        </div>
                        @elseif($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != $dispute_transaction[0]->user_id )
                        <div class="form-group">
                            <strong>Winner: Seller (Sub-Admin decision)</strong>
                        </div>
                        @endif
                        @if($dispute_transaction[0]->winner_user_by_admin != null && $dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Buyer (Super-Admin decision)</strong>
                        </div>
                        @elseif($dispute_transaction[0]->winner_user_by_admin != null && count($dispute_transaction) > 1 && $dispute_transaction[0]->winner_user_by_admin != $dispute_transaction[0]->user_id)
                        <div class="form-group">
                            <strong>Winner: Seller (Super-Admin decision)</strong>
                        </div>
                        @endif

                        @if(count($dispute_transaction) == 1 && $dispute_transaction[0]->status == 0 && $dispute_transaction[0]->level == 1)

                        <div class="acc-btns">
                            <button type="submit" name="btn_status" value="accept" class="btn btn-sm btn-success">Accept</button>
                            <button type="submit" name="btn_status" value="reject" class="btn btn-sm btn-danger">Reject</button>
                        </div>
                        <div class="gap-or"><span>OR</span></div>

                        <div class="form-group field-inline">
                            <label>Seller Offer</label>
                            <input type="number" step="0.01" id="discount_offer" name="discount_offer" max="{{$product->productTransaction->total_amount}}" value="" />
                        </div>


                        <div class="">
                            <button type="submit" class="btn btn-primary">Make an Offer</button>
                        </div>
                        @endif

                        @if(count($dispute_transaction) > 0 && $dispute_transaction[0]->level == 2 && $dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != auth()->user()->id )
                        @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp


                        <div class="form-group">
                            <input type="hidden" name="level" value="2">
                            <label><input class="" type="checkbox" id="level" name="level" value="3">&nbsp;Skip to level 3</label>
                        </div><!-- .input-item -->

                        <div class="">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>

                        @endif



                    </div>
                    @endif
                    @endif

                    <input type="hidden" name="thread_id" value="{{$current_thread->id}}" />
                    <input type="hidden" name="id" value="{{encode($current_thread->product_id)}}" />
                    <input type="hidden" name="buyer_id" value="{{$current_thread->buyer_id}}" />
                    <input type="hidden" name="seller_id" value="{{$current_thread->seller_id}}" />
                    @if(Auth::user()->id == $current_thread->seller_id )
                    <input type="hidden" name="receiver_id" value="{{$current_thread->buyer_id}}" />

                    @endif
                    @if(Auth::user()->id == $current_thread->buyer_id )
                    <input type="hidden" name="receiver_id" value="{{$current_thread->seller_id}}" />
                    @endif
                </form>
            </div>
        </div>
    </div>

</div>
</div><!-- .user-content -->

@endsection
