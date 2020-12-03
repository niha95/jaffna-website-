@extends('control_panel.layouts.default')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $reservation->subject }}</h3>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered no_center">
                     <tr>
                        <th>{{ trans('labels.name') }}</th>
                        <td>{{ $reservation->name }}</td>
                    </tr>

                    @if(!empty($reservation->phone_number))
                        <tr>
                            <th>{{ trans('labels.phone_number') }}</th>
                            <td>{{ $reservation->phone_number }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->email))
                        <tr>
                            <th>{{ trans('labels.email') }}</th>
                            <td>{{ $reservation->email }}</td>
                        </tr>
                    @endif
                    @if(!empty($reservation->from))
                    <tr>
                        <th>{{ trans('labels.from') }}</th>
                        <td>{{ $reservation->from }}</td>
                    </tr>
                     @endif
                    @if(!empty($reservation->to))
                        <tr>
                            <th>{{ trans('labels.to') }}</th>
                            <td>{{ $reservation->to }}</td>
                        </tr>form
                    @endif

                    @if(!empty($reservation->check_in))
                        <tr>
                            <th>{{ trans('labels.check_in_date') }}</th>
                            <td>{{ $reservation->check_in }}</td>
                        </tr>
                    @endif

                   @if(!empty($reservation->check_out))
                        <tr>
                            <th>{{ trans('labels.check_out_date') }}</th>
                            <td>{{ $reservation->check_out }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->number_of_adults))
                        <tr>
                            <th>{{ trans('labels.number_of_adults') }}</th>
                            <td>{{ $reservation->number_of_adults }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->number_of_children))
                        <tr>
                            <th>{{ trans('labels.number_of_children') }}</th>
                            <td>{{ $reservation->number_of_children }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->number_of_rooms))
                        <tr>
                            <th>{{ trans('labels.number_of_rooms') }}</th>
                            <td>{{ $reservation->number_of_rooms }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->class))
                        <tr>
                            <th>{{ trans('labels.class') }}</th>
                            <td>{{ $reservation->class }}</td>
                        </tr>
                    @endif

                    @if(!empty($reservation->area))
                        <tr>
                            <th>{{ trans('labels.area') }}</th>
                            <td>{{ $reservation->area }}</td>
                        </tr>
                    @endif

                </table>
            </div>
        </div>
    </div>
@stop