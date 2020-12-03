{{ Form::model($data, ['id' => 'ModuleEditingForm', 'onsubmit' => 'return(false)']) }}

@include('pages.modules.partials._head')

<p>{{ trans('messages.confirm_operation') }}</p>

<div class="form-group">
    <button type="button" class="btn btn-danger" onclick="vm.editing = false">{{ trans('actions.close') }}</button>
    <button type="button" class="btn btn-primary" onclick="saveModule()">{{ trans('actions.save') }}</button>
</div>
{{ Form::close() }}

@include('pages.modules.partials._scripts')

<script>
    function saveModule() {
        var title = window.translations.page_title_translated;

        var module = {
            type: 'page-title',

            data: {},

            title: function(){
                return '<i class="fa fa-header fa-fw" aria-hidden="true"></i> | ' + title;
            },

            noEditing: true
        };

        module.title_as_text = module.title();

        var module_index = $('#EditingMode').attr('data-module-index');

        vm.$refs.page.saveModule(module, module_index);
    }
</script>