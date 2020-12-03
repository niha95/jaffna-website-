<?php
namespace App\Blackburn\Pages\Modules;

class SiteWordModule extends AbstractModule {

    public function renderView($locale)
    {
        return view($this->getRenderingViewFile())->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['site_word_translated'] = labelTranslated('pages.modules.site_word');
    }
}