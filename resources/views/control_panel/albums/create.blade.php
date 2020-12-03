@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.albums.store', 'class' => 'form-horizontal']) }}

        @include('control_panel.albums._form')

    {{ Form::close() }}
@stop