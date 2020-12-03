<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends ControlPanelController {

    protected $views_path = 'control_panel.users';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('users');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.users'),
            'fa fa-list',
            route('control_panel.users.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.users.create'),
            'fa fa-plus'
        );

        $users = User::paginate($this->perPage);

        return view($this->viewPath('index'), [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.user')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(Request $request)
    {
        $input = $this->getInput();

        $this->validate($request, User::getRules($input));

        $user = new User($input);

        if ($user->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.user')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.users.edit', [$user->id])
                : redirect()->route('control_panel.users.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.user')]),
            'fa fa-pencil'
        );

        $user = User::findOrFail($id);
        $activities = $user->activities()->paginate($this->perPage);

        return view($this->viewPath('edit'), [
            'user' => $user,
            'activities' => $activities,
        ]);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, User::getRules($input, $user));

        if(empty($input['password'])) unset($input['password']);

        $user->fill($input);

        if ($user->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.user')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.users.edit', [$user->id])
                : redirect()->route('control_panel.users.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->id == auth()->id()) {
            return response()->json([
                'status' => 'failed',
                'message' => trans('messages.cannot_delete_current_user')
            ]);
        }

        if($user->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.user')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    protected function getInput()
    {
        $input = [
            'first_name' => request()->get('first_name'),
            'last_name' => request()->get('last_name'),
            'email' => request()->get('email'),
            'password' => request()->get('password'),
            'password_confirmation' => request()->get('password_confirmation'),
            'status' => request()->get('status'),
            'role' => request()->get('role'),
        ];

        return $input;
    }
}