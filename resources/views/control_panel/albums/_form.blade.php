<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                <a class="section_collapse" role="button" data-toggle="collapse" href="#Section1_{{ $locale }}">&minus;</a>
                <div id="Section1_{{ $locale }}" class="in">
                    @if(count(siteLocales()) > 1)
                        <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                    @endif
                    <div class="form-group">
                        {{ Form::label('Status_' . $locale, trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                {{ Form::radio('status_' . $locale, 'active', true) }} {{ trans('labels.statuses.active') }}
                            </label>
                            <label class="radio-inline">
                                {{ Form::radio('status_' . $locale, 'inactive', false) }} {{ trans('labels.statuses.inactive') }}
                            </label>
                            <label class="radio-inline">
                                {{ Form::radio('status_' . $locale, 'featured', false) }} {{ trans('labels.statuses.featured') }}
                            </label>
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Name_' . $locale, trans('labels.name'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('name_' . $locale, null,
                                ['class' => 'form-control', 'id' => 'Name_' . $locale, 'placeholder' => trans('labels.name')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Description_' . $locale, trans('labels.description'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('description_' . $locale, null,
                                ['class' => 'form-control', 'id' => 'Description_' . $locale, 'placeholder' => trans('labels.description')]) }}
                        </div>
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

        @if(isset($album) && !$album->images->isEmpty())
            @include('control_panel.albums._thumbs')
        @endif
    </div>

    <div class="col-sm-4">
        <div class="section">
            <div class="form-group">
                {{ Form::label('AlbumCategoryId', trans('labels.category'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    {{ Form::select('album_category_id', $categories, null,
                        ['class' => 'form-control', 'id' => 'AlbumCategoryId']) }}
                </div>
            </div>
        </div>
        @if(isset($album))
            @include('control_panel.albums._dropzone_uploader')

            <div>
                <a href="{{ route('control_panel.albums.download_all_images', [$album->id]) }}"
                   class="btn btn-primary" target="_blank">{{ trans('actions.download_all_images') }}</a>
            </div>
        @endif
    </div>
</div>

@section('js')
    <script src="{{ asset('assets/control-panel/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $.each(locales, function(i, locale){
            new RichEditor('Description_' + locale);
        });
    </script>
@append
