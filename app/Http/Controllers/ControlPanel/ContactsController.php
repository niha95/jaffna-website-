<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController extends ControlPanelController {

    protected $views_path = 'control_panel.contacts';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('contacts');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.contacts'),
            'fa fa-list',
            route('control_panel.contacts.index')
        );
    }

    public function index()
    {
        $contacts = Contact::latest()->paginate($this->perPage);

        return view($this->viewPath('index'), [
            'contacts' => $contacts,
        ]);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);

        if($contact->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.contact')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }
}