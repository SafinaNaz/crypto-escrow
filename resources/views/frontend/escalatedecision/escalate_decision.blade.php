@extends("frontend.layouts.dashboard_master")
@section('content')

<div class="user-panel vert-center-block">
    <div class="esc-dec-block">
        <div class="content-block escalate-block show">
            <h2>Do you want to Escalate Admin Decision ?</h2>
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary mx-1">Agree</button>
                <button type="button" class="btn btn-primary mx-1">Disagree</button>
            </div>
        </div>
        <div class="content-block agree-block">
            <h2>Deposit 20% OF Escrow Amount</h2>
            <form class="escalate-agree-form">
                <div class="form-group d-flex flex-column flex-sm-row align-items-md-center">
                    <label>Total Escrow</label>
                    <input type="text" class="form-control flex-fill" placeholder="100 B">
                </div>
                <div class="form-group d-flex flex-column flex-sm-row align-items-md-center">
                    <label>Deposit %</label>
                    <input type="text" class="form-control flex-fill" placeholder="20">
                </div>
            </form>
        </div>
    </div>
</div><!-- .user-content -->

@endsection