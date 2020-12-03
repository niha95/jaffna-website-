@extends('site.layouts.default')

@section('content')
@include('site.layouts.partials._slider')
    
        <section class="our-target-section">
            <div class="container">
                <div class="row">
                    @foreach($homePages as $homePages)
                    <div class="col-sm-4 col-xs-12">
                        <img class="img-fluid" src="{{ $homePages->featuredImage->imageFullLink() }}">
                        <div class="feature text-center">
                            <h3>{{ $homePages->title_locale }}</h3>
                            <p>{{ $homePages->details }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </section>

     <section class="project-section">
            <div class="container">
                <div class="head-title">
                    <p class="lead">كل ماتحتاجه يوجد لدينا</p>
                    <h3>مشاريعنا</h3>
                    <hr class="line1">
                </div>
                
                <div class="samples">
                    <div class="small-img">
                        <ul class="list-unstyled">
                             @foreach($hpackage as $package)
                            <li class="list-item">
                                <img class="img-fluid" src="{{ $package->image->imageFullLink() }}">
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="large-img">
                        <img class="img-fluid" src="{{ $hpackage[0]->image->imageFullLink() }}">
                        <div class="project-title">
                            <h3>{{ $hpackage[0]->name_locale }}</h3>
                            <p>
                                <span>{{ $hpackage[0]->description_locale }}</span>
                                <a href="{{ route('site.products.show', $hpackage[0]->slug) }}" class="btn">المزيد</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<section class="latest-project">
            <div class="container">
                <div class="head-title">
                    <p class="lead">اهم ما لدينا لنعرضه لك</p>
                    <h3>اهم مشاريعنا</h3>
                    <hr class="line1">
                </div>
            <div class="project-samples responsive">
                 @foreach($packages as $package)
                    <div class="item">
                         @if($package->image)<img src="{{ $package->image->imageFullLink() }}" class="img-fluid">
                        <div class="samples-title">
                            <div class="thumbaction">
                                <a class="test-popup-link" href="{{ $package->image->imageFullLink() }}">
                                    <i class="fa fa-search-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                         @endif
                    </div>
                   @endforeach
                </div>
            </div>
        </section>

      
@append

 