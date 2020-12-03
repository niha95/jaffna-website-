@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($category, ['route' => ['control_panel.albums_categories.update', $category->id],
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.albums_categories._form')

    {{ Form::close() }}
@stop