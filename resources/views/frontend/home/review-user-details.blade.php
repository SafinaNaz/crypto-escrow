@extends("frontend.layouts.master-layout")
@section('styles')
    <link href="{{ _asset('frontend/assets/css/rating.css') }}" rel="stylesheet">
@endsection
@section('content')

    <section class="breadcrumbs">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center">
                <h2>Details ({{ $user->username }})</h2>
                <ol>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/reviews') }}">Reviews</a></li>
                    <li>Details</li>
                </ol>
            </div>

        </div>
    </section>

    <section class="section-pd">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3>Total @if (auth()->user()->user_type == 2) Sellings @else Buyings @endif  </h3>
                    <p><strong>Bitcoin: </strong> @if (auth()->user()->user_type == 2) {{ $user->total_sellings['bitcoin'] }} @else {{ $user->total_buyings['bitcoin'] }} @endif </p>
                    <p><strong>Monero: </strong> @if (auth()->user()->user_type == 2) {{ $user->total_sellings['monero'] }} @else {{ $user->total_buyings['monero'] }} @endif </p>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    Feedbacks (Average Rating {{ $user->avg_rating }}/5)
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
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
                                                            <div class="rating-list" title="{{$row->rating}} / 5">
                                                                <div class="starsRating">
                                                                    <span style="width:{{($row->rating*5)}}%"></span>
                                                                </div>

                                                            </div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
@endsection
