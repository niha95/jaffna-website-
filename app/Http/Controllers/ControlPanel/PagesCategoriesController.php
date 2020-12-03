<?php
namespace App\Http\Controllers\ControlPanel;

use App\Http\Requests\PageCategoryRequest;
use App\Models\PageCategory;
use Illuminate\Http\Request;

class PagesCategoriesController extends ControlPanelController {

    protected $views_path = 'control_panel.pages_categories';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('pages');

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.pages'),
            'fa fa-list',
            route('control_panel.pages.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumb(
            trans('actions.list_entities', ['entities' => trans('labels.categories')]),
            'fa fa-list'
        );

        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.pages_categories.create'),
            'fa fa-plus'
        );

        $categories = PageCategory::with('parent', 'pagesCountRelation')->latest()->paginate($this->perPage);

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

        return view($this->viewPath('create'), [
            'parents' => PageCategory::mappedList(),
            'layouts' => [
                '1' => 'Layout 1',
                '2' => 'Layout 2',
                '3' => 'Layout 3',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->getInput();

        // $this->validate($request, PageCategory::getRules($input));

        $category = new PageCategory($input);
        $category->parent_id = $input['parent_id'];

        if ($category->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.pages_categories.edit', [$category->id])
                : redirect()->route('control_panel.pages_categories.index');
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

        $category = PageCategory::findOrFail($id);

        return view($this->viewPath('edit'), [
            'category' => $category,
            'parents' => PageCategory::mappedList(),
            'layouts' => [
                '1' => 'Layout 1',
                '2' => 'Layout 2',
                '3' => 'Layout 3',
            ],
        ]);
    }

    public function update($id, Request $request)
    {
        $category = PageCategory::findOrFail($id);

        $input = $this->getInput();

        // $this->validate($request, PageCategory::getRules($input, $category));

        $category->fill($input);
        $category->parent_id = $input['parent_id'];

        if ($category->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.pages_categories.edit', [$category->id])
                : redirect()->route('control_panel.pages_categories.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = PageCategory::findOrFail($id);

     

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

    private function getInput()
    {
        $input = request()->only('slug', 'parent_id', 'layout');

        foreach (siteLocales() as $locale) {
            $input['name_' . $locale] = request('name_' . $locale);
            $input['content_' . $locale] = request('content_' . $locale);
        }
        $input['slug'] = request('slug_hidden');
        return $input;
    }

}