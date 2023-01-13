@extends('admin.app')
@section('styles')
<style>
    .filter-card .seller,.filter-card .buyer,.filter-card .subAdmin{
        display:none;
    }
</style>
@endsection
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reviews</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Reviews</li>
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
                <div class="card filter-card">
                    <div class="card-header">
                        Filters
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="filter_type" class="control-label">Filter Type</label>
                                <select name="filter_type" id="" class="form-control">
                                    <option value="">Select Filter Type</option>
                                    <option value="0">Seller to Buyer</option>
                                    <option value="1">Buyer to Seller</option>
                                    <option value="2">Buyer to Sub-Admin</option>
                                    <option value="3">Seller to Sub-Admin</option>
                                </select>
                            </div>
                            <div class="col-md-4 buyer">
                                <label for="filter_type" class="control-label">Buyer</label>
                                <select name="buyer" id="" class="form-control"></select>
                            </div>
                            <div class="col-md-4 seller">
                                <label for="filter_type" class="control-label">Seller</label>
                                <select name="seller" id="" class="form-control"></select>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning">Reset Filters</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title float-sm-left">Reviews</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Review By</th>
                                    <th>Transaction #</th>
                                    <th>Review To</th>
                                    <th>Rating</th>
                                    @if(auth()->user()->roles[0]->id == 3)
                                    <th>Review</th>
                                    @endif
                                    <th>Review Date</th>
                                    <th>Action</th>
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
    ajax_data = [];
    ajax_data['filter_type'] = '';
    ajax_data['buyer'] = '';
    ajax_data['seller'] = '';
    ajax_data['subAdmin'] = '';

    sellers = @json($sellers);
    buyers = @json($buyers);
    $(document).ready(function() {
        var table = $('#datatable').DataTable({
            lengthChange: false,
            // scrollX: true,
            processing: false,
            drawCallback: function() {},
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
            // ajax: "{{ $ajax_url }}",
            "ajax": {
                "url": "{{ $ajax_url }}",
                "data": function(d){
                    d.filter_type = ajax_data['filter_type'];
                    d.buyer = ajax_data['buyer'];
                    d.seller = ajax_data['seller'];
                    d.subAdmin =  ajax_data['subAdmin'];
                },
                "beforeSend": function() {
                    if (table && table.hasOwnProperty('settings')) {
                        table.settings()[0].jqXHR.abort();
                    }
                }
            },
            fnDrawCallback: function(oSettings) {

            },
            columns: [{
                    data: 'photo',
                    name: 'photo',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'transaction_id',
                    name: 'transaction_id'
                },
                {
                    data: 'review_to',
                    name: 'review_to'
                },
                {
                    data: 'rating',
                    name: 'rating'
                },
                @if(auth()->user()->roles[0]->id == 3)
                {
                    data: 'feedback',
                    name: 'feedback'
                },
                @endif
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

        // Selecting the filter type Start
        $('body').on('change', 'select[name=filter_type]', function(){
            if( $(this).val() == ''){
                $('.seller').hide();
                $('.buyer').hide();

            }
            // if seller to buyer , buyer to seller or seller to subadmin
            if( $(this).val() == 0 || $(this).val() == 1 ||  $(this).val() == 3 ){
                sellers_options = '<option value="">Select Seller</option>';
                sellers.forEach((seller) => {
                    sellers_options += '<option value="'+seller.id+'">'+seller.username+'</option>'
                });

                $('[name=seller]').html(sellers_options);
                $('.seller').show();

            }else{
                $('.seller').hide();
            }
            // if seller to buyer , buyer to seller or buyer to subadmin
            if( $(this).val() == 0 || $(this).val() == 1 || $(this).val() == 2  ){
                buyers_options = '<option value="">Select Buyer</option>';
                buyers.forEach((buyer) => {
                    buyers_options += '<option value="'+buyer.id+'">'+buyer.username+'</option>'
                });
                $('[name=buyer]').html(buyers_options);
                $('.buyer').show();
            }else{
                $('.buyer').hide();
            }
            ajax_data['filter_type'] = $(this).val();
            refreshDataTable();
        });
        // Selecting the filter type End

        // Selecting the Buyer Start
        $('body').on('change', '[name=buyer]', function(){
            ajax_data['buyer'] = $(this).val();
            refreshDataTable();
        });
        // Selecting the Buyer End

        // Selecting the Buyer Start
        $('body').on('change', '[name=seller]', function(){
            ajax_data['seller'] = $(this).val();
            refreshDataTable();
        });
        // Selecting the Buyer End

        function refreshDataTable(){
            table.ajax.reload();
        }
    });
</script>
@endsection
