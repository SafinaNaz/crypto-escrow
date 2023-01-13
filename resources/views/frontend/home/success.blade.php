@extends("frontend.layouts.master-layout")
@section('content')

<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>Escrow Transaction</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>Transaction Success</li>
            </ol>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row justify-content-lg-center">
            <div class="col-lg-8">

                <div class="get-started-form">
                    <div class="alert alert-success">
                        
                        @if(Session::has('transaction_id'))
                        <strong>Escrow Transaction created successfully, Please use this transaction ID for buyer login.</strong>
                        <br><br>
                        <strong>
                        TRANSACTION ID: {{Session::get('transaction_id') }}
                        </strong>
                        <br><br>
                        LOGIN LINK: {{url('buyer-login')}}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection