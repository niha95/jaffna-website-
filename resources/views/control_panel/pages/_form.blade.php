<div class="row">
    <div class="col-sm-8">
        {{ Form::hidden('content', null, ['id' => 'PageContent']) }}

        <div id="App" :layouts="{{ json_encode($layouts) }}"
             :modules_list="{{ json_encode($modules_list) }}"
             :existing_page="{{ isset($page) ? htmlentities($page->content) : null }}"
        >
            <div class="section">
                <a class="section_collapse" role="button" data-toggle="collapse" href="#LayoutsSection">&minus;</a>
                <div id="LayoutsSection" class="@{{ minimize_layouts ? 'collapse' : 'in' }}">
                    <div class="row">
                        <div class="col-sm-4" v-for="layout in layouts">
                            <layout :layout="layout" :selected_layout.sync="selected_layout"></layout>
                        </div>
                    </div>
                </div>
            </div>

            <modules_list :modules_list="modules_list" v-ref:modules_list></modules_list>

            <page :selected_layout.sync="selected_layout" :existing_page="existing_page" v-ref:page></page>

            <div id="EditingForm" v-show="editing">
                <h3 id="Title"></h3>
                <div id="Content"></div>
            </div>


            <template id="PageTemplate">
                <div id="Page">
                    <div class="row">
                        <div class="col-sm-4" v-for="position in positions">
                            <position :position="position" :selected_position.sync="selected_position"></position>
                        </div>
                    </div>

                    <div v-if="positionHasModules == true">
                        <div id="PositionModulesList">
                            <module v-for="module in selected_position.modules" :module="module" :i="$index"></module>
                        </div>
                    </div>
                </div>
            </template>

            <template id="ModuleTemplate">
                <div class="table_row">
                    <div class="table_cell">@{{{ title }}}</div>
                    <div class="table_cell text-center reordering_buttons">
                        <button class="btn btn-primary btn-xs" v-on:click.prevent="moveUp(i)" v-if="canMoveUp">
                            <i class="fa fa-level-up" aria-hidden="true"></i>
                        </button>

                        <button class="btn btn-primary btn-xs" v-on:click.prevent="moveDown(i)" v-if="canMoveDown">
                            <i class="fa fa-level-down" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="table_cell">
                        <button class="btn btn-primary btn-xs" v-on:click.prevent="editModule(module)" v-if="!module.noEditing">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>

                        <button class="btn btn-danger btn-xs" v-on:click.prevent="deleteModule(i)">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </template>

            <template id="PositionTemplate">
                <div class="position_card @{{ isSelected ? 'selected' : '' }}">
                    <h4 class="position_name"><a href="#" v-on:click.prevent="selectPosition">@{{ position.name }}</a>
                    </h4>
                    <a class="append_module" href="#" v-on:click.prevent="openModulesList">+</a>
                </div>
            </template>

            <template id="LayoutTemplate">
                <h4 class="text-center">
                    <a href="#" v-on:click.prevent="setLayout">@{{{ layout.thumbnail }}}</a>
                </h4>
            </template>

            <template id="ModulesListTemplate">
                <div class="modal fade" id="ModulesListModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">{{ trans('labels.modules_list') }}</h4>
                            </div>
                            <div class="modal-body">
                                <ul id="ModulesList">
                                    <li v-for="module in modules_list" class="module">
                                        <a href="#"
                                           v-on:click.prevent="appendModule(module.url)"
                                        >
                                            @{{ module.name }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="modal fade" id="ConfirmationModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">{{ trans('messages.confirm_operation') }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-right">
                                <input type="hidden" name="requested_action" value="">
                                <button type="button" class="btn btn-danger"
                                        onclick="confirmation_modal.hide()">{{ trans('actions.go_back') }}</button>
                                <button type="button" class="btn btn-primary"
                                        onclick="confirmation_modal.confirmOperation()">{{ trans('actions.confirm') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="form-group">
                    {{ Form::label('details', 'details', ['class' => 'col-sm-12']) }}
                    <div class="col-sm-12">
                        {{ Form::textarea('details', null,
                            ['class' => 'form-control', 'id' => 'details' ,
                               ]) }}
                    </div>
                </div>
    </div>

    <div class="col-sm-4">
        <button type="button" class="btn btn-primary btn-lg btn-block"
                onclick="manager.show()">
            {{ trans('labels.images_manager') }}
        </button>

        <hr>

        <div class="section">
            <div class="form-group">
                {{ Form::label('Slug', trans('labels.slug'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    <div class="input-group">
                        <span class="input-group-addon" id="CategoriesPath">
                            {{ $path or '' }}
                        </span>
                        {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'Slug',
                                'placeholder' => trans('labels.slug'), !isset($page) ? 'readonly': '']) !!}
                        <span class="input-group-addon">
                            <input type="checkbox" id="ToggleSlug"
                                   onchange="toggleSlug(this)" {{ isset($page) ? 'checked': '' }}>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        @foreach(siteLocales() as $locale)
            <div class="section">
                <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                <div class="form-group">
                    {{ Form::label('Status_' . $locale, trans('labels.status'), ['class' => 'col-sm-12']) }}
                    <div class="col-sm-12">
                        <label class="radio-inline">
                            {{ Form::radio('status_' . $locale, 'active', true) }} {{ trans('labels.statuses.active') }}
                        </label>
                        <label class="radio-inline">
                            {{ Form::radio('status_' . $locale, 'inactive', false) }} 'Project'
                        </label>
                        <label class="radio-inline">
                            {{ Form::radio('status_' . $locale, 'featured', false) }} {{ trans('labels.statuses.featured') }}
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('Title_' . $locale, trans('labels.title'), ['class' => 'col-sm-12']) }}
                    <div class="col-sm-12">
                        {{ Form::text('title_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'Title_' . $locale,
                                'placeholder' => trans('labels.title')]) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('MetaKeywords_' . $locale, trans('labels.meta_keywords'), ['class' => 'col-sm-12']) }}
                    <div class="col-sm-12">
                        {{ Form::text('meta_keywords_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'MetaKeywords_' . $locale,
                                'placeholder' => trans('labels.meta_keywords')]) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('MetaDescription_' . $locale, trans('labels.meta_description'), ['class' => 'col-sm-12']) }}
                    <div class="col-sm-12">
                        {{ Form::textarea('meta_description_' . $locale, null,
                            ['class' => 'form-control', 'id' => 'MetaDescription_' . $locale,
                                'placeholder' => trans('labels.meta_description'), 'rows' => 3]) }}
                    </div>
                </div>
            </div>
        @endforeach

        <div class="section">
            <div class="form-group">
                {{ Form::label('PageCategoryId', trans('labels.category'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::select('page_category_id', selectPlaceholder($categories, trans('labels.category')), null,
                        ['class' => 'form-control', 'id' => 'PageCategoryId']) }}
                </div>
            </div>
        </div>

        <div class="section">
            <div class="form-group">
                {{ Form::label('FeaturedImage', trans('labels.featured_image'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::file('featured_image', ['class' => 'form-control']) }}
                </div>
            </div>
            @if(isset($page) && $page->featuredImage)
                <div class="text-center">
                    <hr>
                    <img src="{{ $page->featuredImage->thumbFullLink() }}"
                         alt="{{ $page->title_translated_piped }}"
                         class="img-thumbnail"
                    >
                </div>
            @endif
        </div>

        <div class="section">
            <div class="form-group">
                {{ Form::label('PageIcon', trans('labels.icon'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::file('icon_image', ['class' => 'form-control', 'id' => 'PageIcon']) }}
                </div>
            </div>
            @if(isset($page) && $page->icon)
                <div class="text-center">
                    <hr>
                    <img src="{{ $page->icon->imageFullLink() }}"
                         alt="{{ $page->title_translated_piped }}"
                         class="img-thumbnail"
                    >
                </div>
            @endif
        </div>
        
        
        
        
        
        
        
         <div class="section">
                        
            <!--<div class="input-group">
              <span class="input-group-addon" id="basic-addon1">@</span>
              <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
            </div>-->
            
            @if(isset($page) && $page->images_paths)
                @foreach($page->images_paths as $img)
                <div style="position: relative; float: left;" class="page_image_item">
                   <img src="{{ url('thumb/'.$img['thumb_filename']) }}"
                      
                         class="img-thumbnail" />
                         <a href="#" style="left:0;top:0;position: absolute;background:#959695;padding:2px 5px" data-id="{{$img['id']}}" data-page-id="{{$page->id}}" class="remove_page_image" ><i class="fa fa-trash"></i></a>
                </div>
                @endforeach
            @endif            
                  <div style="clear:both"></div>  
            <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-success" id="add_image_input"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </span>
                    
                    {{ Form::file('icon_image', ['class' => 'form-control page_image_input', 'name' => 'page_images[]']) }}
                                         
                    <!--<span class="input-group-btn">
                        <button type="button" class="btn btn-default"><i class="fa fa-upload" aria-hidden="true"></i></button>
                    </span>-->
            </div>                
            
            <div id="image_input_container" style="margin-top: 20px;">
            </div>    
        </div>
        
      
        
        
        
        
        

        <div class="form-group">
            <div class="col-sm-12">
                <a href="" class="btn btn-danger">{{ trans('actions.reset') }}</a>
                <button type="submit" class="btn btn-primary" id="SubmitButton">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">
                    {{ trans('actions.save_and_continue') }}</button>
            </div>
        </div>
    </div>
</div>

@include('control_panel.common._images_manager')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2-bootstrap.min.css') }}">
@append

@section('js')
    <script src="{{ asset('assets/control-panel/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/control-panel/js/ace/ace.js') }}"></script>

    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>

    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script>
        $('#PageCategoryId').select2({
            width: '100%'
        });
    </script>

    <script>
        $('#PageForm').on('submit', function () {
            $("#SubmitButton").append('<img class="loading_image_sm" src="' + window.loading_icon + '">');

            var page = vm.$refs.page.fetchData();

            $('#PageContent').val(JSON.stringify(page));
        });
    </script>

    <script>
        var counter = 0;

        var vm = new Vue({
            el: '#App',

            props: ['layouts', 'modules_list', 'existing_page'],

            data: {
                selected_layout: null,
                editing: false,
                minimize_layouts: false,
            },

            watch: {
                editing: function (value) {
                    if (value == false) {
                        var form = $('#EditingForm');

                        form.find('#Title').html('');
                        form.find('#Content').html('');
                    }
                }
            },

            created: function () {
                if (!$.isEmptyObject(this.existing_page)) {
                    var self = this;

                    self.minimize_layouts = true;

                    this.selected_layout = $.grep(this.layouts, function (item) {
                        return item.slug == self.existing_page.layout;
                    })[0];
                }
            },

            components: {
                layout: {
                    template: '#LayoutTemplate',

                    props: ['layout', 'selected_layout'],

                    methods: {
                        setLayout: function () {
                            if (this.selected_layout !== null) return this.confirmSetLayout();

                            return this.doSetLayout();
                        },

                        confirmSetLayout: function () {
                            confirmation_modal.setRequestedAction('set_layout');
                            confirmation_modal.show();

                            var self = this;

                            $(confirmation_modal).on('operation_confirmed', function(e, r){
                                if(r.requested_action == 'set_layout') return self.doSetLayout();
                            });
                        },

                        doSetLayout: function () {
                            $('#LayoutsSection').collapse('hide');

                            this.selected_layout = this.layout;

                            confirmation_modal.hide();
                        }
                    }
                },

                modules_list: {
                    template: '#ModulesListTemplate',

                    props: ['selected_position', 'modules_list'],

                    data: function () {
                        return {
                            module_selected: false
                        };
                    },

                    methods: {
                        appendModule: function (url) {
                            this.$http.get(url).then(function (response) {
                                $('#ModulesListModal').modal('hide');

                                this.module_selected = true;

                                $('#ModulesListModal').on('hidden.bs.modal', function () {
                                    if (this.module_selected == true) {
                                        vm.editing = true;

                                        var resData = response.data;
                                        if(jQuery.isEmptyObject(response.data.content)) {
                                            resData = JSON.parse(response.data);
                                        }
                                        $('#EditingForm').find('#Title').text(resData.title);
                                        $('#EditingForm').find('#Content').html(resData.content);
                                    }
                                }.bind(this));
                            });
                        },

                        show: function () {
                            this.module_selected = false;

                            $('#ModulesListModal').modal('show');
                        }
                    }
                },

                page: {
                    template: '#PageTemplate',

                    props: ['selected_layout', 'existing_page'],

                    data: function () {
                        return {
                            layout: null,
                            positions: [],
                            selected_position: null
                        };
                    },

                    computed: {
                        positionHasModules: function () {
                            return this.selected_position != null && this.selected_position.modules.length != 0;
                        }
                    },

                    watch: {
                        selected_layout: function (new_layout) {
                            this.layout = new_layout.slug;
                            this.positions = new_layout.positions;
                        }
                    },

                    created: function () {
                        var self = this;

                        if (!$.isEmptyObject(self.existing_page)) {
                            this.layout = self.existing_page.layout;
                            this.positions = self.existing_page.positions;
                        }
                    },

                    methods: {
                        saveModule: function (data, module_index) {
                            var position = $.grep(vm.$refs.page.positions, function (e) {
                                return e.slug == vm.$refs.page.selected_position.slug;
                            })[0];

                            if (module_index !== undefined) {
                                this.updateModule(position, data, module_index);
                            } else {
                                this.addModule(position, data)
                            }


                            var selected_position = this.selected_position;
                            this.selected_position = null;
                            this.selected_position = selected_position;

                            vm.editing = false;

                            //$('#GeneralModal').modal('hide');
                        },

                        addModule: function (position, data) {
                            position.modules.push(data);
                        },

                        updateModule(position, data, index) {
                            position.modules[index] = data;
                        },

                        fetchData: function () {
                            return {
                                layout: this.layout,
                                positions: this.positions
                            };
                        }
                    },

                    components: {
                        position: {
                            template: '#PositionTemplate',

                            props: ['position', 'selected_position'],

                            created: function () {
                                if (this.position.modules == undefined) {
                                    this.position.modules = [];
                                }
                            },

                            computed: {
                                isSelected: function () {
                                    if (this.selected_position) {
                                        return this.selected_position.slug == this.position.slug;
                                    }

                                    return false;
                                }
                            },

                            methods: {
                                selectPosition: function () {
                                    this.selected_position = this.position;
                                },

                                openModulesList: function () {
                                    this.selected_position = this.position;

                                    vm.$refs.modules_list.show();
                                }
                            }
                        },

                        module: {
                            template: '#ModuleTemplate',

                            props: ['module', 'i'],

                            computed: {
                                title: function () {
                                    /*var title = '';

                                     $.each(this.module.data, function (i, v) {
                                     if (title.length != 0) {
                                     title += ' | ';
                                     }

                                     title += v.content.title;
                                     });

                                     return title;*/

                                    return this.module.title_as_text;
                                },

                                canMoveUp: function () {
                                    if (vm.$refs.page.selected_position.modules.length == 1) return false;

                                    if (this.i > 0) return true;

                                    return false;
                                },

                                canMoveDown: function () {
                                    if (vm.$refs.page.selected_position.modules.length == 1) return false;

                                    if (vm.$refs.page.selected_position.modules.length != (this.i + 1)) return true;

                                    return false;
                                }
                            },

                            methods: {
                                editModule: function (data) {
                                    var i = this.i;

                                    if(data.noEditing) return null;

                                    this.$http.post(window.urls.edit_module, {
                                        module: data, _token: window.token, module_index: i
                                    }).then(function (response) {
                                        /*var modal = $('#GeneralModal');

                                         modal.find('.modal-dialog').addClass('modal-lg');

                                         modal.find('.modal-title').text(response.data.title);
                                         modal.find('.modal-body').html(response.data.content);

                                         modal.modal('show');*/

                                        var form = $('#EditingForm');

                                        var resData = response.data;
                                        if(jQuery.isEmptyObject(response.data.content)) {
                                            resData = JSON.parse(response.data);
                                        }
                                        form.find('#Title').text(resData.title);
                                        form.find('#Content').html(resData.content);

                                        vm.editing = true;

                                    });
                                },

                                moveUp: function (i) {
                                    vm.$refs.page.selected_position.modules.move(i, i - 1);

                                    this.refreshModules();
                                },

                                moveDown: function (i) {
                                    vm.$refs.page.selected_position.modules.move(i, i + 1);

                                    this.refreshModules();
                                },

                                deleteModule: function (i) {
                                    vm.$refs.page.selected_position.modules.splice(i, 1);

                                    this.refreshModules();
                                },

                                refreshModules: function () {
                                    var selected_position = vm.$refs.page.selected_position
                                    vm.$refs.page.selected_position = null;
                                    vm.$refs.page.selected_position = selected_position;
                                }
                            }
                        }
                    }
                }
            }
        });

        var confirmation_modal = {
            modal: $('#ConfirmationModal'),

            show: function(){
                this.modal.modal('show');
            },

            setRequestedAction: function(action){
                this.modal.find('input[name=requested_action]').val(action)
            },

            getRequestedAction: function(){
                return this.modal.find('input[name=requested_action]').val()
            },

            confirmOperation: function(){
                return this.fireOperationConfirmed();
            },

            fireOperationConfirmed: function(){
                var self = this;

                $(this).trigger('operation_confirmed', {
                    requested_action: self.getRequestedAction()
                });
            },

            hide: function(){
                this.modal.modal('hide');
            }
        };
    </script>

    <script>
        $('#Slug').on('keydown', function () {
            var slug = slugify($(this).val());

            $(this).val(slug);
        });

        $('#Name_' + default_locale + ', #Title_' + default_locale).on('keydown keyup', function () {
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

        $('#PageCategoryId').on('change', function(){
            $.ajax({
                url: window.urls.get_categories_path,

                data: {category_id: $(this).val()},

                success: function(response){
                    $('#CategoriesPath').text(response.content);
                }
            });
        });
    </script>
    
    
    <script>
       
        $("#add_image_input").click(function(){
            $input = '<input class="form-control page_image_input"  name="page_images[]" type="file">';
            $("#image_input_container").append($input );
        }); 
        
        $(".remove_page_image").click(function(){
            $id = $(this).data('id');
            $page_id = $(this).data('page-id');
                     $rrr = $(this);
            if(confirm('Do you want delete?')){
                $.ajax({
                    url:'{{url('vc-admin/en/pages/delete_image')}}',
                    method: 'post',
                    data:{id:$id,"_token": "{{ csrf_token() }}",page_id:$page_id},
                    success: function (data) {
                        if(data == 'saved')
                            $rrr.closest('.page_image_item').remove();
                    }
                });
            }
            
            return false;
            
        });
        
        
    </script>
    
    
    <style>
    
    #image_input_container .page_image_input{
        margin: 20px 0;
    }
    </style>
@append