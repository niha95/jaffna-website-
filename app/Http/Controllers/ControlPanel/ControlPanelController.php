<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\City;
use JavaScript;
use App\Blackburn\Composers\ControlPanel\SideMenuComposer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControlPanelController extends Controller {

    protected $perPage = 12;

    protected $views_path;

    protected $breadcrumb = [];
    protected $breadcrumbButtons = [];

    public function __construct(Request $request)
    {
         
        \DB::enableQueryLog();

        $this->perPage = $request->has('per_page') ? $request->get('per_page') : 12;

        $this->shareLocales();

        $this->composeImagesManager();
        $this->composeLinkSelector();
        $this->composeBreadcrumb();


        $this->addBreadcrumb(
            trans('labels.control_panel'),
            'fa fa-cogs',
            route('control_panel.home', [app()->getLocale()])
        );

        $this->getCitiesList();
    }

    protected function viewPath($view)
    {
        if (!empty($this->views_path)) {
            return $this->views_path . '.' . $view;
        }

        return $view;
    }

    protected function setCurrentPanel($panel)
    {
        config()->set('control_panel.current_panel', $panel);
    }

    public function search(Request $request)
    {
        $query = strtolower($request->get('query'));

        $search_results = [];

        $side_menu = (new SideMenuComposer())->sideMenu();
        foreach ($side_menu as $panel) {
            foreach ($panel['options'] as $option) {
                if (str_contains(strtolower($option['label']), $query)) {
                    $search_results[] = [
                        'data' => $option['link'],
                        'value' => $panel['title'] . ' | ' . $option['label'],
                    ];
                }
            }
        }

        return response()->json([
            'query' => $query,
            'suggestions' => $search_results,
        ]);
    }

    private function shareLocales()
    {
        JavaScript::put([
            'locales' => siteLocales(),
            'current_locale' => app()->getLocale(),
            'default_locale' => defaultLocale(),
        ]);
    }

    private function composeImagesManager()
    {
        view()->composer('control_panel.common._images_manager', function ($view) {
            $urls = [
                'fetch_categories' => route('control_panel.images_manager.fetch_categories'),
                'fetch_uncategorized_images' => route('control_panel.images_manager.fetch_uncategorized_images'),
                'fetch_albums' => route('control_panel.images_manager.fetch_albums'),
                'fetch_images' => route('control_panel.images_manager.fetch_images'),
                'dropzone_uploader' => route('control_panel.images.dropzone_uploader'),
                'store_category' => route('control_panel.images_manager.store_category'),
                'store_album' => route('control_panel.images_manager.store_album'),
                'update_image' => route('control_panel.images_manager.update_image'),
                'delete_image' => route('control_panel.images_manager.delete_image'),
            ];

            $translations = [
                'all_categories' => trans('labels.all_categories'),
                'header' => trans('labels.images_manager')
            ];

            $view->with([
                'urls' => json_encode($urls),
                'translations' => json_encode($translations),
                'token' => csrf_token(),
            ]);
        });
    }

    private function composeLinkSelector()
    {
        view()->composer('control_panel.common._link_selector', function ($view) {
            $urls = [
                'fetch_categories_and_pages' => route('control_panel.link_selector.fetch_categories_and_pages')
            ];

            $translations = [
                'home' => trans('labels.home'),
                'no_categories' => trans('messages.no_entities', ['entities' => trans('labels.categories')]),
                'no_pages' => trans('messages.no_entities', ['entities' => trans('labels.pages')]),
            ];

            $misc = miscLinks();

            $types = [];
            foreach (config('site.link_types') as $type) {
                $types[] = [
                    'slug' => $type,
                    'title' => trans('labels.' . $type)
                ];
            };

            $view->with([
                'urls' => json_encode($urls),
                'translations' => json_encode($translations),
                'misc' => json_encode($misc),
                'types' => json_encode($types),
            ]);
        });
    }

    protected function addBreadcrumb($label, $icon = '', $link = '')
    {
        $this->breadcrumb[] = [
            'label' => $label,
            'link' => $link,
            'icon' => $icon,
        ];
    }

    protected function addBreadcrumbButton($label, $link, $icon = '', $type = 'primary')
    {
        $this->breadcrumbButtons[] = [
            'label' => $label,
            'link' => $link,
            'type' => $type,
            'icon' => $icon
        ];
    }

    private function composeBreadcrumb()
    {
        view()->composer('control_panel.common._breadcrumb', function ($view) {
            $view->with([
                'breadcrumb' => $this->breadcrumb,
                'breadcrumbButtons' => $this->breadcrumbButtons
            ]);
        });
    }

    protected function getCitiesList()
    {
       
//        view()->share('citiesList', ['0' => trans('labels.select-city')]+City::pluck('name_'.app()->getLocale(), 'id')->all());
    }

}