<div id="LinkSelector" :urls="{{ $urls }}" :translations="{{ $translations }}" :misc="{{ $misc }}" :types="{{ $types }}">
    <div class="input-group">
        {{ Form::text('link', null, ['class' => 'form-control', 'placeholder' => trans('labels.link'), 'readonly']) }}
        <span class="input-group-btn">
            <button class="btn btn-primary" type="button" id="LinkSelectorButton"
                    v-on:click.prevent="show">{{ trans('actions.set') }}</button>
        </span>
    </div>

    <div class="modal fade" id="LinkSelectorModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="LinkSelectorModalLabel">
                        {{ trans('labels.set_link_modal') }}
                        <span v-show="loading == true">
                            <img src="{{ asset('assets/control-panel/images/loading.gif') }}"
                                 style="width: 15px; position: relative; top: -2px;"/>
                        </span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="LinkTypes">
                        <label class="radio-inline" v-for="type in types">
                            <input name="link_type" type="radio" value="@{{ type.slug }}" checked="@{{ $index == 0 ? true : false }}"
                                   v-on:change.prevent="changeView(type)"> @{{ type.title }}
                        </label>
                    </div>

                    <static :selected_link.sync="selected_link" v-show="current_view == 'static'" v-ref:static></static>

                    <misc :selected_link.sync="selected_link" :misc="misc" v-show="current_view == 'misc'" v-ref:misc></misc>

                    <page :urls="urls" :translations="translations" v-show="current_view == 'category_or_page'" v-ref:page></page>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('actions.close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click.prevent="setLink">{{ trans('actions.set') }}</button>
                </div>
            </div>
        </div>
    </div>

    <template id="StaticTemplate">
        <div class="form-group">
            <label for="Link">{{ trans('labels.link') }}</label>
            <input type="text" class="form-control" id="Link" v-model="selected_link">
        </div>
    </template>

    <template id="MiscTemplate">
        <div id="LinkSelector__Misc">
            <div class="radio" v-for="item in misc">
                <label>
                    <input type="radio" name="misc" v-model="selected_link" value="@{{ item.uri }}">
                    @{{ item.label }}
                </label>
            </div>
        </div>
    </template>

    <template id="PageTemplate">
        <div id="LinkSelector__Page">
            <breadcrumb :path="path"></breadcrumb>

            <div v-if="categories.length != 0">
                <div class="row" v-for="chunk in categories.chunk(5)">
                    <div  v-for="item in chunk">
                        <div class="item" v-bind:class="{ selected: isSelected(item) }"
                             v-on:dblclick.prevent="fetchCategoriesAndPages(item)"
                             v-on:click.prevent="selectItem(item)"
                        >
                            <i class="fa fa-folder fa-fw" aria-hidden="true"></i>
                            <a href="javascript: void(0)">@{{ item.name_translated_piped }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else>@{{ translations.no_categories }}</p>

            <hr>

            <div v-if="pages.length != 0">
                <div class="row" v-for="chunk in pages.chunk(5)">
                    <div  v-for="item in chunk">
                        <div class="item" v-bind:class="{ selected: isSelected(item) }"
                             v-on:click.prevent="selectItem(item)"
                        >
                            <i class="fa fa-file-text fa-fw" aria-hidden="true"></i>
                            <a href="javascript: void(0)">@{{ item.title_translated_piped }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <p v-else>@{{ translations.no_pages }}</p>
        </div>
    </template>

    <template id="BreadcrumbTemplate">
        <ol class="breadcrumb">
            <li v-for="item in path" class="@{{ path.length == ($index + 1) ? 'active' : '' }}">
                <a href="javascript: void(0)" v-if="path.length > $index + 1"
                   v-on:click.prevenet="fetchCategoriesAndPages(item)"
                >
                    @{{ item.name_translated_piped }}
                </a>
                <span v-else>@{{ item.name_translated_piped }}</span>
            </li>
        </ol>
    </template>
</div>

@section('js')
    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>

    <script>
        var link_selector = new Vue({
            el: '#LinkSelector',

            props: ['urls', 'translations', 'misc', 'types'],

            data: {
                modal: $('#LinkSelectorModal'),
                loading: false,
                current_view: 'category_or_page',
                selected_link: ''
            },

            methods: {
                show: function(){
                    this.modal.modal('show');
                },

                hide: function(){
                    this.modal.modal('hide');
                },

                changeView: function(type){
                    this.current_view = type.slug;
                },

                setLink: function(){
                    $('input[name=link]').val(this.selected_link);

                    this.fireLinkSelected();
                },

                fireLinkSelected: function(){
                    var self = this;

                    $(this).trigger('ls.link_selected', {
                        uri: self.selected_link
                    })

                    self.hide();
                }
            },

            events: {
                'ls.loading': function(status){
                    this.loading = status;
                },

                'ls.item_selected': function(url){
                    this.selected_link = url;
                }
            },

            components: {
                misc: {
                    template: '#MiscTemplate',

                    props: ['selected_link', 'misc'],

                    methods: {
                        selectItem: function(uri){
                            this.selected_link = uri;
                        }
                    }
                },

                static: {
                    template: '#StaticTemplate',

                    props: ['selected_link']
                },

                page: {
                    template: '#PageTemplate',

                    props: ['urls', 'translations'],

                    data: function(){
                        return {
                            categories: [],
                            pages: [],
                            path: [],
                            selectedItem: {}
                        };
                    },

                    created: function(){
                        this.fetchCategoriesAndPages(0);
                    },

                    methods: {
                        fetchCategoriesAndPages: function(category){
                            var self = this;
                            var parent_id = category == 0 ? category : category.id;

                            self.$dispatch('ls.loading', true);

                            this.$http.get(this.urls.fetch_categories_and_pages, {params: {parent_id: parent_id}})
                                    .then(function (response) {
                                       if(typeof(response.data) != 'object'){
                                           response.data = JSON.parse(response.data);
                                        }
                                        
                                        self.categories = response.data.categories;
                                        self.pages = response.data.pages;
                                        self.path = response.data.path;

                                        self.$dispatch('ls.loading', false);

                                        self.selectItem(category != 0 ? category : null);
                                    });
                        },

                        selectItem: function(item){
                            this.selectedItem = item;

                            var url = item != null ? item.url : '';

                            this.$dispatch('ls.item_selected', url);
                        },

                        isSelected: function(item){
                            if(item == null || this.selectedItem == null) return false;

                            return this.selectedItem.url == item.url;
                        }
                    },

                    components: {
                        breadcrumb: {
                            template: '#BreadcrumbTemplate',

                            props: ['path'],

                            methods: {
                                fetchCategoriesAndPages: function(category){
                                    return link_selector.$refs.page.fetchCategoriesAndPages(category)
                                }
                            }
                        }
                    }
                }
            }
        });

        /*var link_selector = new Vue({
            el: '#LinkSelector',

            props: ['urls', 'translations', 'misc', 'types'],

            data: {
                links: [],
                modal: $('#LinkSelectorModal'),
                loading: false,
                current_view: 'category_or_page',
                selected_link: ''
            },

            methods: {
                show: function(){
                    this.modal.modal('show');
                },

                hide: function(){
                    this.modal.modal('hide');
                },

                changeView: function(type){
                    this.current_view = type.slug;
                },

                fireLinkSelectedEvent: function(link){
                    $(this).trigger('ls.link_selected', {
                        link: link
                    });

                    this.hide();
                }
            },

            events: {
                ls_loading: function(status){
                    this.loading = status;
                },

                ls_link_selected: function(url){
                    this.selected_link = url;
                }
            },

            components: {
                page: {
                    template: '#PageTemplate',

                    props: ['urls', 'translations'],

                    data: function(){
                        return {
                            current_url: '',
                            categories: [],
                            pages: [],
                            path: []
                        };
                    },

                    created: function(){
                        this.fetchRoots();
                    },

                    methods: {
                        fetchRoots: function(){
                            var self = this;

                            self.$dispatch('ls_loading', true);

                            this.$http.get(this.urls.fetch_roots)
                                    .then(function (response) {
                                        self.categories = response.data.categories;
                                        self.pages = response.data.pages;

                                        self.path = [{
                                            id: 0,
                                            name_translated_piped: link_selector.translations.home,
                                        }];

                                        self.$dispatch('ls_loading', false);
                                    });
                        },

                        fetchChildren: function(item){
                            var self = this;

                            self.$dispatch('ls_link_selected', item.url);

                            self.$dispatch('ls_loading', true);

                            this.$http.get(this.urls.fetch_children, {params: {id: item.id}})
                                    .then(function (response) {
                                        self.categories = response.data.children;

                                        response.data.path.unshift({
                                            id: 0,
                                            name_translated_piped: link_selector.translations.home,
                                        });
                                        self.path = response.data.path;

                                        self.$dispatch('ls_loading', false);
                                    });
                        },

                        selectPage: function(page){
                            this.$dispatch('ls_link_selected', page.url);
                        }
                    },

                    components: {
                        breadcrumbs: {
                            template: '#BreadcrumbsTemplate',

                            props: ['items'],

                            methods: {
                                fetchChildren: function(item){
                                    if(item.id == 0) return link_selector.$refs.page.fetchRoots();

                                    return link_selector.$refs.page.fetchChildren(item);
                                }
                            }
                        }
                    }
                },

                external: {},

                misc: {}
            }
        });*/
    </script>
@append