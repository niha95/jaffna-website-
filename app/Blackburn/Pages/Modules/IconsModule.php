<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Icon;

class IconsModule extends AbstractModule {

    public function renderView($locale)
    {
        $icons = Icon::with('iconImg')->take(4)->get();
        return view($this->getRenderingViewFile(), ['icons' => $icons])->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['icons_translated'] = labelTranslated('pages.modules.icons');
    }
}