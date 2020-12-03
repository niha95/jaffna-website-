<div class="Works text-center">
    <div class="row">
        <h1>أعمال الشركة</h1>
        <div class="workImages">
            @foreach($slides as $slide)
                <div><img src="{{ $slide->image_full_url }}" alt="{{ $slide->caption }}"></div>
            @endforeach
        </div>
    </div>
</div>