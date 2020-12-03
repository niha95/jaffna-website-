<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\FlightReservation;
use Illuminate\Http\Request;

class FlightReservationController extends ControlPanelController {

    protected $views_path = 'control_panel.flight_reservations';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('reservations');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.flight_reservations'),
            'fa fa-list',
            route('control_panel.flight_reservations.index')
        );
    }

    public function index()
    {
        $reservations = FlightReservation::latest()->paginate($this->perPage);

        return view($this->viewPath('index'), [
            'reservations' => $reservations,
        ]);
    }

    public function show($id)
    {
        $reservation = FlightReservation::findOrFail($id);

        return view($this->viewPath('show'), [
            'reservation' => $reservation
        ]);
    }

    public function destroy($id)
    {
        $reservation = FlightReservation::findOrFail($id);

        if($reservation->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.message')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }
    
    
}