@extends("frontend.layouts.dashboard_master")
@section('content')
@php

$msg = '';
$msg = trans_before_msg($product->id,0,1,\Auth::user()->id);

@endphp

<!-- DataTables -->
@php 
$amount_text = '';
$poc_amount_text = '';
$transactionStatus = $product->productTransaction->status_id;

$sender_info = user_info(\Auth::user()->id);
$receiver_info = '';
if($product->currency_id == 2) {
    $sender_wallet = $sender_info->monero_address;
     $receiver_wallet = settingValue('monero_address');
} else {
    $sender_wallet = $sender_info->btc_address;
    $receiver_wallet = settingValue('btc_address');
}


if(!empty(json_decode(@$dispute_transaction))) {
   $msg = trans_before_msg($product->id,1,2,\Auth::user()->id);
}





$payment_status = '';

// payment status is pending
// in case of seller
if (auth()->user()->user_type == 1) {
    if($transactionStatus == 1) {
        $amount_text = 'Receivable';
    }
    if($transactionStatus == 2) {
        $amount_text = 'Received';
    }
    if($transactionStatus == 8) {
        $amount_text = 'Receivable';
    }

} elseif(auth()->user()->user_type == 2) {

    if($transactionStatus == 1) {
        $amount_text = 'Payable';
    }
    if($transactionStatus == 2) {
        $amount_text = 'Paid';
    }
    if($transactionStatus == 8) {
        $amount_text = 'Payable';
    }
}
if($product->pocRequest != null) {
    if (auth()->user()->user_type == 1) {
        if($product->pocRequest->status == 0) {
            $poc_amount_text = 'Payable';
        }
  
        if($product->pocRequest->status == 1) {
            if(!empty($dispute_transaction)) {
                $poc_amount_text = 'Payable';
            } else {
                $poc_amount_text = 'Paid';
            }
            
        }


    }

    if (auth()->user()->user_type == 2) {
        if($product->pocRequest->status == 0) {
            $poc_amount_text = 'Receivable';
        }
        if($product->pocRequest->status == 1) {
            if(!empty($dispute_transaction)) {
                $poc_amount_text = 'Receivable';
            } else {
                $poc_amount_text = 'Received';
            }
            
        }
      

    }
}
@endphp
@if(!empty(json_decode(@$dispute_transaction)))

@if($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id == $dispute_transaction[0]->user_id)
@php
$amount_text = 'Receivable';
if($product->pocRequest != null) {
    $poc_amount_text = 'Received';
}
@endphp
@elseif($dispute_transaction[0]->winner_id != null && $dispute_transaction[0]->winner_id != $dispute_transaction[0]->user_id )
@php
$amount_text = 'Payable';
if($product->pocRequest != null) {
    $poc_amount_text = 'Payable';
}
@endphp
@endif
@if($dispute_transaction[0]->winner_user_by_admin != null && $dispute_transaction[0]->winner_user_by_admin == $dispute_transaction[0]->user_id)
@php
$amount_text = 'Receivable';
if($transactionStatus == 9) {
    $amount_text = 'Received';
}
if($product->pocRequest != null) {
    $poc_amount_text = 'Receivable';
    if($transactionStatus == 9) {
        $poc_amount_text = 'Received';
    }
}
@endphp
@elseif($dispute_transaction[0]->winner_user_by_admin != null && count($dispute_transaction) > 1 && $dispute_transaction[0]->winner_user_by_admin != $dispute_transaction[0]->user_id)
@php
$amount_text = 'Payable';
if($transactionStatus == 9) {
    $amount_text = 'Paid';
}
if($product->pocRequest != null) {
    $poc_amount_text = 'Payable';
    if($transactionStatus == 9) {
        $poc_amount_text = 'Paid';
    }
}


@endphp
@endif
@endif
@php
if(auth()->user()->user_type == 1) {
    if($transactionStatus == 4 || $transactionStatus == 5) {
        $amount_text = 'Payable';
    }
    if($product->pocRequest != null) {
        $poc_amount_text = 'Payable';
    }

} elseif(auth()->user()->user_type == 2) {
    if($transactionStatus == 4 || $transactionStatus == 5) {
        $amount_text = 'Receivable';
    }
    if($product->pocRequest != null) {
        $poc_amount_text = 'Receivable';
    }
}
@endphp


