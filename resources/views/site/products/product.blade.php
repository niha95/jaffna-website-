

@extends('site.layouts.default')

@section('title', $product->name_locale)

@section('content')

 <section class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <h3>{{ $product->name_locale }}</h3>
                        <hr class="line1">
                        <p>{!! $product->description_locale !!}</p>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        @if($product->images)
                         <div id="gallery" style="display:none;">
                             @foreach($product->images as $photo)
                            <img alt=""
                                 src="images/project1.jpg"
                                 data-image="images/project1.jpg"
                                 data-description="">

                           
                      @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </section>
        
@append

       