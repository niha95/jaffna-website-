<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\PortfolioCategory;
use App\Models\PortfolioItem;

class PortfolioModule extends AbstractModule {

    public function renderView($locale)
    {
        $localized = $this->getLocalizedData();

        $parentCategory = PortfolioCategory::findOrFail($this->data->category_id)->load([
            'children' => function ($query) {
                if ($this->data->featured_only) $query->featured(app()->getLocale());

                $query->has('portfolioItems');
            }
        ]);

        if(request()->has('portfolio-category')) {
            $slug = request()->get('portfolio-category');

            $currentCategory = PortfolioCategory::where([
                'slug' => $slug,
                'parent_id' => $parentCategory->id
            ])->first();

            $portfolioItems = $currentCategory->portfolioItems()->with('image')->paginate(8);
            $portfolioItems->appends(['portfolio-category' => $currentCategory->slug]);
        } else {
            $currentCategory = $parentCategory;

            $ids = array_merge([$parentCategory->id], $parentCategory->children->pluck('id')->toArray());
            $portfolioItems = PortfolioItem::whereIn('portfolio_category_id', $ids)
                ->with('image')->paginate(8);
        }
        return view($this->getRenderingViewFile(), [
            'uid' => rand(1111, 9999),
            'title' => $localized->title,
            'm_category' => $parentCategory,
            'm_currentCategoryId' => $currentCategory->id,
            'm_portfolioItems' => $portfolioItems,
            'page' => $this->page,
        ])->render();
    }

    public function sanitizeData()
    {
        $data['categories'] = PortfolioCategory::mappedList(false);

        if($this->editing) {
            foreach ($this->data['localized'] as $i) {
                $data['m__title_' . $i['locale']] = $i['title'];
            }

            $data['m__featured_only'] = $this->data['featured_only'];
            $data['m__portfolio_category_id'] = $this->data['category_id'];
        }

        return $data;
    }

    public function addTranslations()
    {
        $this->translations['portfolio'] = trans('labels.portfolio');
    }
}