<div class="user-panel">

    <div class="heading-block d-flex justify-content-between">
        <h2>{{$product->transaction_id}}</h2>
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
        <div class="from-step-item">

            <div class="from-step-heading">
                <div class="from-step-head">
                    <h4>Transaction Details</h4>

                </div>
            </div>

            <div class="from-step-content">
                
                <div class="gaps-2x"></div>
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <label class="input-item-label">Transaction ID:</label>
                        <span>@php echo $product->transaction_id @endphp</span>
                    </div>

                </div>
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
                    @if (in_array($transactionStatus, [2, 3]))
                    <div class="col-xl-4 col-md-4">
                        <label class="input-item-label">Completion Date:</label>
                        <span>{{date('D d F, Y', strtotime($product->completion_time))}}</span>
                    </div>
                    @endif
                </div>
                @if($product->term_conditions != null)
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <label class="input-item-label">Term & Conditions:</label>
                        <span>{!!nl2br($product->term_conditions)!!}</span>
                    </div>
                </div>
                @endif
                @if($product->productTransaction->status_id != 9)
              {{--   <div class="row">
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

            </div>
            <div class="gaps-2x"></div>
            @endif

            <div class="from-step-content">



                @if (auth()->user()->user_type == 2)

                @if($product->buyer_request_poc == 0 && $product->pocRequest == null)
                @if (in_array($transactionStatus, [2, 3]))
                <a class="btn btn-primary" href="{{url('transaction-detail/' . encode($product->id).'/request-poc')}}">Request For POC</a>
                @endif
                @endif
                @if ($product->status == 1 && $transactionStatus == 1)
                    @if(empty($sender_wallet) && empty($receiver_wallet))
                       @php 
                        $payment_status = 'Payment'
                       @endphp
                    @else 
                     @php 
                        $payment_status = 'Transaction'
                       @endphp
                    @endif
                    <a class="btn btn-secondary" href="{{url('transaction-detail/' . encode($product->id).'/update')}}">Update {{$payment_status}} Status</a>
                @endif
                

                @if (in_array($transactionStatus, [2, 3]))
                <a class="btn btn-secondary" href="{{url('transaction-detail/' . encode($product->id).'/cancel')}}">Cancel Escrow</a>
                @endif


                    {{-- @if (time() < strtotime($product->completion_time) && in_array($transactionStatus, [2, 3])) --}}
                    @if (in_array($transactionStatus, [2, 3]))

                    <a class="btn btn-secondary" href="{{url('dispute/' . encode($product->id))}}">Create Dispute</a>
                    @endif


                    @endif

                    <!-- SELLER  -->

                    @if (auth()->user()->user_type == 1)
                    @if($product->buyer_request_poc == 1)

                    @if ($product->pocRequest->status == 0)
                    <a class="btn btn-primary" href="{{url('respond-poc/' . encode($product->id))}}">Respond to POC</a>
                    @endif
                    @endif

                    @if (in_array($transactionStatus, [1, 2, 3]))

                    <a class="btn btn-secondary" href="{{url('transaction-detail/' . encode($product->id). '/cancel')}}">Cancel Escrow</a>
                    @endif
                    @if ($transactionStatus == 6)
                    <a class="btn btn-secondary" href="{{url('transaction-detail/' . encode($product->id). '/complete')}}">Complete Transaction</a>
                    @endif
                    @endif

                    @if($product->status == 2)
                    <a class="btn btn-primary btn-icon" href="{{url('dispute-messages/' . encode($product->id))}}" title="View Dispute">View Dispute History</a>
                    @endif

            </div>

            @if($current_status != 7 && $current_status != 9)
            <div class="from-step-content">

                @if($status != 'detail' && $status != 'request-poc' && $status != 'dispute' && $status != 'deposit')
                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>Update Status as {{$status}}</h4>
                    </div>
                </div>
                <form nanme="etl-form" action="{{url('update-transaction/' . encode($product->id).'/'.$status)}}" method="POST" id="etl-form" class="form">
                    @csrf
                    <div class="gaps-2x"></div>
                    <div class="row">
                        @if($status == 'update')
                        
                           <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">  
                                
                                Sender Wallet Address *</label>
                                <input class="input-bordered" type="text" id="sender_wallet_address" name="sender_wallet_address" value="{{$sender_wallet}}">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">Receiver Wallet Address *</label>
                                <input class="input-bordered" type="text" id="receiver_wallet_address" name="receiver_wallet_address" value="{{$receiver_wallet}}">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                       
                         
                      
                         <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">Payment Ref. no / Transaction Hash *</label>
                                <input class="input-bordered" type="text" id="reference_no" name="reference_no" value="">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                        @endif
                       

                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <input type="hidden" name="id" value="{{$product->productTransaction->id}}" />
                                <label for="ref_no" class="input-item-label">Enter Message *</label>
                                <textarea rows="7" class="input-bordered" id="message" name="message" data-rule-required="true" placeholder="Enter your message" aria-required="true" required >{{@$msg->message}}</textarea>
                            </div><!-- .input-item -->
                        </div>

                    </div><!-- .row -->
                    <div class="from-step-content">
                        <div class="gaps-2x"></div><!-- 20px gap -->
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <a href="{{url('/escrows')}}" class="btn btn-warning">Cancel</a>
                        <div class="gaps-2x"></div><!-- 20px gap -->
                    </div>
                </form>
                @endif

                @if($status == 'request-poc' && $product->pocRequest == null)
                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>Request for POC</h4>
                    </div>
                </div>
                <form class="poc-form" method="post" action="{{route('request.poc')}}" name="" id="">
                    @csrf
                    <input type="hidden" name="id" value="{{encode($product->id)}}" />
                    Request for (POC)
                    <div class="form-group">
                        <select class="form-control" name="buyer_request_poc" required>
                            <option value="" selected>Select % For POC</option>
                            @for($j = 10; $j <= 100;$j+=10) <option value="{{$j}}">{{$j}}%</option>
                                @endfor
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
                @endif
                 @if($status == 'deposit')
                <div class="from-step-heading">
                    <div class="from-step-head">
                        <h4>{{ucfirst($status)}} Amount to access Dispute Level 3</h4>
                    </div>
                </div>
                <form nanme="etl-form" action="{{url('update-transaction/' . encode($product->id).'/'.$status)}}" method="POST" id="etl-form" class="form">
                    @csrf
                    <div class="gaps-2x"></div>
                    <div class="row">
                        {{-- @if(empty($sender_dis_wallet) && empty($receiver_dis_wallet)) --}}
                           <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">  Sender
                                Wallet Address *</label>
                                <input class="input-bordered" type="text" id="sender_wallet_address" name="sender_wallet_address" value="{{$sender_wallet}}">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                          <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">Receiver Wallet Address *</label>
                                <input class="input-bordered" type="text" id="receiver_wallet_address" name="receiver_wallet_address" value={{$receiver_wallet}}"">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                        {{-- @endif --}}

                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">Payment Ref. no / Transaction Hash *</label>
                                <input class="input-bordered" type="text" id="reference_no" name="reference_no" value="" >
                            </div><!-- .input-item -->
                        </div><!-- .col -->


                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <input type="hidden" name="pt_id" value="{{$product->productTransaction->id}}" />
                                <label for="ref_no" class="input-item-label">Enter Message *</label>
                                <textarea rows="7" class="input-bordered" id="message" name="message" data-rule-required="true" placeholder="Enter your message" aria-required="true" required>{{@$msg->message}}</textarea>
                                <input type="hidden" name="deposit_amount" value="{{settingValue('deposit_amount')}}">

                            </div><!-- .input-item -->
                        </div>

                    </div><!-- .row -->
                    <div class="from-step-content">
                        <div class="gaps-2x"></div><!-- 20px gap -->
                        <button type="submit" class="btn btn-primary">Update Status</button>
                        <a href="{{url('/escrows')}}" class="btn btn-warning">Cancel</a>
                        <div class="gaps-2x"></div><!-- 20px gap -->
                    </div>
                </form>
                @endif




            </div>
            @endif


        </div><!-- .from-step-item -->
        @if($status == 'dispute')
        <div class="row">
            <div class="col-xl-12">
                <div class="chat-box">
                    <div class="from-step-heading">


                        <div class="from-step-head">
                            <h4>Create Dispute</h4>
                        </div>
                    </div>
                    <div class="from-step-content">
                        <form class="create-dispute" method="post" action="{{route('create-dispute')}}" name="create-dispute" id="create-dispute">
                        @csrf
                            <div class="row">
                                <div class="col-lg-3 col-md-5">
                                    <label>Make and Offer (Optional)</label>
                                </div>
                                <div class="col-lg-4 col-md-7">
                                    <div class="form-group">
                                        <input class="form-control" type="number" step="0.01" id="discount_offer" name="discount_offer" max="{{$product->productTransaction->total_amount}}" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="hidden" name="level" value="1">
                                        <label><input class="" type="checkbox" id="level" name="level" value="2"></label>
                                        <label for="level" class="input-item-label">Skip to level 2</label>&nbsp;&nbsp;

                                    </div><!-- .input-item -->
                                </div>
                            </div>
                            <input type="hidden" name="type" value="dispute" />
                            <input type="hidden" name="product_id" value="{{$product->id}}" />
                            <input type="hidden" name="id" value="{{$product->productTransaction->id}}" />
                            <input type="hidden" name="buyer_id" value="{{$product->buyer_id}}" />
                            <input type="hidden" name="seller_id" value="{{$product->seller_id}}" />
                            <div class="text-right pb-3 mbl-padd">
                                <button type="submit" class="btn btn-primary btn-mbl">Create Dispute</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div><!-- .from-step -->

</div><!-- .user-content -->

@endsection
