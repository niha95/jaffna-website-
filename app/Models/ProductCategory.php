<?php

namespace App\Models;

use App\Blackburn\Traits\AddImageTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\SlugifyTrait;

use App\Blackburn\Traits\StatusAttributeTrait;

class ProductCategory extends BaseModel
{
    use StatusAttributeTrait, LocalizedFieldsTrait, SlugifyTrait, AddImageTrait
    ;

    protected $table = 'products_categories';
    protected $fillable = [];
    protected $status_localized = false;


    protected function setFillableFields()
    {
        $this->fillable = ['slug', 'image_id','parent_id'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'name_' . $locale;
          
        }
    }

    public function image()
    {
        return $this->hasOne('App\Models\Image', 'id', 'image_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
    public function sub_products()
    {
        return $this->hasMany('App\Models\Product','product_sub_category_id');
    }
    public function subs()
    {
        return $this->hasMany('App\Models\ProductCategory','parent_id');
    }


    public static function listCategories($activeOnly = false)
    {
        $builder = static::select('id');

        if($activeOnly) $builder->active();

        foreach (siteLocales() as $locale) $builder->addSelect('name_' . $locale);

        $categories = [];
        foreach ($builder->get() as $category) {
            $categories[$category->id] = $category->name_translated_piped;
        }

        return $categories;
    }
}
