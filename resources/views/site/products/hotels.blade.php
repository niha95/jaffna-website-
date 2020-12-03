@extends('site.layouts.default')

@section('content')
    <div class="feature-img">
        <img src="{{ asset('assets/site/images/hotel.jpg') }}" class="img-fluid" alt="optimo-banner">
    </div>

    <section class="list-hotels-page">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <h2 class="">{{ $title }}</h2>
                    @if(!$products->isEmpty())
                    <ul class="list-unstyled">
                        @foreach($products as $product)
                        <li class="list-item">
                            <div class="hotel-data">
                                @if($product->image)<img class="img-fluid" src="{{ $product->image->imageFullLink() }}">@endif
                                <ul class="list-unstyled">
                                    <li class="list-item"><h3>{{ $product->name_locale }}</h3></li>
                                    <li class="list-item"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ @$product->city->{"name_".app()->getLocale()} }}</li>
                                    <li class="list-item"><p>{{ str_limit(strip_tags($product->description_locale), 100) }}</p></li>
                                    <li class="list-item"><a href="{{ route('site.products.show', $product->slug) }}">{{ trans('labels.more') }}</a></li>
                                </ul>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <div class="opt-pagination">
                        <ul class="pagination">{{ $products->links() }}</ul>
                    </div>
                    @endif
                </div>

                @if(count($toursCities))
                <div class="col-md-4 col-sm-12">
                    <p class="choose">choose your destination</p>
                    <ul class="list-unstyled destination">
                        @foreach($toursCities as $tcity)
                            <li class="list-item"><a href="?city={{$tcity->id}}">{{ $tcity->{"name_".app()->getLocale()} }}</a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection