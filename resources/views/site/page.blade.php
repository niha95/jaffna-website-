@extends('site.layouts.default')

@section('title', $page->title_locale)

@if(!empty($page->meta_description_locale))
    @section('meta_description', $page->meta_description_locale)
@endif

@if(!empty($page->meta_keywords_locale))
    @section('meta_keywords', $page->meta_keywords_locale)
@endif

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $page->title_locale }}">
    <meta property="og:description" content="{{ $page->meta_description_locale }}">
    @if($page->featuredImage)
        <meta property="og:image" content="{{ $page->featuredImage->imageFullLink() }}">
    @endif
@append

@section('content')

    <section class="page-content">
        <div class="container">
            <div class="row">
@if($page->featuredImage)
<img alt="" 
                               src="$page->featuredImage->imageFullLink()" 
                               
                                   
                               />
 @endif

                       @if(isset($page) && $page->images_paths)
                        <div id="gallery" style="width: 100%;margin:0 auto">
                            @foreach($page->images_paths as $img)
                            
                               <img alt="" 
                               src="{{ route('images.show_image', $img['image_filename'], false) }}" 
                                data-image="{{ route('images.show_image', $img['image_filename'], false) }}"
                                   
                               />
                               
                          
                            @endforeach
                            </div>
                        @endif                                               
            </div>
            
            <div class="row">
                {!! $content !!}
            </div>
        </div>
    </section>

@append