@extends('admin.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Escrows Products</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Escrows Products</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title float-sm-left">Escrows Products</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Transaction ID</th>
                                    <th>Seller</th>
                                    <th>Buyer</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    {{-- <th>Escrow Fee Payer</th> --}}
                                    <th>Buyer Status</th>
                                    <th>Completion Days</th>
                                    <th>Transaction Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

<!-- DataTables  & Plugins -->
<script src="{{ _asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ _asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<!-- /.content -->
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            lengthChange: false,
            // scrollX: true,
            processing: false,
            drawCallback: function() {

            },
            responsive: true,
            dom: '<"top"B<"clear">if>rt<"bottom"lp><"clear">',
            buttons: [],
            aLengthMenu: [
                [10, 50, 100, -1],
                [10, 50, 100, "All"]
            ],
            aaSorting: [],
            "language": {
                "emptyTable": "No record found"
            },
            serverSide: true,
            ajax: "{{ route('admin.escrows.index') }}",
            fnDrawCallback: function(oSettings) {

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'transaction_id',
                    name: 'transaction_id'
                },

                {
                    data: 'seller',
                    name: 'seller'
                },
                {
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: 'currency',
                    name: 'currency'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                // {
                //     data: 'escrow_fee_payer',
                //     name: 'escrow_fee_payer'
                // },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'completion_days',
                    name: 'completion_days'
                },
                {
                    data: 'transaction_status',
                    name: 'transaction_status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endsection
