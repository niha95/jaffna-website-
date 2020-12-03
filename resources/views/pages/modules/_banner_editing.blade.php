{{ Form::model($data, ['id' => 'ModuleEditingForm', 'onsubmit' => 'return(false)']) }}

@include('pages.modules.partials._head')

@foreach(siteLocales() as $locale)
    <div class="section">
        @if(count(siteLocales()) > 1)
            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
        @endif

        <div class="form-group">
            {{ Form::label('mBannerTitle_' . $locale, trans('labels.title')) }}
            {{ Form::text($locale . "[title]", null, ['class' => 'form-control', 'id' => 'mBannerTitle_' . $locale]) }}
        </div>

        <div class="form-group">
            {{ Form::label('mBannerContent_' . $locale, trans('labels.content')) }}
            {{ Form::textarea($locale . "[content]", null, ['class' => 'form-control', 'id' => 'mBannerContent_' . $locale]) }}
        </div>

        <div class="form-group">
            {{ Form::label('mBannerLink_' . $locale, trans('labels.link')) }}
            {{ Form::text($locale . "[link]", null, ['class' => 'form-control', 'id' => 'mBannerLink_' . $locale]) }}
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

    function saveModule() {
        var module = {};
        var title = window.translations.banner_translated;
        var form = $('#ModuleEditingForm');

        module.type = 'banner';

        module.data = {
            localized: []
        };

        $.each(locales, function(i, locale){
            var data = {
                locale: locale,
                title: $(form.find('#mBannerTitle_' + locale)).val(),
                content: $(form.find('#mBannerContent_' + locale)).val(),
                link: $(form.find('#mBannerLink_' + locale)).val()
            };

            module.data.localized.push(data);
        });

        module.title = function(){
            return '<i class="fa fa-picture-o fa-fw" aria-hidden="true"></i>' + ' | ' + title;
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>