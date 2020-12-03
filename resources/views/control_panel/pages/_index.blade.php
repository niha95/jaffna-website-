@extends('control_panel.layouts.default')

@section('content')
    @include('control_panel.pages._filters')

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.pages') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$pages->isEmpty())
                <div id="PagesTable" :pages="{{ $pages->getCollection()->toJson() }}">
                    <div class="table-responsive">
                        <div class="form-group">
                            <input type="text"
                                   placeholder="search in this page"
                                   class="form-control"
                                   v-on:keyup="search"
                            >
                        </div>
                        <table class="table table-hover">
                            <tr>
                                <th>#</th>
                                <th>
                                    <a href="javascript: void(0)"
                                       v-on:click.prevent="sort('title_translated')"
                                    >
                                        {{ trans('labels.title') }}
                                    </a>
                                </th>
                                <th>{{ trans('labels.featured_image') }}</th>
                                <th>{{ trans('labels.category') }}</th>
                                <th>{{ trans('labels.status') }}</th>
                                <th></th>
                            </tr>

                            <tbody>
                                <tr v-for="page in pages" is="page" :page="page" :i="$index"></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{ $pages->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.pages')]) }}</p>
            @endif
        </div>
    </div>

    <template id="PageTemplate">
        <tr>
            <td>@{{ i + 1 }}</td>
            <td>@{{{ page.title_translated }}}</td>
            <td>
                <img v-if="page.featuredImage"
                     v-bind:src="page.featuredImage.thumbFullLink"
                     alt="@{{ page.title_default }}"
                     class="img-thumbnail image_tiny"
                >
            </td>
            <td>
                <a v-if="page.category"
                   href="@{{ page.category.urls.edit }}"
                   target="_blank"
                >
                    @{{ page.category.name_translated_piped }}
                </a>
                <span v-else>{{ trans('labels.uncategorized') }}</span>
            </td>
            <td>@{{{ page.status }}}</td>
            <td nowrap>
                <a href="@{{ page.urls.set_home }}" class="btn btn-primary" target="_blank">
                    <i class="fa fa-home" aria-hidden="true"></i></a>
                <a href="@{{ page.urls.preview }}" class="btn btn-primary" target="_blank">
                    {{ trans('actions.preview') }}</a>
                <a href="@{{ page.urls.edit }}" class="btn btn-primary">
                    {{ trans('actions.edit')}}</a>
                <a href="javascript: void(0)" data-url="@{{ page.urls.destroy }}"
                   class="btn btn-danger" onclick="confirmDeleteEntity(this)">{{ trans('actions.delete')}}</a>
            </td>
        </tr>
    </template>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2-bootstrap.min.css') }}">
@append

@section('js')
    <script src="{{ asset('assets/control-panel/js/underscore-min.js') }}"></script>
    <script src="{{ asset('assets/control-panel/js/vue.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/0.9.3/vue-resource.js"></script>
    <script>
        var pages = new Vue({
            el: '#PagesTable',

            props: ['pages'],

            data: {
                current_sorted_column: '',
                sort_type: '',
                original: []
            },

            created: function(){
                this.original = JSON.parse(JSON.stringify(this.pages));
            },

            methods: {
                sort: function(column){
                    var sort_type = 'asc';
                    if(this.current_sorted_column == column) {
                        sort_type = this.sort_type == 'asc' ? 'desc' : 'asc';
                    }

                    this.current_sorted_column = column;
                    this.sort_type = sort_type;

                    this.pages = this.pages.sort(function(a, b){
                        if(sort_type == 'asc') {
                            return a[column] == b[column] ? 0 : +(a[column] > b[column]) || -1;
                        } else {
                            return a[column] == b[column] ? 0 : +(a[column] < b[column]) || -1;
                        }
                    });
                },

                search: function(){
                    var value = $(event.currentTarget).val();

                    this.pages = JSON.parse(JSON.stringify(this.original));

                    if(!value.length) return false;

                    this.pages = $.grep(this.pages, function(item){
                        var search = item.title_translated.toLowerCase().search(value.toLowerCase());

                        if(search !== -1) {
                            var i = item.title_translated;

                            var end = search + value.length;

                            item.title_translated = [
                                i.slice(0, search),
                                '<span class="selected">',
                                i.slice(search, end),
                                '</span>',
                                i.slice(end)
                            ].join('');

                            return true;
                        }
                    });
                }
            },

            components: {
                page: {
                    template: '#PageTemplate',

                    props: ['page', 'i']
                }
            }
        });
    </script>


    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script>
        $('input[name=categorized]').on('change', function(){
            if($(this).val() == 'specific') {
                $('select[name=categories]').attr('disabled', false);
            } else {
                $('select[name=categories]').attr('disabled', true);
            }
        });

        $('#PagesCategories').select2({
            width: '100%'
        });

        $('#FilterButton').on('click', function(){
            var filters = $('#EntitiesFilters');

            var url = window.location.href.split('?')[0];

            var data = {};
            var value;

            data.sort = filters.find('select[name=order]').val();

            value = filters.find('input[name=keywords]').val();
            if(value != null && value.trim().length != 0) data.keywords = value.trim();

            value = filters.find('input[name=featured_image]').prop('checked');
            if(value == true) data['has-featured-image'] = true;

            value = filters.find('input[name=categorized]:checked').val();
            switch (value) {
                case 'categorized':
                    data.categorized = true;
                    break;
                case 'uncategorized':
                    data.uncategorized = true;
                    break;
                case 'specific':
                    value = filters.find('select[name=categories]').val();
                    if(value != null && value.length != 0) data.categories = value.join(' ');
            }

            value = filters.find('input[name=status]:checked').val();
            if(value != 'all') data.status = value;

            window.location.href = url + '?' + $.param(data);
        });
    </script>
@append