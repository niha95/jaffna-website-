@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.users') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$users->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.name') }}</th>
                            <th>{{ trans('labels.email') }}</th>
                            <th>{{ trans('labels.role') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th></th>
                        </tr>

                        @foreach($users as $i => $user)
                            @if($user->id == 7 && auth()->id() != 7) @continue @endif
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->getRole() }}</td>
                                <td>{!! $user->renderStatus() !!}</td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.users.edit', $user->id) }}" class="btn btn-primary">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                    @if(auth()->id() != $user->id)
                                        <a href="javascript: void(0)"
                                           data-url="{{ route('control_panel.users.destroy', $user->id) }}"
                                           class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                        >
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $users->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.users')]) }}</p>
            @endif
        </div>
    </div>
@stop