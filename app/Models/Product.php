<?php 

namespace App\Models;

use App\Blackburn\Traits\AddImageTrait;
use App\Blackburn\Traits\SlugifyTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\StatusAttributeTrait;

class Product extends BaseModel {

    use LocalizedFieldsTrait;
    use SlugifyTrait;
    use StatusAttributeTrait;
    use AddImageTrait;

    const IMAGE_WIDTH = 1024;
    const IMAGE_HEIGHT = null;

    protected $fillable = [];

    protected $table = 'products';

    public function __construct(array $attributes = [])
    {
        $this->setFillableFields();

        parent::__construct($attributes);
    }

    protected function setFillableFields()
    {
        $fillable = ['slug', 'status',  'image_product',  'product_category_id'];

        foreach (config('site.locales') as $locale) {
            $fillable[] = 'name_' . $locale;
            $fillable[] = 'description_' . $locale;
            $fillable[] = 'status_' . $locale;
        }

        $this->fillable = $fillable;
    }

    public static function getRules($input = [], $product = null)
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'name_' . $default_locale => 'required|max:255',
        ];

        if (!$product || $product->slug != $input['slug']) {
            $rules['slug'] = 'required|unique:products,slug';
        }
        

        
        return $rules;
    }

    public function image()
    {
         return $this->belongsTo('App\Models\Image', 'image_product', 'id');
    }
public function getImageColumn()
    {
        return 'image_product';
    }
     public function addImages($imagesList)
    {
        $ids = [];

        foreach ($imagesList as $uploadedImage) {
            $image = new Image();

            $image->upload($uploadedImage);

            if ($image->save()) $ids[] = $image->id;
        }

        $this->images()->attach($ids);
    }
    public function images()
    {
        return $this->hasMany(ProductAlbum::class);
    }
}

