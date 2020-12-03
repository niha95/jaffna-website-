{{ Form::model($data, ['id' => 'TextContentForm', 'onsubmit' => 'return(false)']) }}

{{ Form::hidden('', $editing_mode, ['id' => 'EditingMode', 'data-module-index' => $module_index]) }}

@foreach(siteLocales() as $locale)
    <div class="section">
        <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
        <div class="form-group">
            {{ Form::label('M__Title_' . $locale, trans('labels.title')) }}
            {{ Form::text('m__title_' . $locale, null, ['class' => 'form-control', 'id' => 'M__Title_' . $locale]) }}
        </div>
    </div>
@endforeach

<div class="form-group">
    {{ Form::label('M__Link', trans('labels.link')) }}
    {{ Form::text('m__link', null, ['class' => 'form-control', 'id' => 'M__Link']) }}
</div>

<div class="form-group">
    <button type="button" class="btn btn-danger" onclick="vm.editing = false">{{ trans('actions.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="saveModule()">{{ trans('actions.save') }}</button>
</div>
{{ Form::close() }}

<script>
    function saveModule() {
        var module = {};
        var title = '';

        module.type = 'youtube-video';

        module.data = {
            link: $('#M__Link').val(),
            localized: []
        };

        $.each(locales, function(i, v){
            var data = {
                locale: v,
                title: $('#M__Title_' + v).val()
            };

            module.data.localized.push(data);

            if(title.length != 0) title += ' | ';

            title += data.title;
        });

        module.title = function(){
            return '<i class="fa fa-youtube fa-fw" aria-hidden="true"></i>' + ' | ' + title;
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>