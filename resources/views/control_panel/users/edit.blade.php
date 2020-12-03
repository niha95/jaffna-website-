@extends('control_panel.layouts.default')

@section('content')
    {{ Form::model($user, ['route' => ['control_panel.users.update', $user->id],
        'files' => true,
        'class' => 'form-horizontal',
        'method' => 'patch']) }}

        @include('control_panel.users._form')

    {{ Form::close() }}
@stop