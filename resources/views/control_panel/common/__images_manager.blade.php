@extends('control_panel.layouts.default')

@section('content')
  

    <div class="modal fade" id="ImagesManagerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        {{ trans('labels.images_manager') }}
                        <span v-show="loading == true">
                            <img src="{{ asset('assets/control-panel/images/loading.gif') }}" style="width: 15px;" />
                        </span>
                    </h4>
                </div>
                <div class="modal-body">
                    <breadcrumb :items="breadcrumb" v-show="breadcrumb.length != 0"></breadcrumb>

                    <categories :categories="categories" v-show="current_view == 'categories'" transition="fade"></categories>

                    <albums :albums="albums" v-show="current_view == 'albums'" transition="fade"></albums>

                    <images :images="images" :album_id="selected_album" :selectable="mode == 'images'" v-show="current_view == 'images'" transition="fade"></images>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <template id="CategoriesTemplate">
        <div class="row">
            <div class="col-sm-4" v-for="category in categories">
                <div class="folder_widget"
                     v-on:click="loadAlbums(category.id)"
                     title="@{{ category.name }}"
                >
                    <img src="{{ asset('assets/site/images/folder-back.png') }}" class="folder_back">
                    <img src="{{ asset('assets/site/images/folder-front.png') }}" class="folder_front">
                    <h3 class="folder_name">@{{ category.name }}</h3>
                </div>
            </div>
        </div>
    </template>

    <template id="AlbumsTemplate">
        <div id="ImagesManager__AlbumsList">
            <div class="row">
                <div class="col-sm-4" v-for="album in albums">
                    <div class="folder_widget"
                         v-on:click="loadImages(album.id)"
                         title="@{{ album.name }}"
                    >
                        <img src="{{ asset('assets/site/images/folder-back.png') }}" class="folder_back">
                        <img src="{{ asset('assets/site/images/folder-front.png') }}" class="folder_front">
                        <div class="images_list">
                            <div class="image_wrapper" v-for="image in album.latestImages">
                                <img v-bind:src="image.thumb_url">
                            </div>
                        </div>
                        <h3 class="folder_name">@{{ album.name }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="ImagesTemplate">
        <div id="ImagesManager__ImagesList">

            <img v-for="image in images"
                 v-bind:src="'/thumb/' + image.thumb_filename"
                 alt="@{{ image.caption }}"
                 class="img-thumbnail @{{ selectable ? 'selectable' : '' }}"
                 v-on:click="selectImage($index)"
            >

            <div data-action="{{ route('control_panel.images.dropzone_uploader') }}"
                 class="dropzone"
                 id="DropzoneUploader"
                 data-album-id="@{{ album_id }}"
                 data-csrf-token="{{ csrf_token() }}"
            ></div>

        </div>
    </template>

    <template id="BreadcrumbTemplate">
        <ol class="breadcrumb" v-show="items.length != 0">
            <li v-for="item in items"
                class="@{{ $index == (items.length - 1) ? 'active' : '' }}"
            >
                <a href="#" v-if="$index != (items.length - 1)"
                   v-on:click="navigate($index, item.type)"
                >
                    @{{ item.title }}
                </a>

                <span v-else>@{{ item.title }}</span>
            </li>
        </ol>
    </template>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/vue-animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/dropzone.min.css') }}">
@append

@section('js')
    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>

    <script src="{{ asset('assets/control-panel/js/ckeditor/ckeditor.js') }}"></script>

    <script>
        Vue.transition('fade', {
            enterClass: 'fadeIn',
            leaveClass: 'hide'
        });

        var manager = new Vue({
            el: '#ImagesManagerModal',

            data: {
                categories: [],
                albums: [],
                images: [],
                breadcrumb: [{type: 'categories', title: 'All Categories'}],
                isReady: false,
                current_view: 'categories',
                loading: false,
                mode: 'images',
                selected_album: null
            },

            created: function () {
                this.loadCategories();
            },

            methods: {
                loadCategories: function () {
                    this.$http.get('/vc-admin/en/images-manager/categories')
                            .then(function (response) {
                                this.categories = response.data.content;
                                this.isReady = true;
                            }.bind(this));
                },

                show: function (element) {
                    var element = $(element);

                    if (manager.isReady == true) {
                        $('#ImagesManagerModal').modal('show');
                        element.find('img.loading_image_sm').remove();
                    } else {
                        element.append(manager.loadingImage());
                        manager.show();
                    }
                },

                loadingImage: function () {
                    return '<img src="/assets/control-panel/images/loading.gif" class="loading_image_sm" />';
                }
            },

            components: {
                categories: {
                    template: '#CategoriesTemplate',

                    props: ['categories'],

                    methods: {
                        loadAlbums: function (category_id) {
                            manager.loading = true;

                            this.$http.get('/vc-admin/en/images-manager/categories/' + category_id + '/albums/')
                                    .then(function (response) {
                                        manager.albums = response.data.content;
                                        manager.current_view = 'albums';

                                        var category = $.grep(this.categories, function (e) {
                                            return e.id == category_id;
                                        })[0];

                                        this.setBreadcrumb(category.name);

                                        manager.loading = false;
                                    });
                        },

                        setBreadcrumb: function(category_name){
                            manager.breadcrumb = manager.breadcrumb.slice(0, 1);
                            manager.breadcrumb.push({
                                type: 'albums',
                                title: category_name
                            });
                        }
                    }
                },

                albums: {
                    template: '#AlbumsTemplate',

                    props: ['albums'],

                    methods: {
                        loadImages: function (album_id) {
                            manager.loading = true;

                            manager.selected_album = album_id;

                            this.$http.get('/vc-admin/en/images-manager/albums/' + album_id + '/images')
                                    .then(function (response) {
                                        manager.images = response.data.content;
                                        manager.current_view = 'images';

                                        var album = $.grep(this.albums, function (e) {
                                            return e.id == album_id;
                                        })[0];

                                        manager.breadcrumb.push({type: 'albums', title: album.name});

                                        manager.loading = false;
                                    });
                        },

                        setBreadcrumb: function(album_name){
                            manager.breadcrumb.slice(2, 0).push({
                                type: 'images',
                                title: album_name
                            });
                        }
                    }
                },

                images: {
                    template: '#ImagesTemplate',

                    props: ['images', 'selectable', 'album_id'],

                    methods: {
                        selectImage: function(i){
                            $(manager).trigger('image_selected', this.images[i]);
                        }
                    }
                },

                breadcrumb: {
                    template: '#BreadcrumbTemplate',

                    props: ['items'],

                    methods: {
                        navigate: function (index, view) {
                            manager.breadcrumb = manager.breadcrumb.slice(0, index + 1);

                            manager.current_view = view;
                        }
                    }
                }
            }
        });




        var editor = CKEDITOR.replace('Editor', {
            language: 'en'
        });

        /*editor.addCommand('openImageGallery', {
            exec: function(){
                console.log('Opening images gallery.');
                $('#ImagesGalleryModal').modal();
            }
        })

        editor.ui.addButton('images_gallery', {
            label: "{{ trans('labels.images_gallery') }}",
            command: 'openImageGallery',
            toolbar: 'insert',
        });*/





        $(document).ready(function(){
            $(manager).on('image_selected', function(e, image){
                CKEDITOR.instances.Editor.insertHtml('<img src="/image/' + image.image_filename + '">');
            })
        });
    </script>

    <script src="{{ asset('assets/control-panel/js/dropzone.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        $('#DropzoneUploader').dropzone({
            url: '/vc-admin/en/images/dropzone-uploader',
            paramName: 'uploaded_image',
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            init: function () {
                var total_uploaded_files = 0;

                this.on('success', function (file, response) {
                    total_uploaded_files += 1;
                    manager.images.push(response.image);
                })

                this.on('queuecomplete', function () {
                    this.removeAllFiles()

                    manager.loading = false;
                });

                this.on('sending', function (file, xhr, formData) {
                    manager.loading = true;

                    var element = $('#DropzoneUploader');

                    formData.append('album_id', element.attr('data-album-id'));
                    formData.append('_token', element.attr('data-csrf-token'));
                });
            }

        });
    </script>
@append