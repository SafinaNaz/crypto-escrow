@extends("frontend.layouts.master-layout")

@section('content')

<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>{{$username}}'s Review</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>Review</li>
            </ol>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row justify-content-lg-center">
            <div class="col-lg-8">
                <h3>Transaction ID:</h3>
                <h5>{{$product->transaction_id}}</h5>
                <hr>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="get-started-form">
                    <form class="profile-form" action="{{$route}}" method="POST" id="profile-form" class="form">
                        @csrf
                        <div class="form-group">
                            <label>Rating</label><br>
                            <div class="range-slider">
                                <input type="range" name="rating" min="1" step="0.1" value="3" max="5" class="slider" id="rating">
                                <div class="slider-rating">
                                    @for($i=1; $i<=9;$i++) <span class="min-val">{{$i}}</span>
                                        @endfor
                                        <span class="max-val">10</span>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label>Feedback <small>UP TO 500 CHARACTERS</small></label>
                            <textarea rows="7" class="form-control limited" maxlength="500" id="feedback" name="feedback" required></textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-cripto-sec btn-round">Add Feedback</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection