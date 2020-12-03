<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductsCategoriesController extends ControlPanelController {

    protected $views_path = 'control_panel.products_categories';
    protected $currentPanel = 'products';

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.categories'),
            'fa fa-list',
            route('control_panel.products_categories.index')
        );
    }

    public function index()
    {
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.products_categories.create'),
            'fa fa-plus'
        );

        $categories = ProductCategory::paginate($this->perPage);

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
            'parents' => ProductCategory::where('parent_id',0)->pluck('name_ar','id')->all()
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->getInput($request);

        $category = new ProductCategory($input);
        if($input['image']) $category->addImage($input['image']);
       

        if ($category->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.products_categories.edit', [$category->id])
                : redirect()->route('control_panel.products_categories.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.category')]),
            'fa fa-pencil'
        );

        $category = ProductCategory::findOrFail($id);

        return view($this->viewPath('edit'), [
            'category' => $category,
            'parents' => ProductCategory::where('parent_id',0)->where('id','!=',$id)->pluck('name_ar','id')->all()
        ]);
    }

    public function update($id, Request $request)
    {
        $category = ProductCategory::findOrFail($id);

        $input = $this->getInput($request);

        $category->fill($input);
        if($input['image']) $category->addImage($input['image']);

        if ($category->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.category')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.products_categories.edit', [$category->id])
                : redirect()->route('control_panel.products_categories.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);

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

    protected function getInput($request = null)
    {
        if(is_null($request)) $request = request();

        $input = $request->only('status', 'slug','parent_id');

        foreach (config('site.locales') as $locale) {
            $input['name_' . $locale] = $request->get('name_' . $locale);
            $input['description_' . $locale] = $request->get('description_' . $locale);
        }

        $input['image'] = $request->file('image');

        return $input;
    }
    public function moveUp($id)
    {
        $categories = ProductCategory::findOrFail($id);

        if($categories->moveUp()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.item')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function moveDown($id)
    {
        $categories = ProductCategory::findOrFail($id);

        if($categories->moveDown()){
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.item')]));
            return redirect()->back();
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }
}