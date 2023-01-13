@if(Session::has('error'))
<div class="alert alert-danger {{$class}}">{{Session::get('error') }}</div>
@endif
@if(Session::has('success'))
<div class="alert alert-success {{$class}}">{{Session::get('success') }}</div>
@endif