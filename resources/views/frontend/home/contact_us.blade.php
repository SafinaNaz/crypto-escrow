@extends("frontend.layouts.master-layout")
@section('content')

<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>Contact Us</h2>
            <ol>
                <li><a href="{{url('/')}}">Home</a></li>
                <li>Contact Us</li>
            </ol>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <p>
            @if($cmsPage)
            {!!$cmsPage->description!!}
            @endif
        </p>

        <div class="row">
            <div class="col-xl-8">
                <div class="contact-area wow fadeInLeft">
                    {!! Form::open([
                    'method'=>'POST',
                    'url' => 'contact-us',
                    'style' => '',
                    'class' => 'default-form contact-form',
                    'id' => 'contactForm',
                    'name' => 'contactForm'
                    
                    ]) !!}
                    <div class="form-group">
                        {{ Form::text('username', '', array('class' => 'form-control','placeholder' => 'User Name','id' => 'username', 'required' => true)) }}
                        @if ($errors->has('username'))
                        <p class="help-block text-danger">
                            {{ $errors->first('username') }}
                        </p>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::email('email', '', array('class' => 'form-control','placeholder' => 'Email Address','id' => 'email', 'required' => true)) }}
                        @if ($errors->has('email'))
                        <p class="help-block text-danger">
                            {{ $errors->first('email') }}
                        </p>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::text('subject', '', array('class' => 'form-control','placeholder' => 'Subject','id' => 'subject','required' => true)) }}
                        @if ($errors->has('subject'))
                        <p class="help-block text-danger">
                            {{ $errors->first('subject') }}
                        </p>
                        @endif
                    </div>
                    <div class="form-group">
                        {{ Form::textarea('message', '', array('class' => 'form-control','placeholder' => 'Your Message','id' => 'message', 'required' => true)) }}
                        @if ($errors->has('message'))
                        <p class="help-block text-danger">
                            {{ $errors->first('message') }}
                        </p>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger ">Submit</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</section>

@endsection