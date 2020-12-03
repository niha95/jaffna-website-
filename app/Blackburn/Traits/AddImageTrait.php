<?php
namespace App\Blackburn\Traits;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

trait AddImageTrait {

    public function addImage(UploadedFile $uploaded_image, $columnName = null, $options = [], $caption = '')
    {
        $columnName = !is_null($columnName) ? $columnName : $this->getImageColumn();

        $options = array_merge($this->getImageOptions(), $options);

        $caption = !empty($caption) ?: $this->getCaption();

        $image = new Image([
            'caption' => $caption,
        ]);

        $image->upload($uploaded_image, $options)->save();

        $this->$columnName = $image->id;
    }

    protected function getImageColumn()
    {
        if(defined('static::IMAGE_COLUMN')) {
            return static::IMAGE_COLUMN;
        }

        return 'image_id';
    }

    public function getCaption()
    {
        $caption = '';

        if(!empty($this->title_locale)) {
            $caption = $this->title_locale;
        } elseif (!empty($this->name_locale)) {
            $caption = $this->name_locale;
        }

        return $caption;
    }

    public function getImageOptions()
    {
        $options = defined('static::IMAGE_OPTIONS') ?: [];

        if(defined('static::IMAGE_WIDTH')) {
            $options['width'] = static::IMAGE_WIDTH;
        }

        return $options;
    }
}