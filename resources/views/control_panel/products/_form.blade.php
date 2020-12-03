<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                <div class="form-group">
                    {{ Form::label('Name_' . $locale, trans('labels.title'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text('name_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'Name_' . $locale, 'placeholder' => trans('labels.title')]) }}
                    </div>
                </div>

                <hr class="input_separator">

                <div class="form-group">
                    {{ Form::label('Description_' . $locale, trans('labels.content'), ['class' => 'control-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::textarea('description_' . $locale, null, ['class' => 'form-control ck_editor', 'rows' => 3,
                            'id' => 'Description_' . $locale, 'placeholder' => trans('labels.content')]) }}
                    </div>
                </div>
         
            </div>
        @endforeach
    </div>

    <div class="col-sm-4">
        <div class="section">
            <div class="form-group">
                {{ Form::label('Status', trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
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
            
        </div>

       

        <div class="section">
            <div class="form-group">
                {{ Form::label('Slug', trans('labels.slug'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon" id="CategoriesPath">
                            {{ $path or '' }}
                        </span>
                        {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'Slug',
                                'placeholder' => trans('labels.slug'), !isset($page) ? 'readonly': '']) !!}
                        <span class="input-group-addon">
                            <input type="checkbox" id="ToggleSlug" onchange="toggleSlug(this)" {{ isset($page) ? 'checked': '' }}>
                        </span>
                    </div>
                </div>
            </div>
                <hr>

            <div class="form-group">
                {{ Form::label('ProductCategoryId', trans('labels.category'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::select('product_category_id', $categories, null, ['class' => 'form-control', 'id' => 'product_category_id']) }}
                </div>
            </div>
            
        </div>
         
            
        <div class="section">
            
            
       

        <div class="section">
            <div class="form-group">
                {{ Form::label('Image', trans('labels.image'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    {{ Form::file('image_product', ['id' => 'Image', 'class' => 'form-control', 'accept' => 'image/*']) }}
                </div>
            </div>
            @if(isset($product) && $product->image)
                <div class="row">
                    <div class="col-sm-12 text-center">
                        @include('control_panel.common._thumb_widget', ['image' => $product->image, 'alt_text' => $product->name_translated_piped])
                    </div>
                </div>
            @endif
        </div>

        
        @if(isset($product))
        <div class="section">
            <div class="form-group">
                {{ Form::label('Album', trans('labels.album'), ['class' => 'control-label col-sm-2']) }}
            </div>
            @include('control_panel.products._dropzone_uploader')
            <div>
                <a href="{{ route('control_panel.albums.download_all_images', [$product->id]) }}"
                   class="btn btn-primary" target="_blank">{{ trans('actions.download_all_images') }}</a>
            </div>

            @if(!$product->images->isEmpty())
                @include('control_panel.products._thumbs')
            @endif
        </div>
        @endif

        <div class="form-group">
            <div class="col-sm-12">
                <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">{{ trans('actions.save_and_continue') }}</button>
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

@section('js')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script src="{{ asset('assets/control-panel/js/ace/ace.js') }}"></script>
    <script>
        $('.features').select2({
            tags: true,
            tokenSeparators: [","],
            createTag: function (tag) {
                return {
                    id: tag.term,
                    text: tag.term,
                    isNew : true
                };
            }
        });

        $('#Slug').on('keydown', function () {
            var slug = slugify($(this).val());

            $(this).val(slug);
        });

        $('#Name_' + default_locale + ', #Name_' + default_locale).on('keydown keyup', function () {
            var slug_field = $('#Slug');

            if (slug_field.prop('readonly')) {
                var slug = slugify($(this).val());

                slug_field.val(slug);
            }
        });

        function slugify(value) {
            return value.toLowerCase()
                    .replace(/[^\w \u0600-\u06FF \-]+/g, '')
                    .replace(/ +/g, '-')
                    .replace(/\-+/g, '-');
        }

        function toggleSlug(element) {
            if ($(element).prop('checked')) {
                $('#Slug').removeProp('readonly');
            } else {
                $('#Slug').attr('readonly', 'readonly');
            }
        }

        function updateHiddenSlug(value) {
            $('#SlugHidden').val(value);
        }

        $('#ProductCategoryId').on('change', function(){
            $.ajax({
                url: window.urls.get_categories_path,

                data: {category_id: $(this).val()},

                success: function(response){
                    $('#CategoriesPath').text(response.content);
                }
            });
        });
    </script>
@append