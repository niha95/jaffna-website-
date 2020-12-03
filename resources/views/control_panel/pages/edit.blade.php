@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($page, ['route' => ['control_panel.pages.update', $page->id],
        'class' => 'form-horizontal', 'id' => 'PageForm', 'method' => 'patch', 'files' => true]) }}

        @include('control_panel.pages._form')

    {{ Form::close() }}
@stop