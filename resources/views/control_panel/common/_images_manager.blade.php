<div id="Manager" urls="{{ $urls }}" translations="{{ $translations }}" token="{{ $token }}">

    <div class="modal fade" id="ImagesManagerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" class="close refresh" v-on:click.prevent="refresh">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </button>
                    <h4 class="modal-title" id="ImagesManagerModalLabel">
                        @{{ header }}
                        <span v-show="loading == true">
                            <img src="{{ asset('assets/control-panel/images/loading.gif') }}" style="width: 15px;"/>
                        </span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div id="ImagesManager">
                        <breadcrumb :breadcrumb.sync="breadcrumb"
                                    :current_view.sync="current_view"
                                    v-ref:breadcrumb></breadcrumb>

                        <div v-show="inCategories">
                            <categories :categories="categories"
                                        :images="uncategorized_images"
                                        :albums.sync="albums"
                                        :current_view.sync="current_view"
                                        :breadcrumb.sync="breadcrumb"
                                        transition="fade"
                                        v-ref:categories></categories>

                            <hr>

                            <h4 class="text-center">{{ trans('labels.uncategorized_images') }}</h4>

                            <images :images="uncategorized_images" :album_id="0" v-ref:images></images>
                        </div>

                        <albums :albums="albums"
                                :images.sync="images"
                                :current_view.sync="current_view"
                                :selected_album.sync="selected_album"
                                :breadcrumb.sync="breadcrumb"
                                :mode="mode"
                                transition="fade"
                                v-show="inAlbums"
                                v-ref:albums></albums>

                        <images :images="images"
                                :album_id="selected_album.id"
                                transition="fade"
                                v-show="inImages"
                                v-ref:images></images>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="CategoriesTemplate">
        <div id="CategoriesList">
            <div class="row">
                <div class="col-sm-4" v-for="category in categories">
                    <div class="folder_widget"
                         v-on:click="fetchAlbums(category.id)"
                         title="@{{ category.name }}"
                    >
                        <img src="{{ asset('assets/control-panel/images/folder-back.png') }}" class="folder_back">
                        <img src="{{ asset('assets/control-panel/images/folder-front.png') }}" class="folder_front">
                        <h3 class="folder_name">@{{ category.name }}</h3>
                    </div>
                </div>
            </div>

            <div id="CreateNewCategory" v-show="creating_new">
                <div class="creating_new">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="CategoryName_{{ defaultLocale() }}"
                                   class="col-sm-3 control-label">{{ trans('labels.name') }}</label>
                            <div class="col-sm-7">
                                @foreach(siteLocales() as $locale)
                                    <div>
                                        <input type="text" id="CategoryName_{{ $locale }}" class="form-control"
                                               placeholder="{{ localizedLabel('name', $locale) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-7">
                                <button type="button" class="btn btn-primary"
                                        v-on:click.prevent="storeCategory">{{ trans('actions.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="AlbumsTemplate">
        <div id="AlbumsList">
            <div class="row">
                <div class="col-sm-4" v-for="album in albums">
                    <div class="folder_widget"
                         v-on:click="fetchImages(album.id)"
                         title="@{{ album.name }}"
                    >
                        <img src="{{ asset('assets/control-panel/images/folder-back.png') }}" class="folder_back">
                        <img src="{{ asset('assets/control-panel/images/folder-front.png') }}" class="folder_front">
                        <div class="images_list">
                            <div class="image_wrapper" v-for="image in album.latestImages">
                                <img v-bind:src="image.thumb_url">
                            </div>
                        </div>
                        <h3 class="folder_name">@{{ album.name }}</h3>
                    </div>
                </div>
            </div>
            <div id="CreateNewAlbum" v-show="creating_new">
                <div class="creating_new">
                    <div class="form-horizontal">
                        @foreach(siteLocales() as $locale)
                            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>

                            <div class="form-group">
                                <label for="AlbumName_{{ $locale }}"
                                       class="col-sm-3 control-label">{{ trans('labels.name') }}</label>
                                <div class="col-sm-7">
                                    <input type="text" id="AlbumName_{{ $locale }}" class="form-control"
                                           placeholder="{{ trans('labels.name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="AlbumDescription_{{ $locale }}"
                                       class="col-sm-3 control-label">{{ trans('labels.description') }}</label>
                                <div class="col-sm-7">
                                    <textarea id="AlbumDescription_{{ $locale }}" class="form-control" rows="5"
                                              placeholder={{ trans('labels.description') }}></textarea>
                                </div>
                            </div>

                            <hr>
                        @endforeach
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-7">
                                <button type="button" class="btn btn-primary"
                                        v-on:click.prevent="storeAlbum">{{ trans('actions.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="ImagesTemplate">
        <div id="ImagesList">
            <div class="image_widget" v-for="image in images">
                <img v-bind:src="image.thumb_url"
                     alt="@{{ image.caption }}"
                     class="img-thumbnail @{{ selectable ? 'selectable' : '' }}"
                     v-on:click="selectImage($index)"
                >

                <span class="actions">
                    <button type="button" class="btn btn-primary btn-xs" v-on:click.prevent="editCaption($index)">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-xs" v-on:click.prevent="deleteImage($index)">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </span>
            </div>

            <div id="EditCaptionForm" v-show="edit_caption_form">
                <div class="creating_new">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="ImageCaption_{{ defaultLocale() }}"
                                   class="col-sm-3 control-label">{{ trans('labels.caption') }}</label>
                            <div class="col-sm-7">
                                @foreach(siteLocales() as $locale)
                                    <input type="text" id="ImageCaption_{{ $locale }}" class="form-control"
                                           placeholder="{{ localizedLabel('caption', $locale) }}"
                                           v-model="selected_image.caption_{{ $locale }}"
                                    >
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-7" id="EditCaptionButton">
                                <button type="button" class="btn btn-primary"
                                        v-on:click.prevent="updateCaption">{{ trans('actions.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="image_size">
                <div class="form-group">
                    <label class="sr-only" for="ImageSize__Height">{{ trans('labels.height') }}</label>
                    <input type="text"
                           class="form-control"
                           id="ImageSize__Height"
                           placeholder="{{ trans('labels.height') }}"
                           v-model="height"
                    >
                </div>
                <div class="form-group">
                    <label class="sr-only" for="ImageSize__Width">{{ trans('labels.width') }}</label>
                    <input type="text"
                           class="form-control"
                           id="ImageSize__Width"
                           placeholder="{{ trans('labels.width') }}"
                           readonly="@{{ keep_aspect_ratio }}"
                           v-model="width"
                    >
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="keep_aspect_ratio"> {{ trans('messages.keep_aspect_ratio') }}
                    </label>
                </div>
            </div>

            <div data-action="{{ route('control_panel.images.dropzone_uploader') }}"
                 class="dropzone"
                 id="DropzoneUploader"
                 data-album-id="@{{ album_id }}"
                 data-height="@{{ height }}"
                 data-width="@{{ width }}"
                 data-keep-aspect-ratio="@{{ keep_aspect_ratio }}"
                 data-csrf-token="{{ csrf_token() }}"
            ></div>
        </div>
    </template>

    <template id="BreadcrumbTemplate">
        <div id="Breadcrumb">
            <ol class="breadcrumb" v-show="breadcrumb.length">
                <li v-for="item in breadcrumb"
                    class="@{{ isLast($index) ? 'active' : '' }}"
                >
                    <a href="#" v-if="!isLast($index)"
                       v-on:click="changeView(item.view, $index)"
                    >
                        @{{ item.title }}
                    </a>

                    <span v-else>@{{ item.title }}</span>
                </li>

                <button type="button" class="btn btn-primary btn-xs add_new_item text-center" v-show="canAdd"
                        v-on:click.prevenet="createNewItem">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
            </ol>
        </div>
    </template>

</div>

@section('js')
    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>

    <script>
        Vue.transition('fade', {
            enterClass: 'fadeIn',
            leaveClass: 'hide'
        });


        var manager = new Vue({
            el: '#Manager',

            props: ['urls', 'translations', 'token'],

            data: {
                categories: [],
                albums: [],
                images: [],

                uncategorized_images: [],

                loading: true,

                mode: 'images',

                current_view: '',

                selected_album: {},
                selected_category: {},

                breadcrumb: []
            },

            computed: {
                inCategories: function () {
                    return this.current_view == 'categories'
                },
                inAlbums: function () {
                    return this.current_view == 'albums'
                },
                inImages: function () {
                    return this.current_view == 'images'
                },

                header: function () {
                    return this.translations.header;
                }
            },

            watch: {
                current_view: function (value) {
                    if (value == 'images') {
                        this.$refs.breadcrumb.canAdd = false;
                    } else {
                        this.$refs.breadcrumb.canAdd = true;
                    }
                }
            },

            created: function () {
                this.translations = JSON.parse(this.translations);
                this.urls = JSON.parse(this.urls);

                this.breadcrumb.push({title: this.translations.all_categories, view: 'categories'});

                this.fetchCategories();
                this.fetchUncategorizedImages();
            },

            methods: {
                refresh: function () {
                    console.log('Refreshing');

                    var view = this.current_view;

                    this.current_view = null;

                    this.current_view = view;
                },

                setMode: function (mode) {
                    this.mode = mode;
                },

                show: function () {
                    $('#ImagesManagerModal').modal('show');
                },

                hide: function () {
                    $('#ImagesManagerModal').modal('hide');
                },

                fetchCategories: function () {
                    this.loading = true;

                    var url = this.urls.fetch_categories;

                    this.$http.get(url)
                            .then(function (response) {
                                this.categories = response.data.content;
                                this.loading = false;
                            }.bind(this));

                    this.current_view = 'categories';
                },

                fetchUncategorizedImages: function () {
                    this.loading = true;

                    var url = this.urls.fetch_uncategorized_images;

                    this.$http.get(url)
                            .then(function (response) {
                                this.uncategorized_images = response.data.content;
                                this.loading = false;
                            }.bind(this));
                }
            },

            components: {
                categories: {
                    template: '#CategoriesTemplate',

                    props: ['categories', 'uncategorized_images', 'albums', 'current_view', 'breadcrumb'],

                    data: function () {
                        return {
                            creating_new: false
                        };
                    },

                    methods: {
                        fetchAlbums: function (category_id) {
                            manager.loading = true;

                            var url = manager.urls.fetch_albums;

                            this.$http.get(url, {params: {category_id: category_id}})
                                    .then(function (response) {
                                        this.albums = response.data.content;
                                        this.current_view = 'albums';

                                        manager.loading = false;
                                    }.bind(this));

                            manager.selected_category = $.grep(this.categories, function (item) {
                                return item.id == category_id;
                            })[0];

                            this.updateBreadcrumb(category_id);
                        },

                        updateBreadcrumb: function (category_id) {
                            var category = $.grep(this.categories, function (item) {
                                return item.id == category_id;
                            })[0];

                            this.breadcrumb = this.breadcrumb.splice(0, 1)

                            this.breadcrumb.push({
                                title: category.name,
                                view: 'albums'
                            });
                        },

                        storeCategory: function () {
                            manager.loading = true;

                            var data = {
                                '_token': manager.token
                            };

                            $.each(locales, function (i, locale) {
                                data['name_' + locale] = $('#CategoryName_' + locale).val();
                            });

                            var url = manager.urls.store_category;

                            this.$http.post(url, data)
                                    .then(function (response) {
                                        this.categories.push(response.data);

                                        $('#CreateNewCategory input[type=text]').val('');

                                        manager.loading = false;
                                    }.bind(this));
                        }
                    }
                },

                albums: {
                    template: '#AlbumsTemplate',

                    props: ['albums', 'images', 'current_view', 'breadcrumb', 'mode', 'selected_album'],

                    data: function () {
                        return {
                            creating_new: false
                        };
                    },

                    methods: {
                        fetchImages: function (album_id) {
                            if (this.mode == 'albums') return this.fireAlbumSelectedEvent(album_id);

                            manager.loading = true;

                            var url = manager.urls.fetch_images;

                            this.$http.get(url, {params: {album_id: album_id}})
                                    .then(function (response) {
                                        this.images = response.data.content;
                                        this.current_view = 'images';

                                        manager.loading = false;
                                    }.bind(this));

                            this.updateBreadcrumb(album_id);

                            this.selected_album = $.grep(this.albums, function (item) {
                                return item.id == album_id;
                            })[0];
                        },

                        fireAlbumSelectedEvent: function (album_id) {
                            var album = $.grep(this.albums, function (item) {
                                return item.id == album_id;
                            })[0];

                            $(manager).trigger('im.album_selected', [album]);
                        },

                        updateBreadcrumb: function (album_id) {
                            var album = $.grep(this.albums, function (item) {
                                return item.id == album_id;
                            })[0];

                            this.breadcrumb = this.breadcrumb.splice(0, 2);

                            this.breadcrumb.push({
                                title: album.name,
                                view: 'images'
                            });
                        },

                        storeAlbum: function () {
                            manager.loading = true;

                            var data = {
                                '_token': manager.token,
                                album_category_id: manager.selected_category.id
                            };

                            $.each(locales, function (i, locale) {
                                data['name_' + locale] = $('#AlbumName_' + locale).val();
                                data['description_' + locale] = $('#AlbumDescription_' + locale).val();
                            });

                            var url = manager.urls.store_album;

                            this.$http.post(url, data)
                                    .then(function (response) {
                                        this.albums.push(response.data);

                                        $('#CreateNewAlbum input[type=text]').val('');
                                        $('#CreateNewAlbum textarea').val('');

                                        manager.loading = false;
                                    }.bind(this));
                        }
                    }
                },

                images: {
                    template: '#ImagesTemplate',

                    props: ['images', 'album_id'],

                    data: function () {
                        return {
                            selected_image: {},

                            edit_caption_form: false,

                            keep_aspect_ratio: true,

                            height: 768,

                            width: ''

                        };
                    },

                    computed: {
                        selectable: function () {
                            return manager.mode == 'images';
                        },

                        caption: function () {
                            return this.selected_image.caption;
                        }
                    },

                    watch: {
                        album_id: function () {
                            $('.dropzone').attr('data-album-id', this.album_id);
                        },

                        height: function () {
                            $('.dropzone').attr('data-height', this.height);
                        },

                        width: function () {
                            $('.dropzone').attr('data-width', this.width);
                        },

                        keep_aspect_ratio: function () {
                            $('.dropzone').attr('data-keep-aspect-ratio', this.keep_aspect_ratio);

                            this.width = this.keep_aspect_ratio == true ? '' : this.width;
                        }
                    },

                    methods: {
                        selectImage: function (i) {
                            this.fireImageSelectedEvent(i);
                        },

                        fireImageSelectedEvent: function (i) {
                            var image = this.images[i];

                            $(manager).trigger('im.image_selected', [image]);
                        },

                        editCaption: function (i) {
                            this.selected_image = this.images[i];
                            this.edit_caption_form = !this.edit_caption_form;
                        },

                        updateCaption: function () {
                            manager.loading = true;

                            var self = this;

                            var data = {
                                '_token': manager.token,
                                'id': this.selected_image.id,
                            };

                            $.each(locales, function(i, locale){
                                data['caption_' + locale] = self.selected_image['caption_' + locale];
                            });

                            this.$http.patch(manager.urls.update_image, data)
                                    .then(function () {
                                        manager.loading = false;

                                        this.edit_caption_form = false;
                                    }.bind(this));

                        },

                        deleteImage: function (i) {
                            var data = {
                                '_token': manager.token,
                                'id': this.images[i].id,
                            };

                            manager.loading = true;

                            this.$http.post(manager.urls.delete_image, data)
                                    .then(function () {
                                        this.images.splice(i, 1);

                                        manager.loading = false;
                                    }.bind(this));
                        }
                    }
                },

                breadcrumb: {
                    template: '#BreadcrumbTemplate',

                    props: ['breadcrumb', 'current_view'],

                    data: function () {
                        return {
                            canAdd: true,
                        }
                    },

                    methods: {
                        changeView: function (view, $index) {
                            this.breadcrumb = this.breadcrumb.splice(0, $index + 1);

                            this.current_view = view;
                        },

                        isLast: function ($index) {
                            return this.breadcrumb.length == $index + 1;
                        },

                        createNewItem: function () {
                            if (manager.current_view == 'categories') {
                                manager.$refs.categories.creating_new
                                        ? manager.$refs.categories.creating_new = false
                                        : manager.$refs.categories.creating_new = true;
                            } else {
                                manager.$refs.albums.creating_new
                                        ? manager.$refs.albums.creating_new = false
                                        : manager.$refs.albums.creating_new = true;
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script src="{{ asset('assets/control-panel/js/dropzone.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;

        var el = $('.dropzone')

        el.dropzone({
            url: el.attr('data-action'),
            paramName: 'uploaded_image',
            maxFilesize: 2,
            acceptedFiles: 'image/*',
            init: function () {
                var total_uploaded_files = 0;

                this.on('success', function (file, response) {
                    total_uploaded_files += 1;

                    if (response.image.album_id == 0) {
                        manager.uncategorized_images.push(response.image);
                    } else {
                        manager.images.push(response.image);
                    }
                });

                this.on('queuecomplete', function () {
                    this.removeAllFiles()

                    manager.loading = false;
                });

                this.on('sending', function (file, xhr, formData) {
                    manager.loading = true;

                    formData.append('album_id', el.attr('data-album-id'));
                    formData.append('_token', el.attr('data-csrf-token'));
                    formData.append('height', el.attr('data-height'));
                    formData.append('width', el.attr('data-width'));
                    formData.append('keep_aspect_ratio', el.attr('data-keep-aspect-ratio'));
                });
            }

        });
    </script>

    <script>
        $(manager).on('im.image_selected', function (e, data) {
            console.log(data);
        });

        $(manager).on('im.album_selected', function (e, data) {
            console.log(data);
            manager.hide();
        });
    </script>
@append

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/vue-animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/dropzone.min.css') }}">
@append