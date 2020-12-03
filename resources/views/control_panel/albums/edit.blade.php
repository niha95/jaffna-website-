@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($album, ['route' => ['control_panel.albums.update', $album->id],
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.albums._form')

    {{ Form::close() }}
@stop