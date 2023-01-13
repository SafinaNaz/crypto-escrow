@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
            <h2>Pending Reviews</h2>
           
            
        </div>
        <br>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
                <thead>
                    <tr>
                    
                        <th>Transaction</th>
                        <th>Add Review</th>
                      
                   
                      

                    </tr>
                </thead>
                <tbody>
                	@php 
                	if(auth()->user()->user_type == 1 ) {
	                	$url = 'seller-review';
	                	
                	} else {
                		$url = 'review';
                	}
                	@endphp
                    @if(count($reviews) > 0)
	                    @foreach($reviews as $index => $row)
		                    @if(isset($row->reviewer_id) && !empty($row->reviewer_id))
			                    @if(empty(review_exit($row->product_id, auth()->user()->id)))
			                    <tr>
			                      
			                        <td>
			                            {{$row->transaction_id}}
			                        </td>
			                         <td>

			                            <a href="{{url('/'.$url.'/' . encode($row->product_id))}}">
			                            	<i class="fas fa-star"></i>
			                            </a>
			                        </td>
			                       
			                        
			                       
			                    </tr>


			                    @endif
		                    @else
		                     <tr>
		                      
		                        <td>
		                            {{$row->transaction_id}}
		                        </td>
		                         <td>
		                            <a href="{{url('/'.$url.'/' . encode($row->product_id))}}"><i class="fas fa-star"></i></a>
		                        </td>
		                       
		                        
		                       
		                    </tr>
		                    @endif
	                    @endforeach
                    @else
                    <tr>
                        <td colspan="5">No record found.</td>
                    </tr>
                    @endif
                </tbody>

            </table>
            <nav class="pull-right">{!! $reviews->links( "pagination::bootstrap-4") !!}</nav>
        </div>
    </div><!-- .user-content -->

    @endsection