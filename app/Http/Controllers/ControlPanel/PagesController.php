<?php
namespace App\Http\Controllers\ControlPanel;

use App\Blackburn\Filters\PageFilters;
use App\Http\Requests\PageRequest;
use App\Models\PageCategory;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Image;
use JavaScript;
use DB;

class PagesController extends ControlPanelController {

    protected $views_path = 'control_panel.pages';

    public function __construct(Request $request)
    {
        $this->setCurrentPanel('pages');

        $this->composeForm();

        parent::__construct($request);

        $this->addBreadcrumb(
            trans('labels.pages'),
            'fa fa-list',
            route('control_panel.pages.index')
        );
    }

    public function index(PageFilters $filters)
    {
        
        
                
        $this->addBreadcrumbButton(
            trans('actions.add'),
            route('control_panel.pages.create'),
            'fa fa-plus'
        );

        $pages = Page::with('featuredImage', 'category')
            ->filter($filters)
            ->orderBy('updated_at', 'desc')
            ->paginate($this->perPage);

        $pages->appends($filters->queryString());

        return view('control_panel.pages.index', [
            'pages' => $pages,
            'filters_as_string' => $filters->toString(),
            'categories' => PageCategory::mappedList(false),
            'orders' => Page::availableOrders()
        ]);
    }

    public function create()
    {
        $this->addBreadcrumb(
            trans('actions.create_entity', ['entity' => trans('labels.page')]),
            'fa fa-plus'
        );

        return view($this->viewPath('create'));
    }

    public function store(PageRequest $request)
    {
        $input = $this->getInput();    
        
        $page = new Page($input);

        if(!empty($request['page_images'])){
             
            $inserted_img_ids = array();
            foreach($request['page_images'] as $img){
                if($img) 
                $inserted_img_ids[] = $page->addPageImages($img);                              
            }
            $inserted_img_ids = implode(',',$inserted_img_ids);
            $input['images'] = $inserted_img_ids;
            $page->images= $inserted_img_ids;
        }
                    
        
        if ($input['featured_image']) $page->addImage($input['featured_image']);
        if ($input['icon_image']) $page->addIconImage($input['icon_image']);

        if ($page->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.page')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.pages.edit', [$page->id])
                : redirect()->route('control_panel.pages.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function edit($id)
    {
        $this->addBreadcrumb(
            trans('actions.edit_entity', ['entity' => trans('labels.page')]),
            'fa fa-pencil'
        );

        $page = Page::findOrFail($id);

        $path = $page->getCategoriesPath();
       
        
        //DB::enableQueryLog();       
        if(!empty($page->images)){
            $images = Image::whereRaw(' id IN  ('.$page->images.') ')->get()->toArray();
            $page->images_paths = $images;            
        }                        
    
        //dd(DB::getQueryLog());                        
    
        return view($this->viewPath('edit'), ['page' => $page, 'path' => $path]);
    }

    public function update(PageRequest $request, $id)
    {
        $page = Page::findOrFail($id);

        $input = $this->getInput();
        
        
                
        
        
       
        if(!empty($request['page_images'])){
            $inserted_img_ids = array();
            
            $inserted_img_ids = explode(',',$page->images);             
                                             
           
            foreach($request['page_images'] as $img){
                if($img) 
                $inserted_img_ids[] = $page->addPageImages($img);                              
            }
            $inserted_img_ids = implode(',',$inserted_img_ids);
            $input['images'] = $inserted_img_ids;
        }                                                   
        

        $this->validate($request, Page::getRules($input, $page));

        $page->fill($input);
  
        if ($input['featured_image']) $page->addImage($input['featured_image']);
        if ($input['icon_image']) $page->addIconImage($input['icon_image']);

        if ($page->save()) {
            flashMessage(trans('messages.entity_edited', ['entity' => trans('labels.page')]));

            return $request->exists('save_and_continue')
                ? redirect()->route('control_panel.pages.edit', [$page->id])
                : redirect()->route('control_panel.pages.index');
        }

        flashMessage(trans('messages.request_failed'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.entity_deleted', ['entity' => trans('labels.page')])
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => trans('messages.request_failed')
        ]);
    }

    private function getInput()
    {
        $input = [];

        foreach (siteLocales() as $locale) {
            $input['title_' . $locale] = request('title_' . $locale);
            $input['meta_keywords_' . $locale] = request('meta_keywords_' . $locale);
            $input['meta_description_' . $locale] = request('meta_description_' . $locale);
            $input['status_' . $locale] = request('status_' . $locale);
        }

        $input['slug'] = request('slug');
        $input['page_category_id'] = request('page_category_id');
        $input['content'] = request('content');
        $input['details'] = request('details');

        $input['featured_image'] = request()->file('featured_image');
        $input['icon_image'] = request()->file('icon_image');

        return $input;
    }

    public function getLayouts()
    {
        $layouts = [];

        foreach (config('pages.layouts') as $k => $v) {
            $layout = [
                'slug' => $k,
                'positions' => $v::availablePositions(),
                'thumbnail' => $v::thumbnail(),
            ];

            $layouts[] = $layout;
        }

        return $layouts;
    }

    public function getModulesList()
    {
        $modules = [];

        foreach (config('pages.modules') as $k => $m) {
            $modules[$k] = [
                'name' => $m::name(),
                'url' => $m::editingUrl()
            ];
        }

        return $modules;
    }

    public function module($module)
    {
        $class = config('pages.modules.' . $module);

        $module = new $class();

        $response = [
            'title' => $module->getTitle(),
            'content' => $module->getEditingView()
        ];

        return response()->json($response);
    }

    public function editModule(Request $request)
    {
        $module = $request->get('module');
        $module_index = $request->get('module_index');

        $class = config('pages.modules.' . $module['type']);

        $module = new $class($module['data'], $module_index);

        $response = [
            'title' => $module->getTitle(),
            'content' => $module->getEditingView()
        ];

        return response()->json($response);
    }

    public function getCategoriesPath()
    {
        $category_id = request('category_id');

        $path = '';

        $node = PageCategory::find($category_id);
        if ($node == null) return response()->json(['content' => $path]);

        $ancestors = $node->getAncestors();

        foreach ($ancestors as $i) {
            $path .= $i->slug . '/';
        }

        $path .= $node->slug . '/';

        return response()->json([
            'content' => $path
        ]);
    }

    private function composeForm()
    {
        view()->composer($this->viewPath('_form'), function ($view) {
            JavaScript::put([
                'urls' => [
                    'edit_module' => route('control_panel.pages.edit_module'),
                    'get_categories_path' => route('control_panel.pages.get_categories_path'),
                ],

                'token' => csrf_token(),

                'loading_icon' => asset('assets/control-panel/images/loading.gif')
            ]);

            $view->with([
                'categories' => PageCategory::mappedList(),
                'layouts' => $this->getLayouts(),
                'modules_list' => $this->getModulesList()
            ]);
        });
    }
    
    function delete_image(){
        $id = request('id');
        $page_id = request('page_id');
        
        if($page_id && $id){
            $page = Page::findOrFail($page_id);                                    
            
            $page_images = explode(',',$page->images);             
            $key = array_search($id, $page_images);
            unset($page_images[$key]);
            $input['images'] = implode(',',$page_images);
            $page->fill($input);
            if($page->save()){
                echo 'saved';
                //233,234,235,236
            }
        
        }
                
    }

}