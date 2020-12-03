<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\PageCategory;

class BreadcrumbModule extends AbstractModule {

    public function renderView($locale)
    {
        $breadcrumb[] = [
            'label' => trans('labels.home'),
            'link' => route('site.home')
        ];

        if($this->page->category) {
            $ancestors = PageCategory::ancestorsOf($this->page->category->id)->sortBy('_lft');

            foreach($ancestors as $a) {
                $breadcrumb[] = [
                    'label' => $a->name_locale,
                    'link' => $a->fullUrl()
                ];
            }

            $breadcrumb[] = [
                'label' => $this->page->category->name_locale,
                'link' => $this->page->category->fullUrl()
            ];
        }

        $breadcrumb[] = [
            'label' => $this->page->title_locale,
            'link' => ''
        ];


        return view($this->getRenderingViewFile(), [
            'breadcrumb' => $breadcrumb
        ])->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['breadcrumb_translated'] = labelTranslated('pages.modules.breadcrumb');

        foreach (siteLocales() as $locale) {
            $this->translations['caption_' . $locale] = trans('labels.translated_label', [
                'label' => trans('labels.caption'),
                'locale' => trans('locales.' . $locale),
            ]);
        }
    }
}