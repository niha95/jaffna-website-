<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                <div class="form-group">
                    {{ Form::label('Name_' . $locale, trans('labels.name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('name_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'Name_' . $locale,
                                'placeholder' => trans('labels.name'),
                                'v-model' => defaultLocale() == $locale ? 'name' : '']) }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-sm-4">
        
            <div class="section">
                <div class="form-group">
                    {{ Form::label('Slug', trans('labels.slug'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        <div class="input-group">
                            {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'Slug',
                                    'placeholder' => trans('labels.slug'), !isset($category) ? 'disabled': '']) !!}
                            {!! Form::hidden('slug_hidden', null, ['id' => 'SlugHidden']) !!}
                            <span class="input-group-addon">
                            <input type="checkbox" id="ToggleSlug" onchange="toggleSlug(this)" {{ isset($category) ? 'checked': '' }}>
                        </span>
                        </div>
                    </div>
                </div>
            </div>

        <div class="section">
            <div class="form-group">
                {{ Form::label('ParentId', trans('labels.parent'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    {!! Form::select('parent_id', $parents, null, ['id' => 'ParentId', 'class' => 'form-control']) !!}
                </div>
            </div>

            <!--<div class="form-group">
                {{ Form::label('Layout', trans('labels.layout'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {!! Form::select('layout', $layouts, null, ['id' => 'Layout', 'class' => 'form-control']) !!}
                    </div>
                </div>-->
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

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2-bootstrap.min.css') }}">
@append

@section('js')
    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script src="{{ asset('assets/control-panel/js/ace/ace.js') }}"></script>
    <script>
        $('#ParentId').select2();

        $('#Slug').on('keydown', function(){
            var slug = slugify($(this).val());

            $(this).val(slug);
            updateHiddenSlug(slug);
        });

        $('#Name_' + default_locale).on('keydown', function () {
            var slug_field = $('#Slug');

            if (slug_field.prop('disabled')) {
                var slug = slugify($(this).val());

                slug_field.val(slug);

                updateHiddenSlug(slug);
            }
        });

        function slugify(value) {
            return value.toLowerCase()
                    .replace(/[^\w \u0600-\u06FF \-]+/g, '')
                    .replace(/ +/g, '-')
                    .replace(/\-+/g, '-');
        }

        function toggleSlug(element){
            if($(element).prop('checked')) {
                $('#Slug').removeProp('disabled');
            } else {
                $('#Slug').attr('disabled', 'disabled');
            }
        }

        function updateHiddenSlug(value){
            $('#SlugHidden').val(value);
        }
    </script>
@append