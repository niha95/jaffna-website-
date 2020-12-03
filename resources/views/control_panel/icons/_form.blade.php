<div class="row">
    <div class="col-sm-8">
        @foreach(siteLocales() as $locale)
            <div class="section">
                <a class="section_collapse" role="button" data-toggle="collapse" href="#Section1_{{ $locale }}">&minus;</a>
                <div id="Section1_{{ $locale }}" class="in">
                    @if(count(siteLocales()) > 1)
                        <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                    @endif
                    <div class="form-group">
                        {{ Form::label('Status_' . $locale, trans('labels.status'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            <label class="radio-inline">
                                {{ Form::radio('status_' . $locale, 'active', true) }} {{ trans('labels.statuses.active') }}
                            </label>
                            <label class="radio-inline">
                                {{ Form::radio('status_' . $locale, 'inactive', false) }} {{ trans('labels.statuses.inactive') }}
                            </label>
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Title_' . $locale, trans('labels.title'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('title_' . $locale, null,
                                ['class' => 'form-control', 'id' => 'Title_' . $locale, 'placeholder' => trans('labels.title')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Excerpt_' . $locale, trans('labels.excerpt'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('excerpt_' . $locale, null,
                                ['class' => 'form-control', 'id' => 'Excerpt_' . $locale, 'placeholder' => trans('labels.excerpt'), 'rows' => '3']) }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="section">
            <div class="form-group">
                {{ Form::label('Link', trans('labels.link'), ['class' => 'control-label col-sm-2']) }}
                <div class="col-sm-10">
                    @include('control_panel.common._link_selector')
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="section">
            <div class="form-group">
                {{ Form::label('Icon', trans('labels.icon'), ['class' => 'col-sm-12']) }}
                <div class="col-sm-12">
                    {{ Form::file('icon', ['class' => 'form-control', 'id' => 'Icon']) }}
                </div>
            </div>
            @if(isset($item) && $item->iconImg)
                <div class="text-center">
                    <hr>
                    <img src="{{ $item->iconImg->imageFullLink() }}"
                         alt="{{ $item->title_translated_piped }}"
                         class="img-thumbnail"
                    >
                </div>
            @endif
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                <button type="submit" class="btn btn-primary" name="save_and_continue">{{ trans('actions.save_and_continue') }}</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        $(link_selector).on('ls.link_selected', function(e, data){
            $()
        });
    </script>
@append
