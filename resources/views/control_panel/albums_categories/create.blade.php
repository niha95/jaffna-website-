@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.albums_categories.store', 'class' => 'form-horizontal']) }}

        @include('control_panel.albums_categories._form')

    {{ Form::close() }}
@stop