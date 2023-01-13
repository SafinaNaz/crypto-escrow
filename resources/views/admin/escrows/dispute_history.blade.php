@extends('admin.app')
@section('content')
@php
$amount_text = '';

if($product->currency_id == 2) {
    $sender_wallet = settingValue('monero_address');
} else {
    $sender_wallet = settingValue('btc_address');
}

$poc_amount_text = '';
if($product->productTransaction->transactionStatus->status == 'In-Dispute') {
    $amount_text = 'Payable';
}
if($product->productTransaction->transactionStatus->status == 'Dispute Finished') {
    $amount_text = 'Paid';
}
if(isset($product->pocRequest->poc_amount)) {

    if($product->pocRequest->status == 1) {
         if($product->productTransaction->transactionStatus->status == 'Escrowed') {
             $poc_amount_text = 'Payable';
        }
        if($product->productTransaction->transactionStatus->status == 'Transfered to Seller') {
             $poc_amount_text = 'Paid';
        }
        if($product->productTransaction->transactionStatus->status == 'Completed') {
            $poc_amount_text = 'Paid';
        }
        
        if($product->productTransaction->transactionStatus->status == 'In-Dispute') {
            $poc_amount_text = 'Payable';
        }
        if($product->productTransaction->transactionStatus->status == 'Dispute Finished') {
            $poc_amount_text = 'Paid';
        }
       
    }

}

if($type == 'finish') {
    $msg_data = trans_admin_msg($product->id,\Auth::user()->id,1);
}

