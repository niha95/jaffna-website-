<div id="myCarousel" class="carousel" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($slides as $k => $slide)
        <div class="item @if($k==0) active @endif">
            <img src="{{ $slide->image->imageFullLink() }}" alt="{{ $slide->title_locale }}">
        </div>
        @endforeach
    </div>
</div>