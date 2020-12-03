<?php
namespace App\Http\Controllers\ControlPanel;

use App\Http\Requests\MenuRequest;
use App\Models\Activity;
use App\Models\Icon;
use Illuminate\Http\Request;

class IconsController extends ControlPanelController {

    protected $views_path = 'control_panel.icons';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('icons');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.icons'),
            'fa fa-list',
            route('control_panel.icons.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.icons.create'),
            'fa fa-plus'
        );

        $items = Icon::orderBy('order', 'asc')->get();

        return view($this->viewPath('index'), ['items' => $items]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.icon')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(MenuRequest $request)
    {
        $input = $this->getInput();

        $this->validate($request, Icon::getRules());

        $item = new Icon($input);

        if ($input['icon']) $item->addIconImage($input['icon']);

        if ($item->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.icon')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.icons.edit', [$item->id])
                : redirect()->route('control_panel.icons.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.icon')]),
            'fa fa-pencil'
        );

        $item = Icon::findOrFail($id);

        return view($this->viewPath('edit'), ['item' => $item]);
    }

    public function update($id, MenuRequest $request)
    {
        $item = Icon::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, Icon::getRules());

        $item->fill($input);

        if ($request->hasFile('icon') && $input['icon'])
            $item->addIconImage($input['icon']);

        if ($item->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.icon')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.icons.edit', [$item->id])
                : redirect()->route('control_panel.icons.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $item = Icon::findOrFail($id);

        if ($item->delete()) {
            Activity::log([
                'message' => 'deleted_entity',
                'entity' => 'labels.icons'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.icon')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    public function moveUp($id)
    {
        $item = Icon::findOrFail($id);

        if($item->moveUp()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.icon')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function moveDown($id)
    {
        $item = Icon::findOrFail($id);

        if($item->moveDown()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.icon')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function getInput()
    {
        foreach (config('site.locales') as $locale) {
            $input['title_' . $locale] = request()->get('title_' . $locale);
            $input['excerpt_' . $locale] = request()->get('excerpt_' . $locale);
            $input['status_' . $locale] = request()->get('status_' . $locale);
        }

        $input['link'] = request()->get('link');
        $input['order'] = request()->get('order');
        if(request()->hasFile('icon'))
            $input['icon'] = request()->file('icon');

        return $input;
    }
}