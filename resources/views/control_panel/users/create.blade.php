@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.users.store', 'class' => 'form-horizontal']) }}

        @include('control_panel.users._form')

    {{ Form::close() }}
@stop