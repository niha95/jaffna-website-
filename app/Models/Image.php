<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;
use File;
use DB;
use App\Blackburn\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use App\Blackburn\Traits\ScopeNPerGroupTrait;

class Image extends BaseModel {

    use ImageUploadTrait, ScopeNPerGroupTrait, LocalizedFieldsTrait;

    protected $fillable = [];

    protected $table = 'images';


    protected function setFillableFields()
    {
        $this->fillable = ['album_id'];

        foreach(siteLocales() as $locale)
            $this->fillable[] = 'caption_' . $locale;
    }

    public function imageFullLink($absolute = true) {
        return route('images.show_image', $this->image_filename, $absolute);
    }

    public function thumbFullLink() {
        return route('images.show_thumb', $this->thumb_filename);
    }

    public static function boot()
    {
        static::deleted(function($modal){
            File::delete(IMAGE_PATH . $modal->image_filename);
            File::delete(THUMB_PATH . $modal->thumb_filename);
        });

        parent::boot();
    }

    public static function bulkDelete($images){
        $images_files = $images->pluck('image_filename');
        $thumbs_files = $images->pluck('thumb_filename');

        DB::table('images')->whereIn('id', $images->pluck('id')->toArray())->delete();

        foreach($images_files as $image) File::delete(IMAGE_PATH . $image);
        foreach($thumbs_files as $thumb) File::delete(THUMB_PATH . $thumb);
    }
}
