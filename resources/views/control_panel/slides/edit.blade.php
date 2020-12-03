@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($slide, ['route' => ['control_panel.slides.update', $slide->id],
        'files' => true,
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.slides._form')

    {{ Form::close() }}
@stop