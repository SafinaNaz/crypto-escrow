@extends("frontend.layouts.dashboard_master")
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<div class="user-panel">
    <div class="text-right pb-3 mbl-padd">
        @if(Auth::user()->user_type == 1)
        <a class="btn btn-primary btn-mbl" href="{{route('get-started')}}">Create New Escrow</a>
        @endif
    </div>
    <div class="table-responsive">
        <table id="datatable" class="table crpto-table table-hover">
            <thead>

                <tr>
                    <th>ID</th>
                    <th>Transaction ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Amount In $</th>
                    <th>Seller</th>
                    <th>Buyer</th>
                    {{-- <th>Escrow Fee Payer</th> --}}
                    <th>Buyer Status</th>
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


                       @php
                       $type = '';
                       if($row->productCurrency->currency == 'Bitcoin') {
                            $type = 'BTC';

                       } elseif($row->productCurrency->currency == 'Monero') {
                            $type = 'XMR';
                       }
                       $curl = curl_init();
                       curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://min-api.cryptocompare.com/data/price?fsym=$type&tsyms=USD",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_HTTPHEADER => array(
                            "accept: application/json"
                        ),
                    ));
                       $response = curl_exec($curl);
                       $err = curl_error($curl);
                       curl_close($curl);
                       $response_json = json_decode($response);
                       $coin = $response_json->USD;
                       $converted_amount = $total * $coin;
                       @endphp
                    <td>{!!$converted_amount!!}</td>
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
                        @if (auth()->user()->user_type == 2)
                        <form class="float-sm-right" name="approveForm{{$row->id}}" id="approveForm{{$row->id}}" action="{{url('escrows/approve')}}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{encode($row->id)}}" />
                            <input type="hidden" name="status" value="1" />
                            <button type="submit" class="btn btn-sm btn-secondary">Approve</button>
                        </form>
                        @endif

                        @elseif ($row->status == 2)
                        <label class="badge badge-danger">In-Dispute</label>
                        @elseif ($row->status == 1)
                        <label class="badge badge-success">Buyer Approved</label>
                        @endif

                        @if($row->pocRequest != null)
                        @if($row->buyer_request_poc == 1 && $row->pocRequest->status == 0)
                        <label class="badge badge-primary">Requested For POC</label>
                        @endif
                        @if($row->pocRequest->status == 1)
                        <label class="badge badge-primary">Seller Responded POC</label>
                        @endif
                        @endif

                    </td>
                    <td>
                        @if (in_array($row->productTransaction->status_id, [6, 7]) && auth()->user()->id == $row->buyer_id)
                        <label class="badge badge-primary">Completed</label>
                        @else
                        <label class="badge badge-primary">{{$row->productTransaction->transactionStatus->status}}</label>
                        @endif
                    </td>
                    <td>

                        <a class="btn btn-primary btn-icon" href="{{url('messages/' . encode($row->id))}}" title="View Messages"><i class="fa fa-envelope"></i></a>
                        <a class="btn btn-success btn-icon" href="{{url('transaction-detail/' . encode($row->id) . '/detail')}}" title="View Detail"><i class="fa fa-eye"></i></a>
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
