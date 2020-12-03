        
        <section class="bottom-section">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-xs-12">
                        <h3>تأسست مؤسسه جفنه عام</h3>
                        <p>
                            {!! $misc->site_word_content_locale !!}
                        </p>
                    </div>
              
                    <div class="col-sm-4 col-xs-12">
                        <h3>روابط تهمك</h3>
                        <hr class="line">
                        <ul class="list-unstyled">
                        @foreach($footerMenu as $f_m)
                        <li class="list-item"><a href="{{ $f_m->fullUrl() }}" target="_blank"><i class="fa fa-angle-left"></i> {{ $f_m->title_locale }}</a></li>
                        @endforeach
                    </ul>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <h3>معلومات عنا</h3>
                        <hr class="line">
                        <ul class="list-unstyled">
                            <li class="list-item">
                                <i class="fa fa-map-marker"></i> {!! $misc->address_locale !!}
                            </li>
                             
                            <li class="list-item">
                                @if($misc->emails_list)
                            @foreach($misc->emails_list as $email)
                                <i class="fa fa-envelope" aria-hidden="true"></i>{{ $email->value }}
                            @endforeach
                        @endif
                            </li>
                            <li class="list-item">
                                <i class="fa fa-mobile"></i> {!! $misc->other_contact_data_locale !!}
                            </li>
                            
                        </ul>
                     
                         <div class="social">
                                            <ul class="list-inline list-unstyled">
                            @if($misc->facebook) <li class="list-inline-item">
                                <a href="{{$misc->facebook}}">
                                   <img class="img-fluid" src="{{asset('assets/site/images/facebook.png')}}">
                                </a>
                            </li>@endif
                             @if($misc->twitter)<li class="list-inline-item">
                                <a href="{{$misc->twitter}}">
                                    <img class="img-fluid" src="{{asset('assets/site/images/twitter.png')}}">
                                </a>
                            </li>@endif
                             @if($misc->instagram)<li class="list-inline-item">
                                <a href="{{$misc->instagram}}">
                                    <img class="img-fluid" src="{{asset('assets/site/images/insta.png')}}">
                                </a>
                            </li>@endif
                            
                        </ul>
                                
                            </div>
                    </div>
                </div>
                <footer class="text-center">
                            <span>جميع الحقوق محفوظه &reg; 2018 مؤسسه جفنه للتجاره والمقاولات 
                                <br>
                                <a href="https://vadecom.net/ar" target="_blank"> تصميم مواقع</a> - 
                                <a href="https://vadecom.net/ar" target="_blank"> شركة فاديكوم</a>
                            </span>
                        </footer>
            </div>
        </section>