<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                @if(count(siteLocales()) > 1)
                    <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                @endif

                <div class="form-group">
                    {{ Form::label('Name_' . $locale, trans('labels.name'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('name_' . $locale, null, ['class' => 'form-control',
                            'id' => 'Name_' . $locale, 'placeholder' => trans('labels.name'),
                            slugLocale() == $locale ? 'data-sluggaoble' : '']) }}
                    </div>
                </div>

             
            </div>
        @endforeach
    </div>

    <div class="col-sm-4">
            <div class="section">
                <div class="form-group">
                    {{ Form::label('ParentId', trans('labels.parent'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {!! Form::select('parent_id', ["0"=>trans('labels.main')]+$parents, null, ['id' => 'ParentId', 'class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
           
        <div class="section">
            <div class="form-group">
                {{ Form::label('Status', trans('labels.status'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    <label class="radio-inline">
                        {{ Form::radio('status', 'active', true) }} {{ trans('labels.statuses.active') }}
                    </label>
                    <label class="radio-inline">
                        {{ Form::radio('status', 'inactive', false) }} {{ trans('labels.statuses.inactive') }}
                    </label>
                    <label class="radio-inline">
                        {{ Form::radio('status', 'featured', false) }} {{ trans('labels.statuses.featured') }}
                    </label>
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('Slug', trans('labels.slug'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    <div class="input-group">
                        {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'Slug',
                                'placeholder' => trans('labels.slug'), !isset($category) ? 'readonly' : '']) !!}
                        <span class="input-group-addon">
                            <input type="checkbox" id="ToggleSlug" onchange="toggleSlug(this)"
                                    {{ isset($category) ? 'checked': '' }}
                            >
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                {{ Form::label('Image', trans('labels.image'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::file('image', ['id' => 'Image', 'class' => 'form-control', 'accept' => 'image/*']) }}
                </div>
            </div>

            @if(isset($category) && $category->image)
                <div class="row">
                    <div class="col-sm-12 text-center">
                        @include('control_panel.common._thumb_widget', [
                            'image' => $category->image,
                            'alt_text' => $category->name_translated_piped
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

@include('control_panel.common._ckeditor', ['images_manager' => 0])
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2-bootstrap.min.css') }}">
@append
@section('js')
    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script>
        $('#ParentId').select2({
            width: '100%'
        });

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