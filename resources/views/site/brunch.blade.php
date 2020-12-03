@extends('site.layouts.default')

@section('content')
<section class="page-content">
            <div class="container list-project">
                <div class="row">
               
                    <div class="col-md-9 col-sm-12">
                        
                        <hr class="line1">
                        <div class="row">
                            @foreach($packages as $package)
                            <div class="col-sm-6 col-md-4 col-xs-12">
                                <div class="">
                                     <img src="{{ $package->image->imageFullLink() }}" class="img-fluid">
                                    
                                    <h4>{{ $package->name_locale }}</h4>
                                    <p>{{ $package->description_locale }}</p>
                                    <a href="{{ route('site.products.show', $package->slug) }}" class="btn">اعرف المزيد</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                
            </div>
        </section>
@endsection
 