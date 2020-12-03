@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.contacts') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$contacts->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.email_address') }}</th>
                            <th>{{ trans('labels.newsletter') }}</th>
                            <th></th>
                        </tr>

                        <? $i = 1 ?>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $contact->email_address }}</td>
                                <td>
                                    @if($contact->newsletter == 1)
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    @else
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    @endif
                                </td>
                                <td nowrap>
                                    <a href="javascript: void(0)" data-url="{{ route('control_panel.contacts.destroy', $contact->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $contacts->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.contacts')]) }}</p>
            @endif
        </div>
    </div>
@stop