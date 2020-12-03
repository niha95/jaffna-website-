<?php
namespace App\Http\Controllers\Site;

use App\Models\City;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsController extends SiteController
{
    public function __construct()
    {
        view()->composer('site.products._form', function ($view){
            $categories = [];

            // $records = $guard->user()->categories;
            $records = ProductCategory::where('parent_id',0)->get();
            foreach ($records as $record) {
                $categories[$record->id] = $record->name_locale;
            }

            $view->with('categories', $categories);
        });

        parent::__construct();
    }
    
    public function index($slug)
    {
        if(!$slug)
            return back();

        $products = Product::with('category', 'images')
        ;
        
        //if(\Request::has('city') && \Request::get('city') > 0)
           // $products->where('city_id', \Request::get('city'));

        $products = $products->paginate(8);

        $viewPath = 'site.products.packages';
        if(in_array($slug, ['tours', 'shore-excursions', 'compined-tours', 'daily-tours', 'cairo', 'luxor']))
            $viewPath = 'site.products.tours';
        elseif($slug == 'nile-cruises')
            $viewPath = 'site.products.nile-cruises';
        elseif($slug == 'hotels')
            $viewPath = 'site.products.hotels';

        //$toursCities = City::has('products')->get();

        return view($viewPath, [
            'products'      => $products,
            'title'         => ProductCategory::whereSlug($slug)->first()->name_locale,
            //'toursCities'   => $toursCities
        ]);
    }

    public function show($slug)
    {
        $product = Product::findBySlugOrFail($slug)->load('image');
        return view('site.products.product', ['product' => $product]);
    }

    public function category($slug)
    {
        $category = ProductCategory::with('featuredImage')->whereSlug($slug)->first();

        return view('site.products.category', ['category' => $category]);
    }
}