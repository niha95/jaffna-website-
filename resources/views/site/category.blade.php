@extends('site.layouts.default')

@section('content')
    <section id="services">
        <div class="container">
            <div class="get-started-content media" style="padding-top: 30px;">
                <div class="wow fadeInRight"  data-wow-delay="1000ms" data-wow-duration="2000ms" style="visibility: visible; animation-duration: 1000ms; animation-delay: 300ms; animation-name: fadeInRight;">
                    @if(!$category->pages->isEmpty())
                        <div id="CategoryPages">
                            @foreach($category->pages->chunk(3) as $chunk)
                                @foreach($chunk as $page)
                                    <div class="col-sm-3">
                                        <a href="{{ $page->fullUrl() }}" class="page_widget">
                                            @if($page->featuredImage)
                                                <img src="{{ $page->featuredImage->thumbFullLink() }}" alt="{{ $page->title_locale }}" class="img-thumbnail">
                                            @endif
                                            <h3 class="text-center">{{ $page->title_locale }}</h3>
                                        </a>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection