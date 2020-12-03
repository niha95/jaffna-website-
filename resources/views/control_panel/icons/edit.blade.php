@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($item, ['route' => ['control_panel.icons.update', $item->id],
        'class' => 'form-horizontal',
        'method' => 'patch', 'files' => true]) }}

        @include('control_panel.icons._form')

    {{ Form::close() }}
@stop