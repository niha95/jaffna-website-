<?php

namespace App\Models;

use App\Blackburn\Traits\AddImageTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\ReorderingTrait;
use App\Blackburn\Traits\StatusAttributeTrait;

class Slide extends BaseModel {

    use AddImageTrait;
    use LocalizedFieldsTrait;
    use StatusAttributeTrait;
    use ReorderingTrait;

    const IMAGE_WIDTH = 1920;
    const IMAGE_HEIGHT = 650;

    const HAS_PARENT_ID_COLUMN = false;

    protected static $IMAGE_OPTIONS = [
        'method' => 'resize',
        'aspectRatio' => false,
    ];

    protected $table = 'slides';

    protected $fillable = [];


    protected function setFillableFields()
    {
        $this->fillable = ['link', 'image_id'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'title_' . $locale;
            $this->fillable[] = 'content_' . $locale;
            $this->fillable[] = 'status_' . $locale;
        }
    }

    public static function getRules($input = [])
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'title_' . $default_locale => 'required|max:255',
        ];

        if ($input['image']) {
            $rules['image'] = 'image|max:2048';
        }

        return $rules;
    }

    public function image()
    {
        return $this->hasOne('App\Models\Image', 'id', 'image_id');
    }

    public function fullLink()
    {
        return generateNavLink($this->link);
    }
}
