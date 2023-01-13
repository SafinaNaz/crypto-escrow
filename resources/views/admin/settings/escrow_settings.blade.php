@extends('admin.app')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Escrow</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Escrow Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <form action="{{ route('admin.escrow-settings.update') }}" method="post" class="form-horizontal" name="escrowSettingsForm" id="escrowSettingsForm">
            @csrf
            <input type="hidden" name="id" value="{!!@$settings->id!!}">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                Escrow Fee & Wallet Addresses
                            </h3>
                        </div>

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label" for="escrow_fee_btc">Escrow fee BTC % </label>
                                <input type="number" class="form-control" name="escrow_fee_btc" id="escrow_fee_btc" placeholder="Enter Escrow fee BTC Percentage" value="{{ @$settings->escrow_fee_btc }}" min="0" max="100" required  />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="escrow_fee_monero">Escrow fee Monero % </label>
                                <input type="number" class="form-control" name="escrow_fee_monero" id="escrow_fee_monero" placeholder="Enter Escrow fee Monero Percentage" value="{{ @$settings->escrow_fee_monero }}" min="0" max="100" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="btc_address">BTC Wallet Address </label>
                                <input type="text" class="form-control" name="btc_address" id="btc_address" placeholder="Enter BTC Wallet Address" value="{{ @$settings->btc_address }}" validBTCAddress="true" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="monero_address">Monero Wallet Address </label>
                                <input type="text" class="form-control" name="monero_address" id="monero_address" placeholder="Enter Monero Wallet Address" value="{{ @$settings->monero_address }}" validXMRAddress="true" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
    $(function(){
        $('#escrowSettingsForm').validate({
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass("is-invalid");
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass("is-invalid");
            },
        });
    });
</script>
@endsection
