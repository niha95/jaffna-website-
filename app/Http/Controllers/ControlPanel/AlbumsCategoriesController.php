<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\AlbumCategory;
use Illuminate\Http\Request;

class AlbumsCategoriesController extends ControlPanelController {

    protected $views_path = 'control_panel.albums_categories';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('albums');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.albums'),
            'fa fa-list',
            route('control_panel.albums.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.albums_categories.create'),
            'fa fa-plus'
        );

        $this->addBreadcrumb(
            trans('actions.list_entities', ['entities' => trans('labels.categories')]),
            'fa fa-list'
        );

        $categories = AlbumCategory::with('albumsCountRelation')->latest()->paginate($this->perPage);

        return view($this->viewPath('index'), [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.category')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(Request $request)
    {
        $input = $this->getInput();

        $this->validate($request, AlbumCategory::getRules());

        $category = new AlbumCategory($input);

        if ($category->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.albums_categories.edit', [$category->id])
                : redirect()->route('control_panel.albums_categories.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.category')]),
            'fa fa-pencil'
        );

        $category = AlbumCategory::findOrFail($id)->load('albums.latestImages');

        return view($this->viewPath('edit'), [
            'category' => $category,
        ]);
    }

    public function update($id, Request $request)
    {
        $category = AlbumCategory::findOrFail($id);

        $input = $this->getInput();

        $this->validate($request, AlbumCategory::getRules());

        $category->fill($input);

        if ($category->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.albums_categories.edit', [$category->id])
                : redirect()->route('control_panel.albums_categories.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = AlbumCategory::findOrFail($id);

        if($category->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.category')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    protected function getInput()
    {
        foreach (config('site.locales') as $locale) {
            $input['name_' . $locale] = request()->get('name_' . $locale);
        }

        return $input;
    }
}