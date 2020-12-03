@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.slides.store', 'files' => true, 'class' => 'form-horizontal']) }}

        @include('control_panel.slides._form')

    {{ Form::close() }}
@stop