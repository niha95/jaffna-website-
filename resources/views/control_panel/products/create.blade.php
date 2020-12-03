@extends('control_panel.layouts.default')

@section('content')
    {{ Form::open(['route' => 'control_panel.products.store', 'files' => true, 'class' => 'form-horizontal', 'id' => 'ProductForm']) }}

        @include('control_panel.products._form')

    {{ Form::close() }}
@stop