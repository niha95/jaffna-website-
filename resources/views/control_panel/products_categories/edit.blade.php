@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($category, ['route' => ['control_panel.products_categories.update', $category->id],
        'files' => true,
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.products_categories._form')

    {{ Form::close() }}
@stop