<div class="row">
    <div class="col-sm-6">
        @foreach(siteLocales() as $locale)
            <div class="section">
                @if(count(siteLocales()) > 1)
                    <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                @endif
                <div class="form-group">
                    {{ Form::label('Name_' . $locale, trans('labels.name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('name_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'Name_' . $locale, 'placeholder' => trans('labels.name')]) }}
                    </div>
                </div>
            </div>
        @endforeach

        <div class="form-group">
            <div class="col-sm-12">
                <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">
                    {{ trans('actions.save_and_continue') }}</button>
            </div>
        </div>
    </div>

    @if(isset($category))
        <div class="col-sm-6">
            @foreach($category->albums as $album)
                <a class="album_widget" href="{{ route('control_panel.albums.edit', [$album->id]) }}" target="_blank">
                    <div class="album_widget__images_list">
                        @foreach($album->latestImages as $image)
                            <img src="{{ $image->thumbFullLink() }}" alt="{{ $album->name }}" class="img-responsive">
                        @endforeach
                    </div>
                    <h3 class="album_name">{!! $album->name_translated !!}</h3>
                </a>
            @endforeach
        </div>
    @endif
</div>