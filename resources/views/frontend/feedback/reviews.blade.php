@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
            <h2>Public Reviews</h2>
            <form method="get" action="">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                        	<label for="q" class="input-item-label">Filter Reviews by @if (auth()->user()->user_type == 2){{'Seller'}}@else{{'Buyer'}}@endif</label>
                        	<input type="text" class="form-control" name="q" value="{{$q}}" />
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>

        </div>
        <br>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
                <tr>

                    <th>@if (auth()->user()->user_type == 2){{'Seller'}}@else{{'Buyer'}}@endif Name</th>
                    <th>No. of Transactions</th>
                    <th>Rating</th>
                  
                </tr>
                @if(count($users) > 0)
                @php
                $user_type = '';
                @endphp
                @foreach($users as $user)
                @if (auth()->user()->user_type == 2 )
                    @php 
                    $user_type = 1;
                    @endphp
                @else 
                    @php 
                    $user_type = 2;
                    @endphp
                @endif
                @if ($user->user_type == $user_type )
                @if(isset($_GET['q']) && !empty($_GET['q']))
                <tr>
                    <td>
                        <h6> <a href="{{ route('review-details', encode($user->id)) }}" title='View Details for {!! $user->username !!} '> {!! $user->full_name()!!} </a> </h6>
                    </td>
                    <td>
                        <h6> 
                         
                           
                      
                            {{ no_of_transaction($user->id) }}
                        
                      
                    </h6>
                    </td>
                    <td>
                        <div class="rating-list" title="{{$user->avg_rating}} / 5">
                        
                            <h6> {{($user->avg_rating)}} </h6>
                               
                          

                        </div>
                    </td>
                  

                </tr>
                @else
                <tr>
                    <td colspan="4">Search reviews.</td>
                </tr>
                @endif

              
                @endif
                @endforeach
                @else
                <tr>
                    <td colspan="4">No reviews found.</td>
                </tr>
                @endif

            </table>
            <nav class="pull-right">{!! $users->links( "pagination::bootstrap-4") !!}</nav>
        </div>
    </div><!-- .user-content -->

    @endsection
