<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <h3>
                        <img loading="lazy" width="150px" class="img-fluid" src="{{ _asset('frontend/assets/img/logo-light.png') }}" />
                    </h3>
                   
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Services</h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="{{url('/about-escrow')}}">Escrows</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>About Us</h4>
                    <ul>
                        @php $pages = footer_menu(5,['about-us','term-conditions','privacy-policy']) @endphp
                        @if($pages)
                        @foreach($pages as $p)
                        <li><i class="bx bx-chevron-right"></i> <a href="{{url($p->seo_url)}}">{{$p->title}}</a></li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                 <div class="col-lg-3 col-md-6 footer-contact">
                   <h4>Contact Info</h4>
                    <p>
                       <i class="bx bxs-map"></i> {!!nl2br(SITE_ADDRESS)!!} <br />
                        <i class="bx bxs-phone-call"></i> {{SITE_PHONE}}<br />
                        <i class="bx bxs-envelope"></i> {{SITE_EMAIL}}<br />
                    </p>
                </div>

               
            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright-wrap d-md-flex py-4">
            <div class="mr-md-auto text-center text-md-left">
                <div class="copyright">
                    &copy; Copyright <strong><span>{{SITE_NAME}}</span></strong>. All Rights Reserved
                </div>
            </div>
        </div>
    </div>
</footer>