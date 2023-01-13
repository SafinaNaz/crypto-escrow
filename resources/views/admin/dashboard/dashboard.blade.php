@extends('admin.app')
@section('content')
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Admin Users</span>
                        <span class="info-box-number">
                            {{$admin_users ?? '0'}}
                        </span>
                    </div>
                </div>
            </div>

            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sellers</span>
                        <span class="info-box-number">{{$sellers ?? '0'}}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Buyers</span>
                        <span class="info-box-number">{{$buyers ?? '0'}}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Open Escrows</span>
                        <span class="info-box-number">{{$products}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Latest Escrow Products</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
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
                                        <th>Completion Days</th>
                                        <th>Transaction Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($latest_products)
                                    @foreach($latest_products as $row)
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
                                            @else
                                            <label class="badge badge-success">Approved</label>
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

                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="10">Records not found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <a href="{{url('/admin/escrows')}}" class="btn btn-sm btn-secondary float-right">View All Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{ _asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

@endsection
