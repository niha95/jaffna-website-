<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Product;
use App\Models\MenuItem;
use App\Models\Misc;
use App\Models\PageCategory;
use App\Models\Page;
use App\Models\Slide;
use App\Models\Ad;
use Gloudemans\Shoppingcart\Cart;
use Carbon\Carbon;

class SiteController extends Controller {

    protected $misc, $banner;

    protected $navbar = null;

    public function __construct()
    {
        \DB::enableQueryLog();

        $this->shareMisc();
        $this->shareCurrentLocale();

        $this->composeNavbar();
        $this->composeCart();
        $this->setCurrentNav();
        $this->getBanner();
        $this->featuredPages();
        $this->composeFooter();
        
    }


private function featuredPages()
    {
        $featuredPages = Page::where('status_ar','featured')->limit(8)->get();

        view()->share('featuredPages', $featuredPages);
    }
    
    
    private function shareMisc()
    {
        $this->misc = Misc::first();

        view()->share('misc', $this->misc);
    }

    private function shareLocales()
    {
        \JavaScript::put([
            'locales' => siteLocales(),
            'current_locale' => app()->getLocale(),
            'default_locale' => defaultLocale(),
        ]);
    }

    private function shareCurrentLocale()
    {
        $locale = config('site.current_locale');
        view()->share([
            'current_locale' => $locale,
        ]);
    }

    protected function composeNavbar()
    {
        if(is_null($this->navbar)) {
            $this->navbar = MenuItem::with(['subMenus' => function($query){
                return $query->orderBy('order', 'asc');
            }])->orderBy('order', 'asc')->parents()->active(app()->getLocale())->get();
        }

        view()->composer('site.layouts.partials._navbar', function($view){
            $view->with([
                'navbar' => $this->navbar
            ]);
        });
    }
    
    protected function composeCart()
    {
        view()->composer(['site.layouts.partials._cart'], function ($view) {
            return $view->with('cart', \Cart::content());
        });
    }
    
    protected function setCurrentNav($url = null)
    {
        if(is_null($url)) $url = url()->current();

        config()->set('site.current_nav', $url);
    }

    protected function getBanner()
    {
        $this->banner = Slide::with('image')->active(app()->getLocale())->get();
        view()->share('banner', $this->banner);
    }
    
    protected function composeFooter()
    {
        view()->composer('site.layouts.partials._footer', function ($view) {
            $footerMenu = MenuItem::orderBy('order', 'asc')->parents()
                ->where('position','top,bottom')
                ->active(app()->getLocale())
                ->get();

            $view->with([
                'footerMenu' => $footerMenu,
            ]);
        });
    }

}