<?php

namespace App\Blackburn\Pages\Modules;

use App\Models\Product;

class BrandsModule extends AbstractModule
{
    public function renderView($locale)
    {
        $brands = Product::with('logo')->orderBy('name_' . $locale)->get();

        return view($this->getRenderingViewFile(), [
            'brands' => $brands,
        ])->render();
    }

    public function sanitizeData()
    {
        return [];
    }

    public function addTranslations()
    {
        $this->translations['brands_translated'] = labelTranslated('pages.modules.brands');
    }
}