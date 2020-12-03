@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('labels.messages') }}</h3>
        </div>
        <div class="panel-body">
            @if(!$reservations->isEmpty())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.name') }}</th>
                            <th>{{ trans('labels.phone_number') }}</th>
                            <th>{{ trans('labels.email') }}</th>
                            <th>Product name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>date</th>
                            <th> Time </th>
                            <th> Place</th>
                        </tr>

                        <? $i = 1 ?>
                        @foreach($reservations as $reservation)
                            <tr>
                                <td>{{ $i++ }}</td>
                                
                               
                                <td>{{ $reservation->name }}</td>
                                <td>{{ $reservation->phone_number }}</td>
                                <td>{{ $reservation->email }}</td>
                                <td>{{ $reservation->Product_name }}</td>
                                <td>{{ $reservation->Price }}</td>
                                <td>{{ $reservation->Quantity }}</td>
                                <td>{{ $reservation->date }}</td>
                                <td>{{ $reservation->hours }}</td>
                                <td>{{ $reservation->place}}</td>
                                <td nowrap>
                                    <a href="{{ route('control_panel.flight_reservations.show', $reservation->id) }}" class="btn btn-primary">
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript: void(0)" data-url="{{ route('control_panel.flight_reservations.destroy', $reservation->id) }}"
                                       class="btn btn-danger" onclick="confirmDeleteEntity(this)"
                                    >
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                {{ $reservations->links() }}
            @else
                <p>{{ trans('messages.no_entities', ['entities' => trans('labels.flight_reservations')]) }}</p>
            @endif
        </div>
    </div>
@stop