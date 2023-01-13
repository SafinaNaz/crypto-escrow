@extends("frontend.layouts.dashboard_master")
@section('content')
<!-- DataTables -->
@php
$msg = '';
$msg = trans_before_msg($product->id,2,1,\Auth::user()->id);
$sender_info = user_info(\Auth::user()->id);
if($product->currency_id == 2) {
    $sender_wallet = $sender_info->monero_address;
     $receiver_wallet = settingValue('monero_address');
} else {
    $sender_wallet = $sender_info->btc_address;
    $receiver_wallet = settingValue('btc_address');
}
@endphp
<div class="user-panel">

    <form nanme="etl-form" action="{{url('update-poc-request/' . encode($product->id))}}" method="POST" id="etl-form" class="form">
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
            <div class="from-step-item">

                <div class="from-step-heading">
                 
                    <div class="from-step-head">
                        <h4>Respond To POC</h4>

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
                            <label class="input-item-label">Payable Amount:</label>
                            <span>{{$product->pocRequest->poc_amount}} {{$product->productCurrency->currency}}</span>
                        </div>
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Request POC Percentage:</label>
                            <span>{{$product->pocRequest->poc_percentage}}%</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-md-4">
                            <label class="input-item-label">Currency:</label>
                            <span>{{$product->productCurrency->currency}}</span>
                        </div>
                        @if($product->productTransaction->status_id != 9)
                     {{--    <div class="col-xl-4 col-md-4">
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
                        </div> --}}
                        @endif
                    </div>




                    <div class="gaps-2x"></div>
                    <div class="row">
                       
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
                                <input class="input-bordered" type="text" id="receiver_wallet_address" name="receiver_wallet_address" value="{{$receiver_wallet}}">
                            </div><!-- .input-item -->
                        </div><!-- .col -->
                       

                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">

                                <label for="ref_no" class="input-item-label">Payment Ref. no / Transaction Hash *</label>
                                <input class="input-bordered" type="text" id="reference_no" name="reference_no" value="" >
                            </div><!-- .input-item -->
                        </div><!-- .col -->

                        <div class="col-xl-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <input type="hidden" name="id" value="{{$product->pocRequest->id}}" />
                                <label for="ref_no" class="input-item-label">Enter Message *</label>
                                <textarea rows="7" class="input-bordered" id="message" name="message" data-rule-required="true" placeholder="Enter your message" aria-required="true" required>{{@$msg->message}}</textarea>
                            </div><!-- .input-item -->
                        </div>

                    </div><!-- .row -->
                    <div class="from-step-content">
                        <div class="gaps-2x"></div><!-- 20px gap -->
                        <button type="submit" class="btn btn-primary">Submit Details</button>
                        <a href="{{url('/escrows')}}" class="btn btn-warning">Cancel</a>
                        <div class="gaps-2x"></div><!-- 20px gap -->
                    </div>

                </div><!-- .from-step-content -->
            </div><!-- .from-step-item -->

        </div><!-- .from-step -->
    </form>
</div><!-- .user-content -->

@endsection