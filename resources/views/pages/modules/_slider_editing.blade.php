{{ Form::model($data, ['id' => 'ModuleEditingForm', 'onsubmit' => 'return(false)']) }}

@include('pages.modules.partials._head')

@foreach(siteLocales() as $locale)
    <div class="section">
        @if(count(siteLocales()) > 1)
            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
        @endif

        <div class="form-group">
            {{ Form::label('mSliderTitle_' . $locale, trans('labels.title')) }}
            {{ Form::text($locale . "[title]", null, ['class' => 'form-control', 'id' => 'mSliderTitle_' . $locale]) }}
        </div>

        <div class="input-group">
            {{ Form::hidden('album_id') }}
            {{ Form::text('album_name', null, ['class' => 'form-control', 'placeholder' => trans('labels.album')]) }}
            <span class="input-group-btn">
                <button class="btn btn-primary" type="button" onclick="manager.show()">
                    {{ trans('actions.select') }}
                </button>
            </span>
        </div>
    </div>
@endforeach

<hr>

<div class="form-group">
    <button type="button" class="btn btn-danger" onclick="vm.editing = false">{{ trans('actions.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="saveModule()">{{ trans('actions.save') }}</button>
</div>
{{ Form::close() }}

@include('pages.modules.partials._scripts')

<script>
    var moduleForm = $('#ModuleEditingForm');

    manager.setMode('albums');

    $(manager).off('im.album_selected');
    $(manager).on('im.album_selected', function(e, album){
        moduleForm.find('[name=album_id]').val(album.id);
        moduleForm.find('[name=album_name]').val(album.name);
        manager.hide();
    });

    function saveModule() {
        var module = {};
        var title = window.translations.slider_translated;

        module.type = 'slider';

        module.data = {
            albumId: moduleForm.find('[name=album_id]').val(),
            localized: []
        };

        $.each(locales, function(i, locale){
            module.data.localized.push({
                locale: locale,
                title: moduleForm.find('#mSliderTitle_' + locale).val()
            });
        });

        module.title = function(){
            return '<i class="fa fa-file-image-o fa-fw" aria-hidden="true"></i>' + ' | ' + title;
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>