 <header>
            <div class="container">
                <div class="top-head">
                    <div class="social">
                        <ul class="list-unstyled list-inline">
                         @if($misc->facebook) <li class="list-inline-item">
                                <a href="{{$misc->facebook}}">
                                    <i class="fa fa-facebook fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                             @if($misc->twitter)<li class="list-inline-item">
                                <a href="{{$misc->twitter}}">
                                    <i class="fa fa-twitter fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                             @if($misc->instagram)<li class="list-inline-item">
                                <a href="{{$misc->instagram}}">
                                    <i class="fa fa-instagram fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                            @if($misc->youtube) <li class="list-inline-item">
                                <a href="{{$misc->youtube}}">
                                    <i class="fa fa-youtube fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                            @if($misc->linkedin) <li class="list-inline-item">
                                <a href="{{$misc->linkedin}}">
                                    <i class="fa fa-linkedin fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                            @if($misc->gplus) <li class="list-inline-item">
                                <a href="{{$misc->gplus}}">
                                    <i class="fa fa-gplus fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                             @if($misc->snapchat) <li class="list-inline-item">
                                <a href="{{$misc->snapchat}}">
                                    <i class="fa fa-snapchat-ghost fa " aria-hidden="true"></i>
                                </a>
                            </li>@endif
                        
                       <li class="list-inline-item">
                                <a href="{{ changeSiteLocale('en') }}">
                                    <img class="img-responsive" src="{{asset('assets/site/images/en.png')}}" alt="en" title="english">
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ changeSiteLocale('ar') }}">
                                    <img class="img-responsive" src="{{asset('assets/site/images/ar.png')}}" alt="ar" title="العربيه">
                                </a>
                            </li>
                    </ul>
               
                    </div>
                    <div class="data">
                        <ul class="list-inline list-unstyled">
                            
                            <li class="list-inline-item">
                                @if($misc->emails_list)
                            @foreach($misc->emails_list as $email)
                               <i class="fa fa-envelope" aria-hidden="true"></i><span>{{ $email->value }}</span>
                            @endforeach
                        @endif
                            </li> 
                            
                        </ul>
                    </div>
                </div>
            </div>
        </header>



        