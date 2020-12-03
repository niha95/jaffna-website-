<?php
namespace App\Http\Controllers\ControlPanel;

use App\Blackburn\Filters\ProductFilters;
use App\Models\Product;
use App\Models\ProductAlbum;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductsController extends ControlPanelController {

    protected $views_path = 'control_panel.products';

    protected $currentPanel = 'products';

    protected $title = '';

    public function __construct(Request $request)
    {
        
        $this->setCurrentPanel('products');
        parent::__construct($request);
        view()->composer($this->viewPath('_form'), function($view){
            $categories = [];
            foreach (ProductCategory::get() as $category) {
                $categories[$category->id] = $category->name_translated_piped;
            }
            return $view->with([
                'categories' => $categories,
                
            ]);
        });
    }

    public function packages()
    {
        $this->addBreadcrumb(trans('labels.packages'), 'fa fa-list', route('control_panel.packages.index'));
        $this->addBreadcrumbButton(trans('actions.add'), route('control_panel.packages.create'), 'fa fa-plus');
        $this->title = trans('labels.packages');
        return $this->index([1,5,6,7,17]);
    }

    public function tours()
    {
        $this->addBreadcrumb(trans('labels.tours'), 'fa fa-list', route('control_panel.tours.index'));
        $this->addBreadcrumbButton(trans('actions.add'), route('control_panel.tours.create'), 'fa fa-plus');
        $this->title = trans('labels.tours');
        return $this->index([2,8,9,10]);
    }

    public function nileCruises()
    {
        $this->addBreadcrumb(trans('labels.nile-cruises'), 'fa fa-list', route('control_panel.nile-cruises.index'));
        $this->addBreadcrumbButton(trans('actions.add'), route('control_panel.nile-cruises.create'), 'fa fa-plus');
        $this->title = trans('labels.nile-cruises');
        return $this->index([3]);
    }

    public function hotels()
    {
        $this->addBreadcrumb(trans('labels.hotels'), 'fa fa-list', route('control_panel.hotels.index'));
        $this->addBreadcrumbButton(trans('actions.add'), route('control_panel.hotels.create'), 'fa fa-plus');
        $this->title = trans('labels.hotels');
        return $this->index([4]);
    }

    public function index($catID = null)
    {
        $products = Product::with('image')->latest()->orderBy('updated_at', 'desc');
        
            $this->title = trans('labels.products');
        $products = $products->paginate($this->perPage);
        return view($this->viewPath('index'), [
            'title' => $this->title,
            'products' => $products,
            
            
        ]);
    }

    public function createPackage()
    {
        $this->addBreadcrumb(trans('labels.packages'), 'fa fa-list', route('control_panel.packages.index'));
        $this->addBreadcrumb(trans('actions.create_entity', ['entity' => trans('labels.package')]), 'fa fa-plus');
        $this->title = trans('actions.create_entity', ['entity' => trans('labels.package')]);
        return $this->create(1);
    }

    public function createTour()
    {
        $this->addBreadcrumb(trans('labels.tours'), 'fa fa-list', route('control_panel.tours.index'));
        $this->addBreadcrumb(trans('actions.create_entity', ['entity' => trans('labels.tour')]), 'fa fa-plus');
        $this->title = trans('actions.create_entity', ['entity' => trans('labels.tour')]);
        return $this->create(2);
    }

    public function createNileCruise()
    {
        $this->addBreadcrumb(trans('labels.nile-cruises'), 'fa fa-list', route('control_panel.nile-cruises.index'));
        $this->addBreadcrumb(trans('actions.create_entity', ['entity' => trans('labels.nile-cruise')]), 'fa fa-plus');
        $this->title = trans('actions.create_entity', ['entity' => trans('labels.nile-cruise')]);
        return $this->create(3);
    }

    public function createHotel()
    {
        $this->addBreadcrumb(trans('labels.hotels'), 'fa fa-list', route('control_panel.hotels.index'));
        $this->addBreadcrumb(trans('actions.create_entity', ['entity' => trans('labels.hotel')]), 'fa fa-plus');
        $this->title = trans('actions.create_entity', ['entity' => trans('labels.hotel')]);
        return $this->create(4);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.product')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'),['sub_categories'=>[]]);
    }

    public function store(Request $request)
    {
       $input = $this->getInput($request);
        
        $input['slug'] = $input['slug'];
        
        $product = new Product($input);
        
         if ($input['image_product']) $product->addImage($input['image_product']);

        if ($product->save()) {
            if(!empty($input['images']) && is_array($input['images'])) $product->addImages($input['images']);
            

            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.product')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.products.edit', [$product->id])
                : redirect()->route('control_panel.products.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.product')]),
            'fa fa-pencil'
        );

        $product = Product::findOrFail($id);
       

        return view($this->viewPath('edit'), [
            'product' => $product,
            'sub_categories' => ProductCategory::where('parent_id',$product->product_category_id)->pluck('name_ar','id')->all(),
        ]);
    }
        
    public function update($id, Request $request)
    {
       $product = Product::findOrFail($id);

        $input = $this->getInput($request);
        $product->fill($input);
       
 if (isset($input['image_product'])) $product->addImage($input['image_product']);
        if ($product->save()) {
            if(!empty($input['images']) && is_array($input['images'])) $product->addImages($input['images']);
            

            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.product')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.products.edit', [$product->id])
                : redirect()->route('control_panel.products.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.product')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    private function getInput()
    {
        $input = request()->only('slug', 'status','price');

        foreach (siteLocales() as $locale) {
            $input['name_' . $locale] = request('name_' . $locale);
            $input['description_' . $locale] = request('description_' . $locale);
            $input['features_' . $locale] = request('features_' . $locale);
        }

        $input['status'] = request('status');
         
        $input['product_category_id'] = request('product_category_id');
         if(request()->hasFile('image_product'))
            $input['image_product'] = request()->file('image_product');
      

        return $input;
            
    }

 public function dropzoneUploader(Request $request)
    {
        $uploaded_image = $request->file('uploaded_image');
        $product_id = $request->get('product_id');

        $image = new ProductAlbum([
            'product_id' => product_id,
        ]);

        $options = [];
        if($request->has('height')) $options['height'] = $request->get('height');

        if($request->get('keep_aspect_ratio') == "true") {
            $options['aspectRatio'] = true;
        } elseif (($width = $request->get('width')) != 0 && !empty($width)) {
            $options['aspectRatio'] = false;
            $options['width'] = $width;
        }

        $image->upload($uploaded_image, $options)->save();
        $image->thumb_url = $image->thumbFullLink();
        $image->image_url = $image->imageFullLink();
        $image->image_url_relative = $image->imageFullLink(true);

        return response()->json([
            'status' => 'success',
            'image' => $image->toArray(),
        ]);
    }


}