@endphp
<style>
    .chat-holder {
        min-height: 400px;
        max-height: 400px;
        overflow-y: auto;
    }


    .chat-holder .sender-detail {
        background: #f1f1ff;
        border-radius: 7px;
    }

    .chat-holder img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    /* .chat-holder .msgesLi {
        justify-content: space-between;
        flex-direction: unset;
        align-items: center;
    } */

    .chat-holder .chat-messages .author-reply {
        flex-direction: row-reverse;
        align-items: center;
        justify-content: space-between;
    }

    ul.dispute-message li .time-holder {
        margin-right: 15px;
        border-right: 2px dashed#7d4399;
        width: 170px;
        position: relative;
        margin-bottom: 27px;
    }

    ul.dispute-message li .time-holder:before {
        content: "";
        position: absolute;
        top: -20px;
        right: -1px;
        transform: translate(50%, -50%);
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #8a8a8a;
        mix-blend-mode: darken;
    }

    ul.dispute-message li .img-name-wrapper {
        margin-bottom: 40px;
        width: 70%;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{$product->transaction_id}}'s Dispute History</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/escrow') }}">Escrow</a></li>
                    <li class="breadcrumb-item active">Dispute History</li>
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
                            Transaction Detail
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class=" col-md-12   p-t-30 ">
                        <br />
                        @if ($errors->any())
                        <div class="alert alert-danger w-100">
                            <dd>
                                @foreach ($errors->all() as $error)
                                <dl class="mb-0">{{ $error }}</dl>
                                @endforeach
                            </dd>
                        </div>
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <label class="input-item-label">Transaction ID:</label>
                                    <span>@php echo $product->transaction_id; @endphp</span>
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
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">Commission:</label>
                                    <span>{{$product->productTransaction->commission}} {{$product->productCurrency->currency}} ({{$product->commission}}%)</span>
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
                                    <span><label class="badge badge-primary">{{$product->productTransaction->transactionStatus->status}}</label></span>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">Completion Time:</label>
                                    <span><label class="badge badge-info">{{$product->completion_days()}}</label></span>
                                </div>
                                @if (in_array($product->productTransaction->status_id, [2, 3]))
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">Completion Date:</label>
                                    <span>{{date('D d F, Y', strtotime($product->completion_time))}}</span>
                                </div>
                                @endif
                            </div>
                            @if($product->productTransaction->status_id != 9)
                            {{-- <div class="row">
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

                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <label class="input-item-label">Reference no / Transaction Hash:</label>
                                    <span>{{$product->productTransaction->reference_no}}</span>
                                </div>
                            </div>

                            @if (in_array($current_status, [6, 7]))
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <label class="input-item-label">Transfered to Seller Reference no / Transaction Hash:</label>
                                    <span>{{$product->productTransaction->admin_reference_no}}</span>
                                </div>
                            </div>
                            @endif

                        </div>

                        <div class="card-body">
                            @if($product->pocRequest != null)
                            <div class="from-step-heading">
                                <div class="from-step-head">
                                    <h4>Request for POC Details</h4>
                                </div>
                            </div>

                            <div class="from-step-content">
                                <div class="row">
                                    <div class="col-xl-4 col-md-4">
                                        <label class="input-item-label">{{$poc_amount_text}} Amount:</label>
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

                                <div class="row">
                                    <div class="col-xl-12 col-md-12">
                                        <label class="input-item-label">POC Reference no / POC Transaction Hash:</label>
                                        <span>{{$product->pocRequest->reference_no}}</span>
                                    </div>
                                </div>

                            </div>
                            <div class="gaps-2x"></div>
                            @endif
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Dispute History
                                </h3>
                            </div>
                            <div class="chat-holder p-3 mb-3">
                                <ul class="chat-messages list-unstyled dispute-message" id="messages">

                                    @foreach($current_thread->messages as $msg)
                                    @if($msg->is_private == 1)
                                    @php continue; @endphp
                                    @endif

                                    @php $msger = 'sender';@endphp

                                    <li class="d-flex mb-2 msgesLi @if($msg->is_admin == 1) author-reply1 @endif">
                                        <time class="time-holder">{{$msg->get_date()}}</time>
                                        <div class="d-flex align-items-center img-name-wrapper">

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
                        </div>


                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    @if($product->productTransaction->status_id == 8 && $product->productTransaction->status_id != 9)
                                    @if($type == 'finish')
                                    Dispute Finish Details
                                    @else
                                    Send Message
                                    @endif
                                    @endif
                                    @if($product->productTransaction->status_id == 9)
                                    Dispute Finish Details
                                    @endif
                                </h3>
                            </div>

                            @if(count($dispute_transaction) > 0)
                            <br>
                            <div class="alert alert-info">
                                <strong>Level : {{$dispute_transaction[0]->level}}</strong>
                                <br>
                                <strong>Buyer offer : {{$dispute_transaction[0]->discount_offer}}</strong>
                                <br>
                                @if(count($dispute_transaction) > 1)
                                <strong>Seller offer : {{$dispute_transaction[1]->discount_offer}}</strong>
                                <br>
                                @endif
                                @if($product->productTransaction->status_id != 9)
                                <strong>Time to Left : {{\Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time)}} hours</strong>
                                <br>
                                @endif
                                @if(count($dispute_transaction) == 1)
                                @if($dispute_transaction[0]->status == 1)
                                <strong>Offer Accepted By Seller</strong>
                                <br>
                                @elseif($dispute_transaction[0]->status == 2)
                                <strong>Offer Rejected By Seller</strong>
                                <br>
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
                                <strong>Winner: Buyer (Sub-Admin decision)</strong>
                                <br>
                                @elseif($dispute_transaction[0]->winner_id != null && count($dispute_transaction) > 1 && $dispute_transaction[0]->winner_id == $dispute_transaction[1]->user_id)
                                <strong>Winner: Seller (Sub-Admin decision)</strong>
                                <br>
                                @endif

                                @if($dispute_transaction[0]->winner_user_by_admin != null && $dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[0]->user_id)
                                <strong>Winner: Buyer (Super-Admin decision)</strong>
                                <br>
                                @elseif($dispute_transaction[0]->winner_user_by_admin != null && count($dispute_transaction) > 1 &&$dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[1]->user_id)
                                <strong>Winner: Seller (Super-Admin decision)</strong>
                                <br>
                                @endif

                            </div>

                            @endif


                            <form id="profile-form" name="profile-form" class="form-horizontal form-validate setting-form" novalidate="novalidate" name="msg-form" id="msg-form" action="{{route('admin.messages.send_dispute_message')}}" method="post">
                                    <div class="card-body">
                                        @csrf

                                     {{--    @if($type == 'finish' && count($dispute_transaction) > 0 && ($dispute_transaction[0]->winner_id != null || $dispute_transaction[0]->winner_id == null))
                                        @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp --}}
                                          @if($type == 'finish' && count($dispute_transaction) > 0 && ($dispute_transaction[0]->winner_id != null || $dispute_transaction[0]->winner_id == null))
                                        @php $offer_expire_time = \Carbon\Carbon::now()->diffInHours($dispute_transaction[0]->offer_expire_time); @endphp
                                        <div class="form-group">

                                            <label for="ref_no" class="col-sm-3 control-label">Sender Wallet Address *</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="admin_sender_wallet_address" name="admin_sender_wallet_address" value="{{$sender_wallet}}" >
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <label for="ref_no" class="col-sm-3 control-label">Receiver Wallet Address *</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="admin_receiver_wallet_address" name="admin_receiver_wallet_address" value="" >
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">

                                            <label for="ref_no" class="col-sm-3 control-label">Payment Ref. no / Transaction Hash *</label>
                                            <div class="col-sm-8">
                                                <input class="form-control" type="text" id="admin_reference_no" name="admin_reference_no" value="">
                                            </div>
                                        </div>
                                       
                                        <div class="form-group">

                                            <label for="ref_no" class="col-sm-3 control-label">Refund Amount *</label>
                                            <div class="col-sm-8">

                                                <input class="form-control" type="number" step="0.01" id="refund_amount" name="refund_amount" value="@if(count($dispute_transaction) == 1){{$dispute_transaction[0]->discount_offer}}@elseif($dispute_transaction[1]->discount_offer != null){{$dispute_transaction[1]->discount_offer}}@endif" data-rule-required="true" aria-required="true" required>
                                            </div>
                                        </div>
                                        @if(count($dispute_transaction) > 0 && $dispute_transaction[0]->level == 3 && ($dispute_transaction[0]->winner_id != null || $dispute_transaction[0]->winner_id == null))
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Mark as Winner</label>
                                            <div class="col-sm-9">

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="winner_user_by_admin" id="winner_user_by_admin1" value="{{$product->seller_id}}">
                                                    <label for="winner_user_by_admin1" class="custom-control-label">Seller</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="winner_user_by_admin" id="winner_user_by_admin2" value="{{$product->buyer_id}}">
                                                    <label for="winner_user_by_admin2" class="custom-control-label">Buyer</label>
                                                </div>

                                            </div>
                                        </div>
                                      
                                        @endif
                                         @endif
                                        @if(count($dispute_transaction) > 0 && $dispute_transaction[0]->level == 2 && $dispute_transaction[0]->winner_id == null)
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Mark as Winner</label>
                                            <div class="col-sm-9">

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="winner" id="winner1" value="{{$product->seller_id}}">
                                                    <label for="winner1" class="custom-control-label">Seller</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" name="winner" id="winner2" value="{{$product->buyer_id}}">
                                                    <label for="winner2" class="custom-control-label">Buyer</label>
                                                </div>

                                            </div>
                                        </div>
                                        @endif
                                       

                                        @if($product->productTransaction->status_id == 8 && $product->productTransaction->status_id != 9)
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="col-sm-12 control-label">Message *</label>

                                                <textarea rows="5" cols="5" class="form-control" name="message" id="message" placeholder="Type Message here" data-rule-required="true" aria-required="true" required>{{@$msg_data->message}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{encode($current_thread->product_id)}}" />
                                                <input type="hidden" name="thread_id" value="{{$current_thread->id}}" />
                                                <input type="hidden" name="type" value="{{$type}}" />
                                                <input type="hidden" name="buyer_id" value="{{$current_thread->buyer_id}}" />
                                                <input type="hidden" name="seller_id" value="{{$current_thread->seller_id}}" />
                                                <input type="hidden" name="receiver_id" value="{{$current_thread->buyer_id}}" />
                                                @if($type == 'finish')
                                                <button type="submit" id="submitBttn" class="btn btn-primary btn-block">Mark as Finish</button>
                                                @else
                                                <button type="submit" id="submitBttn" class="btn btn-success btn-block">Send <i class="far fa-paper-plane"></i></button>

                                                @endif

                                                @if (auth()->user()->can('Dispute Level 3'))
                                                @if(@$type == '' && count(@$dispute_transaction) > 0 && $dispute_transaction[0]->level >= 2 || @$dispute_transaction[0]->status == 1)
                                                <a class="btn btn-danger btn-block" style="width:200px" href="{{url('admin/dispute-history/'.encode($current_thread->product_id).'/finish')}}">Mark Dispute Finished</a>
                                                @endif
                                                @endif

                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    </form>

                            </div>



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
