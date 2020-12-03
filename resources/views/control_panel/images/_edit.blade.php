{{ Form::model($image, ['route' => ['control_panel.images.update', $image->id],
    'method' => 'patch',
    'class' => 'form-horizontal',
    'id' => 'EditImageForm',
    'onsubmit' => 'return updateImage()']) }}
    <div class="form-group">
        <label for="Caption_{{ defaultLocale() }}" class="control-label col-sm-2">{{ trans('labels.caption') }}</label>
        <div class="col-sm-10">
            @foreach(siteLocales() as $locale)
                {{ Form::text('caption_' . $locale, null,
                    ['class' => 'form-control', 'placeholder' => localizedLabel('caption', $locale), 'id' => 'Caption_' . $locale]) }}
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-primary" type="submit">{{ trans('actions.save') }}</button>
        </div>
    </div>
{{ Form::close() }}