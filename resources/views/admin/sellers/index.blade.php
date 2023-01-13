@extends('admin.app')
@section('content')
<style type="text/css">
    tbody td:nth-child(4) {
    display: flex;
}
</style>
<!-- DataTables -->
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ _asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Sellers</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Sellers</li>
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
                                    <option value="1">Verified</option>
                                    <option value="0">Unverified </option>
                                </select>
                            </div>
                           
                          
                        </div>
                        <div class="row mt-1">
                            <div class="col-md-12">
                                <a href="{{ route('admin.sellers.index') }}" class="btn btn-warning">Reset Filters</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title float-sm-left">Sellers</h3>
                        <a class="btn btn-primary float-sm-right" href="{{url('/admin/sellers/create')}}">Add Seller</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Seller</th>
                                    <th>Rating ( /10)</th>
                                    <th>Status</th>
                                    {{-- <th>ETL Status</th> --}}
                                    <th>Actions</th>
                                    {{-- <th>ETL Verification</th> --}}
                                    <th>Verification</th>
                                    <th>Registration Date</th>
                                    <th>Last Login</th>
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
        ajax_data = [];
        ajax_data['filter_type'] = '';
        var table = $('#datatable').DataTable({
            lengthChange: false,
            // scrollX: true,
            processing: false,
            drawCallback: function() {
                $('.delete-form-btn').on('click', function() {
                    var submitBtn = $(this).next('.deleteSubmit');
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You will not be able to recover this record!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        showCloseButton: true
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            submitBtn.click();
                        } else if (result.isDenied) {
                            // Swal.fire('Changes are not saved', '', 'info')
                        }
                    });

                });
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
            // ajax: "{{ route('admin.sellers.index') }}",
            "ajax": {
                "url": "{{ route('admin.sellers.index') }}",
                "data": function(d){
                    d.filter_type = ajax_data['filter_type'];
                },
                "beforeSend": function() {
                    if (table && table.hasOwnProperty('settings')) {
                        table.settings()[0].jqXHR.abort();
                    }
                }
            },
            fnDrawCallback: function(oSettings) {
                $('[data-toggle="popover"]').popover();
                $('[data-toggle="tooltip"]').tooltip();
            },
            columns: [
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'avg_rating',
                    name: 'avg_rating'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                // {
                //     data: 'approved_status',
                //     name: 'approved_status'
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'approve_etl',
                    name: 'approve_etl',
                    orderable: false,
                    searchable: false
                },
                 {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'last_login_on',
                    name: 'last_login_on'
                },


            ]
        });
           // Selecting the filter type Start
        $('body').on('change', 'select[name=filter_type]', function(){
            if( $(this).val() == ''){
                $('.seller').hide();
                $('.buyer').hide();

            }
           
         
            ajax_data['filter_type'] = $(this).val();
            refreshDataTable();
        });
        function refreshDataTable(){
            table.ajax.reload();
        }
    });
</script>
@endsection
