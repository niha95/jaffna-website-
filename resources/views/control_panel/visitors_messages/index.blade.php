@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.messages') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$messages->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.sender_name') }}</th>
                            <th>{{ trans('labels.sender_email_address') }}</th>
                            <th>{{ trans('labels.subject') }}</th>
                            <th>{{ trans('labels.statuses.new') }}</th>
                            <th></th>
                        </tr>

                        @foreach($messages as $i => $message)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $message->sender_name }}</td>
                                <td>{{ $message->sender_email_address }}</td>
                                <td>{{ $message->subject }}</td>
                                <td>{!! $message->renderStatus() !!}</td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.visitors_messages.show', $message->id) }}" class="btn btn-primary">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)" data-url="{{ route('control_panel.visitors_messages.destroy', $message->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $messages->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.messages')]) }}</p>
            @endif
        </div>
    </div>
@stop