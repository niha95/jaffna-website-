<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\Page;

class PageListingModule extends AbstractModule {

    static protected $selectedPages = null;

    public function renderView($locale)
    {
        $pages = $this->getSelectedFeaturedPages();

        $per_row = $this->data->per_row ?: 3;

        $columnClass = ceil(12 / $per_row);

        return view($this->getRenderingViewFile(), [
            'pages' => $pages,
            'column_class' => $columnClass
        ])->render();
    }

    public function sanitizeData()
    {
        $data = [
            'all_pages' => $this->getAllFeaturedPages(),
            'selected_pages' => []
        ];

        if(!$this->editing) return $data;

        $data['selected_pages'] = $this->data['selected_ids'] ?: [];

        return $data;
    }

    public function addTranslations()
    {
        $this->translations['page_listing_translated'] = labelTranslated('pages.modules.page_listing');
    }

    public function getAllFeaturedPages()
    {
        return Page::with('category')->featured()->get();
    }

    public function getSelectedFeaturedPages()
    {
        $ids = $this->data->selected_ids ?: [];

        return Page::with('category', 'featuredImage', 'icon')->whereIn('id', $ids)->get();
    }
}