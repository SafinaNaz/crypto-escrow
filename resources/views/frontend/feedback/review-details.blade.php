@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
           
            <div class="col-md-12">
            	<h2>Details ({{ $user->username }})</h2>
            	<h3>Total @if (auth()->user()->user_type == 2) Sellings @else Buyings @endif  </h3>
            	<p><strong>Bitcoin:</strong> @if (auth()->user()->user_type == 2) {{ $user->total_sellings['bitcoin'] }} @else {{ $user->total_buyings['bitcoin'] }} @endif </p>
            	<p><strong>Monero:</strong> @if (auth()->user()->user_type == 2) {{ $user->total_sellings['monero'] }} @else {{ $user->total_buyings['monero'] }} @endif </p>
            	<p><strong>Feedbacks:</strong>  (Average Rating {{ $user->avg_rating }}/5)

            	</p>
            </div>

        </div>
        <br>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
               <tr>

                                                <th>Rating</th>
                                               
                                               
                                                {{-- <th>Transaction Amount</th> --}}
                                               
                                                @if (auth()->user()->user_type == 1)
                                                <th>Review</th>
                                                @endif
                                                <th>Review Date</th>
                                            </tr>
                                            @if(count($reviews) > 0)
                                                @foreach($reviews as $row)
                                                    <tr>
                                                        <td>
                                                         
                                                            {{$row->rating}}
                                                        </td>
                                                      
                                                       {{--  <td>
                                                            <div class="rev-wrap">
                                                                {!!($row->product->total_price)!!}
                                                            </div>
                                                        </td> --}}
                                                      
                                                      
                                                        @if (auth()->user()->user_type == 1)
                                                        <td>
                                                            <div class="rev-wrap">
                                                                {!!nl2br($row->review)!!}
                                                            </div>
                                                        </td>
                                                        @endif
                                                        

                                                        <td>{{date('M d, Y G:i A',strtotime($row->created_at))}}</td>


                                                    </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="4">No reviews found.</td>
                                            </tr>
                                            @endif

            </table>
            <nav class="pull-right">{!! $reviews->links( "pagination::bootstrap-4") !!}</nav>
        </div>
    
    </div><!-- .user-content -->

    @endsection
