<?php
namespace App\Blackburn\Pages\Modules;

use App\Models\PackageCategory;
use Illuminate\Support\Collection;

class StoredPackagesModule extends AbstractModule {

    public function renderView($locale)
    {
        $category = PackageCategory::find($this->data->package_category_id);

        if(is_null($category)) return '';

        $l = $this->getLocalizedData();

        $data = [
            'uid' => rand(1111, 9999),
            'packages' => $category->packages,
            'emart_id' => $this->data->emart_id,
            'title' => $l->title,
            'caption' => $l->caption
        ];

        return view($this->getRenderingViewFile(), $data)->render();
    }

    public function sanitizeData()
    {
        $data = [
            'categories_list' => PackageCategory::listCategories()
        ];

        if($this->editing) {
            foreach($this->data['localized'] as $l) {
                $data['m__title_' . $l['locale']] = $l['title'];
                $data['m__caption_' . $l['locale']] = $l['caption'];
            }

            $data['m__emart_id'] = $this->data['emart_id'];
            $data['m__package_category_id'] = $this->data['package_category_id'];
        }
        return $data;
    }
}