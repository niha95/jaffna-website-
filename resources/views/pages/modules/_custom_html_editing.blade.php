{{ Form::model($data, ['id' => 'CustomHtmlForm', 'onsubmit' => 'return(false)']) }}

    {{ Form::hidden('', $editing_mode, ['id' => 'EditingMode', 'data-module-index' => $module_index]) }}

    @foreach(siteLocales() as $locale)
        <div class="section">
            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
            <div class="form-group">
                {{ Form::label('M__Title_' . $locale, trans('labels.title')) }}
                {{ Form::text('m__title_' . $locale, null, ['class' => 'form-control', 'id' => 'M__Title_' . $locale]) }}
            </div>

            <div class="form-group">
                {{ Form::label('M__Content_' . $locale, trans('labels.content')) }}
                {{ Form::hidden('m__content_' . $locale, null, ['id' => 'M__Content_' . $locale]) }}
                <div id="_M__Content_{{ $locale }}" class="ace_editor"></div>
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <button type="button" class="btn btn-danger" onclick="vm.editing = false">{{ trans('actions.close') }}</button>
        <button type="button" class="btn btn-primary" onclick="saveModule()">{{ trans('actions.save') }}</button>
    </div>
{{ Form::close() }}

<style>
    .ace_editor {
        height: 300px;
    }
</style>

<script>
    var selected_editor_id = '';

    manager.setMode('images');

    $.each(locales, function(i, v){
        var editor = ace.edit('_M__Content_' + v);
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setMode("ace/mode/html");

        editor.setOptions({
            minLines: 30
        });

        var input = $('#M__Content_' + v);

        editor.getSession().setValue(input.val());

        editor.getSession().on('change', function(){
            input.val(editor.getSession().getValue());
        });

        editor.on('focus', function(e, i){
            selected_editor_id = i.container.id;
        });
    });

    $(manager).off('im.image_selected');
    $(manager).on('im.image_selected', function(e, image){
        ace.edit(selected_editor_id).insert('<img src="' + image.image_url_relative + '">');
    });

    function saveModule() {
        var module = {};
        var title = '';

        module.type = 'custom-html';

        module.data = {
            localized: []
        };

        $.each(locales, function(i, v){
            var data = {
                locale: v,
                title: $('#M__Title_' + v).val(),
                content: $('#M__Content_' + v).val()
            };

            module.data.localized.push(data);

            if(title.length != 0) title += ' | ';

            title += data.title;
        });

        module.title = function(){
            return '<i class="fa fa-code fa-fw" aria-hidden="true"></i>' + ' | ' + title;
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>