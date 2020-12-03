<?php

namespace App\Models;

use App\Blackburn\Traits\AddImageTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\ReorderingTrait;
use App\Blackburn\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Icon extends BaseModel {

    use LocalizedFieldsTrait;
    use StatusAttributeTrait;
    use ReorderingTrait;
    use AddImageTrait;

    protected $table = 'icons';

    protected $fillable = [];
    
    protected function setFillableFields()
    {
        $this->fillable = ['link', 'order', 'position', 'icon'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'title_' . $locale;
            $this->fillable[] = 'excerpt_' . $locale;
            $this->fillable[] = 'status_' . $locale;
        }
    }

    public static function getRules()
    {
        $rules = [
            'link'      => 'required',
            'icon'      => 'image|max:2048',
        ];

        foreach (sitelocales() as $locale) {
            $rules['title_' . $locale] = 'required';
            $rules['status_' . $locale] = 'required';
        }

        return $rules;
    }

    public function fullUrl()
    {
        return generateNavLink($this->link);
    }

    public function iconImg()
    {
        return $this->belongsTo('App\Models\Image', 'icon', 'id');
    }

    public function addIconImage(UploadedFile $uploaded_image)
    {
        $image = new Image([
            'caption' => $this->getCaption(),
        ]);

        $image->upload($uploaded_image, [
            'width' => 100,
            'thumb_width' => 100,
            'thumb_height' => 100
        ])->save();

        $this->icon = $image->id;
    }
}
