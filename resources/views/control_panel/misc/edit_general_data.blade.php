@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($data, ['route' => 'control_panel.misc.edit_general_data', 'class' => 'form-horizontal', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-sm-12">
                @foreach(siteLocales() as $locale)
                    <div class="section">
                        @if(count(siteLocales()) > 1)
                            <h2 class="section_title">{{ localizedTitle($locale) }}</h2>
                        @endif
                        <div class="form-group">
                            {{ Form::label('SiteName_' . $locale, trans('labels.site_name'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('site_name_' . $locale, null,
                                    ['class' => 'form-control', 'id' => 'SiteName_' . $locale, 'placeholder' => trans('labels.site_name')]) }}
                            </div>
                        </div>

                        <hr class="input_separator">

                        <div class="form-group">
                            {{ Form::label('SiteWordTitle_' . $locale, trans('labels.site_word_title'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('site_word_title_' . $locale, null,
                                    ['class' => 'form-control', 'id' => 'SiteWordTitle_' . $locale, 'placeholder' => trans('labels.site_word_title')]) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('SiteWordContent_' . $locale, trans('labels.site_word_content'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::textarea('site_word_content_' . $locale, null,
                                    ['class' => 'form-control ', 'id' => 'SiteWordContent_' . $locale, 'placeholder' => trans('labels.site_word_content')]) }}
                            </div>
                        </div>

                        <hr class="input_separator">

                        <div class="form-group">
                            {{ Form::label('MetaKeywords_' . $locale, trans('labels.meta_keywords'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('meta_keywords_' . $locale, null,
                                    ['class' => 'form-control', 'id' => 'MetaKeywords_' . $locale, 'placeholder' => trans('labels.meta_keywords')]) }}
                            </div>
                        </div>

                        <hr class="input_separator">

                        <div class="form-group">
                            {{ Form::label('MetaDescription_' . $locale, trans('labels.meta_description'), ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('meta_description_' . $locale, null,
                                    ['class' => 'form-control', 'id' => 'MetaDescription_' . $locale, 'placeholder' => trans('labels.meta_description')]) }}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

    {{ Form::close() }}

    @include('control_panel.common._images_manager')
@stop

@include('control_panel.common._ckeditor', ['images_manager' => 1])