<?php
namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\QueryFiltersTrait;
use App\Blackburn\Traits\StatusAttributeTrait;

class Album extends BaseModel {

    use LocalizedFieldsTrait;
    use QueryFiltersTrait;
    use StatusAttributeTrait;

    protected $fillable = [];

    protected $table = 'albums';


    protected function setFillableFields()
    {
        $this->fillable = ['album_category_id'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'name_' . $locale;
            $this->fillable[] = 'description_' . $locale;
            $this->fillable[] = 'status_' . $locale;
        }
    }

    public function latestImages()
    {
        return $this->images()->latest()->nPerGroup('album_id', 4);
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image');
    }

    public function imagesCountRelation() {
        return $this->hasOne('App\Models\Image')
            ->selectRaw('album_id, count(*) as count')
            ->groupBy('album_id');
    }

    public function getImagesCountAttribute() {
        if($this->imagesCountRelation) {
            return $this->imagesCountRelation->count;
        }

        return 0;
    }

    public function category()
    {
        return $this->belongsTo('App\Models\AlbumCategory', 'album_category_id', 'id');
    }

    public static function getRules()
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'name_' . $default_locale => 'required|max:255',
            'album_category_id' => 'sometimes|exists:albums_categories,id',
        ];

        return $rules;
    }

    public static function availableOrders()
    {
        $orders = [];

        foreach(siteLocales() as $locale) {
            $orders['name_' . $locale . ' asc'] = localizedLabel('name', $locale) . ' - ' . trans('labels.asc');
            $orders['name_' . $locale . ' desc'] = localizedLabel('name', $locale) . ' - ' . trans('labels.desc');
        }

        $orders['created_at' . ' desc'] = trans('labels.latest');

        return $orders;
    }

    public static function boot()
    {
        static::deleted(function($model){
            $images = $model->images;

            Image::bulkDelete($images);
        });

        parent::boot();
    }
}
