
@extends('site.layouts.default')

@section('content')
        <section class="page-content">
            <div class="container product-list">
                <div class="row ">
                   @foreach($category->products->chunk(3) as $chunk)
                    @foreach($chunk as $k => $package)
                    <div class="col-sm-3">
                        
                          <div>
                            @if($package->image)<a href="{{ route('site.products.show', $package->slug) }}">
                                <img class="img-responsive" src="{{ $package->image->imageFullLink() }}" alt="بخوراتى">@endif
                                <h3> {{ $package->name_locale }}  </h3>
                            </a>
                            <p>{!! str_limit(strip_tags($package->description_locale),20,'...') !!}
                            </p>
                            <hr class="line">
                                @if ($package->offer == 1)
                            <span class="price"  style="text-decoration:line-through; color:#ae6e4a;margin-left:7px" > {{ $package->price }} ريال</span>
                            <span class="price">{{ $package->offer_price }} ريال</span>
                            
                            @else
                            <p class="price" >السعر  {{ $package->price }} ريال</p>
                            @endif
                            <span style="display:block; margin-top:5px;">* لا يشمل الضريبه</span>
                            <hr class="line">
                            @if ($package->quantity == 1)
                             <a href="" class="btn btn-danger disabled"> <i class="fa fa-ban" aria-hidden="true"></i> نفذ من المخزن</a>
                             @else
                             
                            <!-- if quantity > 0 show this button-->
                            <a href="{{ route('site.cart.add', $package->id) }}" onclick="{{ route('site.cart.add', $package->id) }}" class="btn btn-success"><i class="fa fa-cart-plus" aria-hidden="true"></i> اضف الى سلتى</a
                            <!-- if quantity == 0 show this button-->
                           @endif
                        </div>
                       
                   
                    </div>
                     @endforeach
                     @endforeach
                </div>
                  
            </div>
            </section>


@endsection