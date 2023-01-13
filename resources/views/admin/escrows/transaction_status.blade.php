@extends('admin.app')
@section('content')

<link rel="stylesheet" href="{{ _asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
@php
$amount_text = '';
$poc_amount_text = '';
$msg = '';
$msg = trans_admin_msg($product->id,\Auth::user()->id,0);
if($product->productTransaction->transactionStatus->status == 'Escrowed') {
    $amount_text = 'Payable';
}
if($product->productTransaction->transactionStatus->status == 'Completed') {
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
if($product->productTransaction->transactionStatus->status == 'In-Dispute') {
    $amount_text = 'Payable';
}
if($product->productTransaction->transactionStatus->status == 'Dispute Finished') {
    $amount_text = 'Paid';
}
if($product->productTransaction->transactionStatus->status == 'Cancelled' || $product->productTransaction->transactionStatus->status == 'Rejected by Admin') {
    $amount_text = 'Payable';
    $poc_amount_text = 'Payable';
}
$receiver_info = user_info($product->seller_id);
if($product->currency_id == 2) {
    $sender_wallet = settingValue('monero_address');
   $receiver_wallet = $receiver_info->monero_address;
} else {
    $sender_wallet = settingValue('btc_address');
    $receiver_wallet = $receiver_info->btc_address;
}


@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Transaction Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/admin/cms-pages') }}">Escrows</a></li>
                    <li class="breadcrumb-item active">{{$product->transaction_id}}</li>
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
                            {{$product->transaction_id}}
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <div class=" col-md-10 col-md-offset-1  p-t-30 ">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <br />
                        @php $transactionStatus = $product->productTransaction->status_id;@endphp
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-md-12">
                                    <label class="input-item-label">Transaction ID:</label>
                                    <span>@php echo $product->transaction_id @endphp</span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">Product:</label>
                                    <span>{{$product->transaction_id}}</span>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">
                                       

                                        {{$amount_text}} Amount:</label>
                                    <span>
                                        @if(isset($product->pocRequest->poc_amount) && !empty($product->pocRequest->poc_amount))
                                        {{$product->productTransaction->total_amount + $product->pocRequest->poc_amount}} {{$product->productCurrency->currency}}
                                        @else
                                        {{$product->productTransaction->total_amount}} {{$product->productCurrency->currency}}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <label class="input-item-label">Commission:</label>
                                    <span>{{$product->productTransaction->commission}} {{$product->productCurrency->currency}} ({{$product->commission}}%)</span>
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
                                @if (in_array($transactionStatus, [2, 3]))
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

                        <div class="card-body">
                            @if (!in_array($product->productTransaction->status_id, [4, 5, 6, 7, 8, 9]))
                            <a class="btn btn-secondary btn-icon" href="{{url('admin/transaction-status/' . encode($product->id) . '/reject')}}">Reject Transaction</a>
                            <a class="btn btn-secondary btn-icon" href="{{url('admin/transaction-status/' . encode($product->id). '/cancel')}}">Cancel Escrow</a>
                            @endif
                             @if (!in_array($product->productTransaction->status_id, [1, 4, 5, 6, 7, 8]))
                            <a class="btn btn-secondary btn-icon" href="{{url('admin/transaction-status/' . encode($product->id) . '/transfer')}}">Transfered to Seller</a>
                            @endif


                            @if (in_array($product->productTransaction->status_id, [8, 9]))
                            <a class="btn btn-secondary btn-icon" href="{{url('admin/dispute-history/' . encode($product->id))}}">Dispute History</a>
                            @endif
                        </div>

                        @if (!in_array($current_status, [6, 7, 8]))
                        @if($status != 'detail')
                        <form id="profile-form" name="profile-form" method="POST" action="{{url('admin/update-transaction/' . encode($product->id).'/'.$status)}}" class="form-horizontal form-validate setting-form" novalidate="novalidate" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <input type="hidden" name="id" value="{{$product->productTransaction->id}}" />

                                @if($status == 'transfer')
                                
                                   <div class="form-group">

                                    <label for="ref_no" class="col-sm-3 control-label">Sender Wallet Address *</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" id="admin_sender_wallet_address" name="admin_sender_wallet_address" value="{{$sender_wallet}}" >
                                    </div>
                                </div>
                                   <div class="form-group">

                                    <label for="ref_no" class="col-sm-3 control-label">Receiver Wallet Address *</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" id="admin_receiver_wallet_address" name="admin_receiver_wallet_address" value="{{$receiver_wallet}}" >
                                    </div>
                                </div>
                               
                                <div class="form-group">

                                    <label for="ref_no" class="col-sm-3 control-label">Payment Ref. no / Transaction Hash *</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" type="text" id="admin_reference_no" name="admin_reference_no" value=""  >
                                    </div>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Enter Message *</label>
                                    <div class="col-sm-8">

                                        <textarea data-rule-required="true" aria-required="true" id="message" name="message" class='form-control' rows="7">{{@$msg->message}}</textarea>

                                    </div>
                                </div>

                                <div class="form-actions text-right">

                                    <a href="{{url('/admin/escrows')}}" class="btn btn-default btn-cancel"> <i class="icons icon-arrow-left-circle"></i> Cancel</a>

                                    <button type="submit" class="btn btn-primary"><i class="icons icon-check"></i> Update Status</button>

                                </div>
                            </div>
                        </form>
                        @endif
                        @endif

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
