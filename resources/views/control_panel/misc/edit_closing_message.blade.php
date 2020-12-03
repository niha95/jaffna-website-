@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($data, ['route' => 'control_panel.misc.edit_closing_message', 'class' => 'form-horizontal', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-sm-8">
                @foreach(siteLocales() as $locale)
                    <div class="section">
                        <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                        <div class="form-group">
                            {{ Form::label('ClosingMessage_' . $locale, trans('labels.closing_message'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::textarea('closing_message_' . $locale, null,
                                    ['class' => 'form-control', 'id' => 'ClosingMessage_' . $locale, 'placeholder' => trans('labels.closing_message')]) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-sm-4">
                <button type="button" class="btn btn-primary btn-lg btn-block"
                        onclick="manager.show()">{{ trans('labels.images_manager') }}</button>

                <hr>

                <div class="section">
                    <div class="form-group">
                        {{ Form::label('ClosingStatus_' . $locale, trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                {{ Form::radio('closing_status', 'opened', true) }} {{ trans('labels.statuses.opened') }}
                            </label>
                            <label class="radio-inline">
                                {{ Form::radio('closing_status', 'closed', false) }} {{ trans('labels.statuses.closed') }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

    {{ Form::close() }}

   
@stop

@section('js')
    <script src="{{ asset('assets/control-panel/js/ckeditor/ckeditor.js') }}"></script>
    <script>
        var selected_editor = '';

        manager.setMode('images');

        CKEDITOR.on('instanceReady', function(evt) {
            var ed = evt.editor;

            ed.on('focus', function(e) {
                selected_editor = e.editor.name;
            });
        });

        $.each(locales, function(i, locale){
            new RichEditor('ClosingMessage_' + locale, {imagesManager: true});
        });

        $(manager).off('im.image_selected');
        $(manager).on('im.image_selected', function(e, image){
            CKEDITOR.instances[selected_editor].insertHtml('<img src="' + image.image_url + '" caption="' + image.caption +'">');
        });
    </script>
@append