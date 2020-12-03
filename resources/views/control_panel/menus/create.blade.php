@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.menus.store', 'class' => 'form-horizontal']) }}

        @include('control_panel.menus._form')

    {{ Form::close() }}
@stop