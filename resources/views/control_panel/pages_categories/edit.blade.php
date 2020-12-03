@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($category, ['route' => ['control_panel.pages_categories.update', $category->id],
        'class' => 'form-horizontal', 'id' => 'PageCategoryForm', 'method' => 'patch']) }}

        @include('control_panel.pages_categories._form')

    {{ Form::close() }}
@stop