@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.pages_categories.store', 'class' => 'form-horizontal', 'id' => 'PageCategoryForm']) }}

        @include('control_panel.pages_categories._form')

    {{ Form::close() }}
@stop