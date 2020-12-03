<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;

class AlbumCategory extends BaseModel {

    use LocalizedFieldsTrait;

    protected $fillable = [];

    protected $table = 'albums_categories';


    protected function setFillableFields()
    {
        $this->fillable = [];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'name_' . $locale;
        }
    }

    public static function getRules()
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'name_' . $default_locale => 'required|max:255',
        ];

        return $rules;
    }

    public function albums()
    {
        return $this->hasMany('App\Models\Album', 'album_category_id', 'id');
    }

    public function albumsCountRelation()
    {
        return $this->hasOne('App\Models\Album')
            ->selectRaw('album_category_id, count(*) as count')
            ->groupBy('album_category_id');
    }

    public function getAlbumsCountAttribute()
    {
        if ($this->albumsCountRelation) {
            return $this->albumsCountRelation->count;
        }

        return 0;
    }

    public static function listCategories()
    {
        $categories = static::orderBy('name_' . defaultLocale(), 'asc')->get();

        $list = [];

        foreach($categories as $category) {
            $list[$category->id] = $category->name_translated_piped;
        }

        return $list;
    }
}
