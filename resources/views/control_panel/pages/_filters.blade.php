<div id="EntitiesFilters">
    <div class="section">
        <table class="table table-bordered">
            <tr>
                <th>{{ trans('labels.category') }}</th>
                <th>{{ trans('labels.order') }}</th>
                <th>{{ trans('labels.keywords') }}</th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td>
                    <label class="radio-inline">
                        <input type="radio" name="categorized" value="all" checked>
                        {{ trans('labels.all') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="categorized" value="categorized">
                        {{ trans('labels.categorized') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="categorized" value="uncategorized">
                        {{ trans('labels.uncategorized') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="categorized" value="specific">
                        {{ trans('labels.specific') }}
                    </label>
                    <hr>
                    {{ Form::select('categories', $categories, null,
                        ['class' => 'form-control', 'id'  => 'PagesCategories', 'multiple', 'disabled']) }}
                </td>
                <td>
                    {{ Form::select('order', $orders, null, ['class' => 'form-control', 'id' => 'PagesOrder']) }}
                </td>
                <td>
                    {{ Form::text('keywords', null, ['class' => 'form-control', 'id' => 'PagesKeywords']) }}
                </td>
                <td>
                    <div class="checkbox">
                        <label>
                            <input name="featured_image" type="checkbox"> {{ trans('labels.featured_image') }}
                        </label>
                    </div>

                    <hr>

                    <label class="radio-inline">
                        <input type="radio" name="status" value="all" checked> {{ trans('labels.all') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="active"> {{ trans('labels.statuses.active') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="inactive"> {{ trans('labels.statuses.inactive') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status" value="featured"> {{ trans('labels.statuses.featured') }}
                    </label>
                </td>
                <td class="text-center">
                    <button type="button" id="FilterButton" class="btn btn-primary">{{ trans('actions.filter') }}</button>
                </td>
            </tr>
        </table>

        @if(!empty($filters_as_string))
            <p class="text-muted">{{ $filters_as_string }}</p>
        @endif
    </div>
</div>