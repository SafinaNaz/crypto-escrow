@extends("frontend.layouts.master-layout")
@section('content')

<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>FAQs</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>FAQs</li>
            </ol>
        </div>

    </div>
</section>

<section class="page-content">
    <div class="container">
        @if(count($faq_categories) > 0)
        @foreach($faq_categories as $category)
        <h3 class="mb-3">{{$category->title}}</h3>
        <div class="tabs">
            @if(count($category->faqs) > 0)
            @foreach($category->faqs as $faq)
            <div class="tab">
                <input type="radio" id="rd{{$faq->id}}" name="rd">
                <label class="tab-label" for="rd{{$faq->id}}">{{$faq->title}}</label>
                <div class="tab-content">
                    {{$faq->description}}
                </div>
            </div>
            @endforeach
            @endif

        </div>
        @endforeach
        @endif


    </div>
</section>

@endsection