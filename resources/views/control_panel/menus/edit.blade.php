@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($item, ['route' => ['control_panel.menus.update', $item->id],
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.menus._form')

    {{ Form::close() }}
@stop