@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
            <h2>Dispute History</h2>

        </div>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Transaction ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Seller</th>
                        <th>Buyer</th>
                        {{-- <th>Escrow Fee Payer</th> --}}
                        <th>Buyer Status</th>
                        <th>Completion days</th>
                        <th>Transaction Status</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @if(count($all_products) > 0)
                    @foreach($all_products as $row)
                    <tr>
                        <td>{{encode($row->id)}}</td>
                        <td>{{$row->transaction_id}}</td>
                        <td><label class="badge badge-success">{{$row->productCurrency->currency}}</label></td>
                        <td>
                            @php
                            $currency = $row->productCurrency->currency;
                            $price = $row->price;
                            $comm = $row->commission;
                            if ($row->escrow_fee_payer == 1) {
                            $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                            } elseif ($row->escrow_fee_payer == 2) {
                            $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                            } elseif ($row->escrow_fee_payer == 3) {
                            $total = number_format($price + (($price * $comm) / 100), 2, '.', '');
                            }
                            @endphp
                            {{$total . ' ' . $currency}}
                        </td>
                        <td>{!!$row->seller->full_name()!!}</td>
                        <td>{!!$row->buyer->full_name()!!}</td>
                        {{-- <td>
                            @if ($row->escrow_fee_payer == 1)
                            Buyer
                            @elseif ($row->escrow_fee_payer == 2)
                            Seller
                            @elseif ($row->escrow_fee_payer == 3)
                            50% Buyer & 50% Seller
                            @endif
                        </td> --}}
                        <td>
                            @if ($row->status == 0)
                            <label class="badge badge-warning">Pending for Approval</label>
                            @elseif ($row->status == 2)
                            <label class="badge badge-danger">In-Dispute</label>
                            @elseif ($row->status == 1)
                            <label class="badge badge-success">Buyer Approved</label>
                            @endif

                            @if($row->pocRequest == null)
                            @if($row->buyer_request_poc == 1)
                            <label class="badge badge-primary">Requested For POC</label>

                            @if (auth()->user()->user_type == 1)
                            <a class="btn btn-primary btn-sm" href="{{url('respond-poc/' . encode($row->id))}}">Respond to POC</a>
                            @endif
                            @endif
                            @else
                            @if($row->pocRequest->status == 1)
                            <label class="badge badge-primary">Seller Responded POC</label>
                            @endif

                            @endif



                        </td>
                        <td>{{$row->completion_days()}}</td>
                        <td>
                            @if (in_array($row->productTransaction->status_id, [6, 7]) && auth()->user()->id == $row->buyer_id)
                            <label class="badge badge-primary">Completed</label>
                            @else
                            <label class="badge badge-primary">{{$row->productTransaction->transactionStatus->status}}</label>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{url('dispute-messages/' . encode($row->id))}}" title="View Dispute">View Dispute History</a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="11">Records not found.</td>
                    </tr>
                    @endif
                </tbody>

            </table>
            <nav class="pull-right">{!! $all_products->links( "pagination::bootstrap-4") !!}</nav>
        </div>
    </div><!-- .user-content -->

    @endsection
