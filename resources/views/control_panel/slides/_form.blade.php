<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                <div class="form-group">
                    {{ Form::label('Status_' . $locale, trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            {{ Form::radio('status_' . $locale, 'active', true) }} {{ trans('labels.statuses.active') }}
                        </label>
                        <label class="radio-inline">
                            {{ Form::radio('status_' . $locale, 'inactive', false) }} {{ trans('labels.statuses.inactive') }}
                        </label>
                    </div>
                </div>

                <hr class="input_separator">

                <div class="form-group">
                    {{ Form::label('Title_' . $locale, trans('labels.title'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('title_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'Title_' . $locale, 'placeholder' => trans('labels.title')]) }}
                    </div>
                </div>

                <hr class="input_separator">

                <div class="form-group">
                    {{ Form::label('Content_' . $locale, trans('labels.content'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('content_' . $locale, null, ['class' => 'form-control', 'rows' => 3,
                            'id' => 'Content_' . $locale, 'placeholder' => trans('labels.content')]) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-sm-4">
        <div class="section">
            <div class="form-group">
                {{ Form::label('Link', trans('labels.link'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    @include('control_panel.common._link_selector')
                </div>
            </div>

            <hr class="input_separator">

            <div class="form-group">
                {{ Form::label('Image', trans('labels.image'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    {{ Form::file('image', ['id' => 'Image', 'class' => 'form-control', 'accept' => 'image/*']) }}
                </div>
            </div>

            @if(isset($slide) && $slide->image)
                <div class="row">
                    <div class="col-sm-12 text-center">
                        @include('control_panel.common._thumb_widget', [
                            'image' => $slide->image,
                            'alt_text' => $slide->title_translated_piped
                        ])
                    </div>
                </div>
            @endif
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">
                    {{ trans('actions.save_and_continue') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="ImagesGalleryModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Images Gallery</h4>
            </div>
            <div class="modal-body">
                <p>One fine body&hellip;</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@include('control_panel.common._ckeditor', ['images_manager' => 0])