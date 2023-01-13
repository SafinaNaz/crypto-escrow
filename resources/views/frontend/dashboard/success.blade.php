@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel d-flex align-items-center">


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

</div>


@endsection