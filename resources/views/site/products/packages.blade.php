@extends('site.layouts.default')

@section('content')
        <section class="page-content">
            <div class="container product-list">
                <div class="row ">
                    @foreach($products as $package)
                    <div class="col-sm-3">
                        
                          <div>
                            @if($package->image)<a href="{{ route('site.products.show', $package->slug) }}">
                                <img class="img-responsive" src="{{ $package->image->imageFullLink() }}" alt="بخوراتى">@endif
                                <h3> {{ $package->name_locale }}  </h3>
                            </a>
                            <p>{{ $package->name_locale }}</p>
                            <hr class="line">
                           @if ($package->offer == 1)
                            <span class="price"  style="text-decoration:line-through" >السعر:  {{ $package->price }} ري</span>
                            <span class="price">السعر : {{ $package->offer_price }} ريال</span>
                            @else
                            <p class="price" >السعر  {{ $package->price }} ريال</p>
                            @endif
                         @if ($package->quantity == 1)
                             <a href="" class="btn btn-danger disabled"> <i class="fa fa-ban" aria-hidden="true"></i> نفذ من المخزن</a>
                             @elsep>
                            <span>* لا يشمل الضريبه</span>
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
                </div>
                  
            </div>
            </section>
@endsection