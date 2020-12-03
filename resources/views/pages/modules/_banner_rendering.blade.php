<!-- Banner -->
<section class="banner">
    <div class="container">
        <div class="banner-wrapper">
            <h2 class="section-title alt banner-title">{{ $banner->title }}</h2>

            <div class="banner-content">
                <p>
                    {{ $banner->content }}
                </p>

                @if($banner->link)
                    <div class="text-center">
                        <a href="{{ $banner->link }}" class="btn btn-primary">{{ trans('labels.more') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section><!-- /Banner -->