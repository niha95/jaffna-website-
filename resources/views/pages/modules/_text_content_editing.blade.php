{{ Form::model($data, ['id' => 'TextContentForm', 'onsubmit' => 'return(false)']) }}

    {{ Form::hidden('', $editing_mode, ['id' => 'EditingMode', 'data-module-index' => $module_index]) }}

    {{ Form::hidden('', $translations, ['id' => 'Translations']) }}

    @foreach(siteLocales() as $locale)
        <div class="section">
            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
            <div class="form-group">
                {{ Form::label('M__Title_' . $locale, trans('labels.title')) }}
                {{ Form::text('m__title_' . $locale, null, ['class' => 'form-control', 'id' => 'M__Title_' . $locale]) }}
            </div>

            <div class="form-group">
                {{ Form::label('M__Content_' . $locale, trans('labels.content')) }}
                {{ Form::textarea('m__content_' . $locale, null, ['id' => 'M__Content_' . $locale, 'class' => 'form-control']) }}
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <button type="button" class="btn btn-danger" onclick="vm.editing = false">{{ trans('actions.close') }}</button>
        <button type="button" class="btn btn-primary" onclick="saveModule()">{{ trans('actions.save') }}</button>
    </div>
{{ Form::close() }}

<script>
    window.translations = JSON.parse($('#Translations').val());

    var selected_editor = '';

    CKEDITOR.on('instanceReady', function(evt) {
        var ed = evt.editor;

        ed.on('focus', function(e) {
            selected_editor = e.editor.name;
        });
    });

    $.each(locales, function(i, v){
        new RichEditor('M__Content_' + v, {
            imagesManager: true
        });
    });

    $(manager).off('im.image_selected');
    $(manager).on('im.image_selected', function(e, image){
        CKEDITOR.instances[selected_editor]
                .insertHtml('<img src="' + image.image_url + '" caption="' + image.caption +'">');
    });

    manager.setMode('images');

    function saveModule() {
        var module = {};
        var title = '';

        module.type = 'text-content';

        module.data = {
            localized: []
        };

        $.each(locales, function(i, v){
            var instance = 'M__Content_' + v;

            var data = {
                locale: v,
                title: $('#M__Title_' + v).val(),
                content: CKEDITOR.instances[instance].getData()
            };

            module.data.localized.push(data);

            if(title.length != 0) title += ' | ';

            title += data.title;
        });

        module.title = function(){
            return '<i class="fa fa-align-center fa-fw" aria-hidden="true"></i>' + ' | ' + title;
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>