@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($data, ['route' => 'control_panel.misc.edit_social_links', 'class' => 'form-horizontal', 'method' => 'patch']) }}

        <div class="row">
            <div class="col-sm-8">
                <div class="section">
                    <div class="form-group">
                        {{ Form::label('Facebook', trans('labels.facebook'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('facebook', null,
                                ['class' => 'form-control', 'id' => 'Facebook', 'placeholder' => trans('labels.facebook')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Twitter', trans('labels.twitter'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('twitter', null,
                                ['class' => 'form-control', 'id' => 'Twitter', 'placeholder' => trans('labels.twitter')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Youtube', trans('labels.youtube'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('youtube', null,
                                ['class' => 'form-control', 'id' => 'Youtube', 'placeholder' => trans('labels.youtube')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('GooglePlus', trans('labels.google_plus'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('google_plus', null,
                                ['class' => 'form-control', 'id' => 'GooglePlus', 'placeholder' => trans('labels.google_plus')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('Instagram', trans('labels.instagram'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('instagram', null,
                                ['class' => 'form-control', 'id' => 'Instagram', 'placeholder' => trans('labels.instagram')]) }}
                        </div>
                    </div>

                    <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('LinkedIn', trans('labels.linked_in'), ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('linked_in', null,
                                ['class' => 'form-control', 'id' => 'LinkedIn', 'placeholder' => trans('labels.linked_in')]) }}
                        </div>
                    </div>
                    
                      <hr class="input_separator">

                    <div class="form-group">
                        {{ Form::label('snapchat', 'snapchat', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('snapchat', null,
                                ['class' => 'form-control', 'id' => 'snapchat', 'placeholder' => 'snapchat']) }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="reset" class="btn btn-danger">{{ trans('actions.reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('actions.save') }}</button>
                    </div>
                </div>
            </div>
        </div>

    {{ Form::close() }}
@stop