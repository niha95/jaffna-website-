<?php
namespace App\Blackburn\Pages\Modules;

class PageTitleModule extends AbstractModule {

    public function renderView($locale)
    {
        return view($this->getRenderingViewFile(), [
            'title' => $this->page->title_locale
        ])->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['page_title_translated'] = labelTranslated('pages.modules.page_title');
    }
}