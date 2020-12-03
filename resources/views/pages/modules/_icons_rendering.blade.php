@if($icons)
    <section>
        <div class="container text-center">
            <div class="row">
                @foreach($icons as $icon)
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{ generateNavLink($icon->link) }}">
                            <img src="@if($icon->iconImg){{ $icon->iconImg->imageFullLink() }}@else{{ asset('assets/site/images/logo.png') }}@endif" alt="{{ $icon->title_locale }}">
                            <div class="caption">
                                <h4>{{ $icon->title_locale }}</h4><hr>
                                <span>{{ str_limit(strip_tags($icon->excerpt_locale), 125) }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif