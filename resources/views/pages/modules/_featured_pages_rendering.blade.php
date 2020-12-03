@if($pages)
    <section>
        <div class="container text-center">
            <div class="row">
                @foreach($pages as $page)
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{ $page->fullUrl() }}">
                            <img src="@if($page->icon){{ $page->icon->imageFullLink() }}@elseif($page->featuredImage){{ $page->featuredImage->imageFullLink() }}@else{{ asset('assets/site/images/logo.png') }}@endif" alt="{{ $page->title_locale }}">
                            <div class="caption">
                                <h4>{{ $page->title_locale }}</h4><hr>
                                <span>{{ str_limit(strip_tags($page->first_text_content), 120) }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif