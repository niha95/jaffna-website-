@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($product, ['route' => ['control_panel.products.update', $product->id],
        'class' => 'form-horizontal', 'id' => 'ProductForm', 'method' => 'patch', 'files' => true]) }}

        @include('control_panel.products._form')

    {{ Form::close() }}
@stop