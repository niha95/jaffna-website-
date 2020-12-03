@extends('site.layouts.default')

@section('content')
@extends('site.layouts.default')

@section('content')
        <section class="page-content">
            <div class="container">
                <div class="row list-product">
                    <div class="col-md-9 col-sm-12">
                       
                        <div class="row">
                              @foreach($products as $package)
                            <div class="col-md-3 col-sm-6 col-xs-12">
                              
                                <div class="item">
                                    @if($package->image)<img class="img-fluid" src="{{ $package->image->imageFullLink() }}">@endif
                                    <div class="thumbproduct">
                                        <p class="lead">{{ $package->name_locale }}</p>
                                        <span>{{ $package->price }} $<small></small>
                                        <a href="{{ route('site.products.show', $package->slug) }}<">
                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                        </a></span>
                                    </div>
                                </div>
                                
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                 <div class="opt-pagination">
                <ul class="pagination">{{ $products->links() }}</ul>
            </div>
            </div>
            </section>
@endsection

   