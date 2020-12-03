<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Page;

class FeaturedPagesModule extends AbstractModule {

    public function renderView($locale)
    {
        $pages = Page::with('category', 'featuredImage')->featured()->take(4)->get();

        return view($this->getRenderingViewFile(), [
            'pages' => $pages
        ])->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['featured_pages_translated'] = labelTranslated('pages.modules.featured_pages');
    }
}