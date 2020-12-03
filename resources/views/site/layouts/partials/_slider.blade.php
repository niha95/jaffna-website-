

       <section class="jafna-slider">
            <div class="container">
                <div id="mycarousel" class="carousel slide" data-ride="carousel">

                   <div class="carousel-inner">
                        @foreach($banner as $k => $slide)
                    <div class="carousel-item {{ $k == 0 ? 'active' : '' }}">
                      <img src="{{ $slide->image->imageFullLink() }}" alt="jafna-banner">
                    </div>
                  @endforeach
                  </div>

                  <a class="carousel-control-prev" href="#mycarousel" data-slide="prev">
                      <img class="carousel-control-prev-icon img-fluid" src="{{asset('assets/site/images/left-side.png')}}">    
                    </a>
                  <a class="carousel-control-next" href="#mycarousel" data-slide="next">
                    <img class="carousel-control-next-icon img-fluid" src="{{asset('assets/site/images/right-side.png')}}">
                  </a>

                </div>
                
                                        <!-- welcome section -->
                
                <div class="welcome-site">
                    <h2>مرحبا بكم فى موقعنا</h2>
                    <p>
                      {!! $misc->site_word_content_locale !!}</p>
                </div>
            </div>
        </section>