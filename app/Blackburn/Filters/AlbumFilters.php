<?php
namespace App\Blackburn\Filters;

use App\Models\AlbumCategory;
use App\Models\PageCategory;

class AlbumFilters extends QueryFilters {

    public function sort($sort)
    {
        $sort = explode(' ', $sort);

        return $this->builder->orderBy($sort[0], $sort[1]);
    }

    public function sortStringify($sort)
    {
        $sort = explode(' ', $sort);

        $x = extractLabelAndLocale($sort[0]);

        $localized_label = !empty($x[1]) ? localizedLabel($x[0], $x[1]) : trans('labels.' . $x[0]);

        return trans('messages.sort_by', [
            'field' => $localized_label . ' - ' . trans('labels.' . $sort[1])
        ]);
    }

    public function categories($categories)
    {
        $ids = explode(' ', $categories);

        return $this->builder->whereIn('album_category_id', $ids);
    }

    public function categoriesStringify($categories)
    {
        $ids = explode(' ', $categories);

        $categories = AlbumCategory::whereIn('id', $ids)->get();
        $categories = $categories->map(function ($item) {
            return $item['name_translated_piped'];
        });

        return trans('messages.in_categories', ['categories' => implode(', ', $categories->toArray())]);
    }

    public function keywords($keywords)
    {
        return $this->builder->where('name_' . defaultLocale(), 'like', '%' . $keywords . '%');
    }

    public function keywordsStringify($keywords)
    {
        return trans('messages.using_keywords', ['keywords' => $keywords]);
    }


    public function status($status)
    {
        foreach (siteLocales() as $locale)
            $this->builder->where('status_' . $locale, $status);
    }

    public function statusStringify($status)
    {
        return trans('messages.only_status', ['status' => trans('labels.statuses.' . $status)]);
    }

}