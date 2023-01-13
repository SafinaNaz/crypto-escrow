@extends("frontend.layouts.dashboard_master")

@section('content')

<div class="user-panel">
    <div class="review-block reviews-list">
        <div class="heading-block d-flex justify-content-between">
            <h2>Reviews</h2>
            <form method="get" action="">
                <div class="row">
                    <div class="col-xl-12 col-md-12">
                        <div class="form-group">
                            <label for="country" class="input-item-label">Review Filter</label>
                            <select class="form-control" name="review">
                                <option {{$review_type == 'buyer_reviews'?'selected':''}} value="buyer_reviews">Reviews added by You</option>
                                <option {{$review_type == 'seller_reviews'?'selected':''}} value=seller_reviews>Received Reviews</option>
                                <option {{$review_type == 'admin_reviews'?'selected':''}} value="admin_reviews">Reviews added for admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Filter Records</button>
            </form>

        </div>
        <br>
        <div class="table-responsive">
            <table id="datatable" class="table crpto-table table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Transaction</th>
                        <th>Rating</th>
                        @if (auth()->user()->user_type == 2)
                        @if (@$_GET['review'] != 'seller_reviews') 
                        <th>Review</th>
                        @endif
                        @elseif (auth()->user()->user_type == 1)
                        
                        <th>Review</th>
                        @endif
                        <th>Review Date</th>

                    </tr>
                </thead>
                <tbody>
                    @if(count($reviews) > 0)
                    @foreach($reviews as $row)
                    <tr>
                        <td>
                            <img loading="lazy" style="height: 40px;width: 40px;" src="{{$row->$user->photo()}}" alt="{{$row->$user->full_name()}}">
                        </td>
                        <td>{!!$row->$user->full_name()!!}</td>
                        <td>
                            {{$row->product->transaction_id}}
                        </td>
                        <td>
                            <strong>{{$row->rating}} / 5</strong>
                        </td>
                        @if (auth()->user()->user_type == 2)
                            @if (@$_GET['review'] != 'seller_reviews') 
                            <td>
                                {!!nl2br($row->review)!!}
                            </td>
                            @endif
                        @elseif (auth()->user()->user_type == 1)
                        
                            <td>
                                {!!nl2br($row->review)!!}
                            </td>
                       
                        @endif
                        <td>{{date('M d, Y G:i A',strtotime($row->created_at))}}</td>
                    </tr>
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
