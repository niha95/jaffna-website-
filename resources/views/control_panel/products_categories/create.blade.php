@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.products_categories.store', 'files' => true, 'class' => 'form-horizontal']) }}

        @include('control_panel.products_categories._form')

    {{ Form::close() }}
@stop