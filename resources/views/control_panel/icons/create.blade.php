@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.icons.store', 'class' => 'form-horizontal', 'files' => true]) }}

        @include('control_panel.icons._form')

    {{ Form::close() }}
@stop