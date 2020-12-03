<?php
namespace App\Http\Controllers\ControlPanel;

use App\Http\Requests\MenuRequest;
use App\Models\Activity;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenusController extends ControlPanelController {

    protected $views_path = 'control_panel.menus';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('menus');

        $this->composeForm();

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.menus'),
            'fa fa-list',
            route('control_panel.menus.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.menus.create'),
            'fa fa-plus'
        );

        $items = MenuItem::parents()->with([
            'subMenus' => function($query){
                return $query->orderBy('order', 'asc');
            }
        ])->orderBy('order', 'asc')->get();

        return view($this->viewPath('index'), [
            'items' => $items,
            'positions_count' => MenuItem::positionsCount()
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.menu_item')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'), [
            'parents' => MenuItem::listParents()
        ]);
    }

    public function store(MenuRequest $request)
    {
        $input = $this->getInput();

        $this->validate($request, MenuItem::getRules());

        $input['order'] = MenuItem::where('parent_id', $input['parent_id'])->count() + 1;

        $item = new MenuItem($input);

        if ($item->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.item')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.menus.edit', [$item->id])
                : redirect()->route('control_panel.menus.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.menu_item')]),
            'fa fa-pencil'
        );

        $item = MenuItem::findOrFail($id);

        return view($this->viewPath('edit'), [
            'item' => $item,
            'parents' => MenuItem::listParents($item->id),
        ]);
    }

    public function update($id, MenuRequest $request)
    {
        $item = MenuItem::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, MenuItem::getRules());

        $item->fill($input);

        if ($item->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.item')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.menus.edit', [$item->id])
                : redirect()->route('control_panel.menus.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $item = MenuItem::findOrFail($id);

        if ($item->delete()) {
            Activity::log([
                'message' => 'deleted_entity',
                'entity' => 'labels.menu'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.item')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    public function moveUp($id)
    {
        $item = MenuItem::findOrFail($id);

        if($item->moveUp()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.item')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function moveDown($id)
    {
        $item = MenuItem::findOrFail($id);

        if($item->moveDown()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.item')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function getInput()
    {
        foreach (config('site.locales') as $locale) {
            $input['title_' . $locale] = request()->get('title_' . $locale);
            $input['status_' . $locale] = request()->get('status_' . $locale);
        }

        $input['link'] = request()->get('link');
        $input['position'] = request()->get('position');
        $input['parent_id'] = request()->get('parent_id');
        $input['icon'] = request()->get('icon');

        return $input;
    }

    private function composeForm()
    {
        view()->composer($this->viewPath('_form'), function ($view) {
            $positions = MenuItem::availablePositions();

            $view->with([
                'positions' => $positions,
            ]);
        });
    }
}