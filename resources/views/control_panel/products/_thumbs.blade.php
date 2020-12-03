<div class="section">
    <div class="text-center">
        @foreach($product->images as $image)
            @include('control_panel.common._thumb_widget')
        @endforeach
    </div>
</div>