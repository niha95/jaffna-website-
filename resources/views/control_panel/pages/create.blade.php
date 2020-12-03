@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.pages.store', 'files' => true, 'class' => 'form-horizontal', 'id' => 'PageForm']) }}

        @include('control_panel.pages._form')

    {{ Form::close() }}
@stop