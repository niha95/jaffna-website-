<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\StatusAttributeTrait;

class News extends BaseModel {

    use LocalizedFieldsTrait;
    use StatusAttributeTrait;

    protected $fillable = [];

    protected $table = 'news';


    protected function setFillableFields()
    {
        $fillable = [];

        foreach (config('site.locales') as $locale) {
            $fillable[] = 'title_' . $locale;
            $fillable[] = 'link_' . $locale;
            $fillable[] = 'status_' . $locale;
        }

        $this->fillable = $fillable;
    }

    public static function getRules()
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'title_' . $default_locale => 'required|max:255',
        ];

        return $rules;
    }
}
