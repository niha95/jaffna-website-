@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $message->subject }}</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered no_center">
                    <tr>
                        <th>{{ trans('labels.sender_name') }}</th>
                        <td>{{ $message->sender_name }}</td>
                    </tr>

                    @if(!empty($message->sender_email_address))
                        <tr>
                            <th>{{ trans('labels.sender_email_address') }}</th>
                            <td>{{ $message->sender_email_address }}</td>
                        </tr>
                    @endif

                    @if(!empty($message->sender_phone_number))
                        <tr>
                            <th>{{ trans('labels.sender_phone_number') }}</th>
                            <td dir="ltr">{{ $message->sender_phone_number }}</td>
                        </tr>
                    @endif

                    @if(!empty($message->subject))
                        <tr>
                            <th>{{ trans('labels.subject') }}</th>
                            <td>{{ $message->subject }}</td>
                        </tr>
                    @endif

                    <tr>
                        <th>{{ trans('labels.message') }}</th>
                        <td>{{ $message->message }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@stop