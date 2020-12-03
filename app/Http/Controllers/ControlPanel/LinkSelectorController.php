<?php
namespace App\Http\Controllers\ControlPanel;

use App\Models\Page;
use App\Models\PageCategory;

class LinkSelectorController extends ControlPanelController {

    protected $path;

    public function fetchCategoriesAndPages()
    {
        $parent_id = request('parent_id');

        $this->path = $this->buildPath($parent_id);

        $categories = PageCategory::where('parent_id', $parent_id == 0 ? null : $parent_id)->get();
        $categories = $categories->map(function ($item) {
            return [
                'id' => $item->id,
                'name_translated_piped' => $item->name_translated_piped,
                'url' => route('site.category', [$this->path->uri . $item->slug], false)
            ];
        });

        $pages = $this->fetchPages($parent_id);

        return response()->json([
            'pages' => $pages->toArray(),
            'categories' => $categories->toArray(),
            'path' => $this->path->items
        ]);
    }

    private function fetchPages($category_id)
    {
        $builder = Page::select(['id', 'slug']);

        foreach (siteLocales() as $locale) {
            $builder->addSelect('title_' . $locale);
        }

        $pages = $builder->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'title_translated_piped' => $item->title_translated_piped,
                'url' => route('site.page', [$this->path->uri . $item->slug], false)
            ];
        });

        return $pages;
    }

    private function buildPath($category_id)
    {
        $path = new \stdClass();
        $path->uri = '';
        $path->items = [
            [
                'id' => 0,
                'name_translated_piped' => trans('labels.home')
            ]
        ];

        if ($category_id == 0) return $path;

        $category = PageCategory::findOrFail($category_id);
        $ancestors = $category->ancestors()->get();

        foreach ($ancestors as $ancestor) {
            $path->uri .= $ancestor->slug . '/';

            $path->items[] = [
                'id' => $ancestor->id,
                'url' => route('site.category', [$ancestor->slug], false),
                'name_translated_piped' => $ancestor->name_translated_piped
            ];
        }

        $path->uri .= $category->slug . '/';

        $path->items[] = [
            'id' => $category_id,
            'url' => route('site.category', [$category->slug], false),
            'name_translated_piped' => $category->name_translated_piped
        ];

        return $path;
    }

}