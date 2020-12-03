@extends('control_panel.layouts.form')

@section('content')
    <div class="cp__form" id="CP__LoginForm">
        <div class="cp__form_icon">
            <i class="fa fa-user" aria-hidden="true"></i>
        </div>

        <div class="cp__locale_selector text-left">
            @foreach(\Config::get('control_panel.locales') as $locale)
                @if(app()->getLocale() != $locale)
                    <a class="locale_icon btn btn-primary" href="{{ changeCPLocaleUrl($locale) }}">{{ localeIcon($locale) }}</a>
                @else
                    <a class="locale_icon btn btn-primary disabled">{{ localeIcon($locale) }}</a>
                @endif
            @endforeach
        </div>

        <h2 class="cp__form_title">{{ trans('labels.control_panel') }}</h2>

        @include('control_panel.common._flash_message')

        <div class="cp__form_fields">
            {{ Form::open(['route' => 'control_panel.do_login']) }}

                <div class="form-group">
                    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('labels.username_or_email')]) }}
                </div>

                <div class="form-group">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('labels.password')]) }}
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember_me" value="1"
                                {{ old('remember_me') == 1 ? 'checked' : '' }}>{{ trans('labels.remember_me') }}
                    </label>
                </div>

                <div class="from-group">
                    <button type="submit" class="btn btn-primary">{{ trans('actions.login') }}</button>
                </div>

            {{ Form::close() }}
        </div>

        <a href="{{ route('control_panel.show_remind') }}">{{ trans('labels.forget_password') }}</a>

        <hr>

        <p class="cp__form_footer">{!! trans('messages.vadecom_copyrights') !!}</p>
    </div>
@stop