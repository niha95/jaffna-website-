<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\VisitorMessage;
use Illuminate\Http\Request;

class VisitorsMessagesController extends ControlPanelController {

    protected $views_path = 'control_panel.visitors_messages';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('contacts');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.visitors_messages'),
            'fa fa-list',
            route('control_panel.visitors_messages.index')
        );
    }

    public function index()
    {
        $messages = VisitorMessage::latest()->paginate($this->perPage);

        return view($this->viewPath('index'), [
            'messages' => $messages,
        ]);
    }

    public function show($id)
    {
        $message = VisitorMessage::findOrFail($id);

        $message->read();

        $this->addBreadcrumb(
            trans('actions.read_message', ['subject' => $message->subject]),
            'fa fa-envelope-o'
        );

        return view($this->viewPath('show'), [
            'message' => $message
        ]);
    }

    public function destroy($id)
    {
        $message = VisitorMessage::findOrFail($id);

        if($message->delete()) {
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