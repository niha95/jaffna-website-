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
                        <input type="radio" name="categorized" value="specific">
                        {{ trans('labels.specific') }}
                    </label>
                    <hr>
                    {{ Form::select('categories', $categories, null,
                        ['class' => 'form-control', 'id'  => 'AlbumsCategories', 'multiple', 'disabled']) }}
                </td>
                <td>
                    {{ Form::select('order', $orders, null, ['class' => 'form-control', 'id' => 'PagesOrder']) }}
                </td>
                <td>
                    {{ Form::text('keywords', null, ['class' => 'form-control', 'id' => 'PagesKeywords']) }}
                </td>
                <td>
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

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/control-panel/css/select2-bootstrap.min.css') }}">
@append

@section('js')
    <script src={{ asset('assets/control-panel/js/select2/select2.full.min.js') }}></script>
    <script>
        $('input[name=categorized]').on('change', function(){
            if($(this).val() == 'specific') {
                $('select[name=categories]').attr('disabled', false);
            } else {
                $('select[name=categories]').attr('disabled', true);
            }
        });

        $('#AlbumsCategories').select2({
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

            value = filters.find('input[name=categorized]:checked').val();
            switch (value) {
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