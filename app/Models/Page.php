<?php

namespace App\Models;

use App\Blackburn\Traits\AddImageTrait;
use App\Blackburn\Traits\QueryFiltersTrait;
use App\Blackburn\Traits\SlugifyTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Page extends BaseModel {

    use SlugifyTrait;
    use AddImageTrait;
    use LocalizedFieldsTrait;
    use QueryFiltersTrait;
    use StatusAttributeTrait;

    const IMAGE_WIDTH = 1024;
    const IMAGE_HEIGHT = null;


    protected $fillable = [];


    protected function setFillableFields()
    {
        $this->fillable = ['slug', 'featured_image_id', 'icon', 'page_category_id', 'content','images','details'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'title_' . $locale;
            $this->fillable[] = 'meta_keywords_' . $locale;
            $this->fillable[] = 'meta_description_' . $locale;
            $this->fillable[] = 'status_' . $locale;
        }
    }

    public function featuredImage()
    {
        return $this->belongsTo('App\Models\Image', 'featured_image_id', 'id');
    }

    public function icon()
    {
        return $this->belongsTo('App\Models\Image', 'icon_image_id', 'id');
    }
    
  

    public static function getRules($input = [], $page = null)
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'title_' . $default_locale => 'required|max:255',
        ];

        if (isset($input['featured_image'])) {
            $rules['featured_image'] = 'image|max:2048';
        }

        if (!$page || $page->slug != $input['slug']) {
            $rules['slug'] = 'required|unique:pages,slug';
        }

        return $rules;
    }

    public function getImageColumn()
    {
        return 'featured_image_id';
    }

    public function category()
    {
        return $this->belongsTo('App\Models\PageCategory', 'page_category_id', 'id');
    }

    public function hasCategory()
    {
        return !empty($this->category);
    }

    public function fullPath($as_array = false)
    {
        $category = $this->category;
        $category_path = is_null($category) ? '' : $category->fullPath();

        $path = $category_path . $this->slug;

        if ($as_array) return explode('/', $path);

        return $path;
    }

    public function fullUrl($locale = null, $absolute = true)
    {
        if (count(siteLocales()) > 1) {
            $locale = '/' . (is_null($locale) ? app()->getLocale() : $locale);
        }

        $uri = $locale . '/' . $this->fullPath() . '.html';

        return $absolute ? url($uri) : $uri;
    }

    public function getCategoriesPath()
    {
        $path = '';

        $node = PageCategory::find($this->page_category_id);
        if ($node == null) return $path;

        $ancestors = $node->getAncestors();

        foreach ($ancestors as $i) {
            $path .= $i->slug . '/';
        }

        $path .= $node->slug . '/';

        return $path;
    }

    public static function availableOrders()
    {
        $orders = [];

        foreach (siteLocales() as $locale) {
            $orders['title_' . $locale . ' asc'] = localizedLabel('title', $locale) . ' - ' . trans('labels.asc');
            $orders['title_' . $locale . ' desc'] = localizedLabel('title', $locale) . ' - ' . trans('labels.desc');
        }

        $orders['created_at' . ' desc'] = trans('labels.latest');

        return $orders;
    }

    public function getFirstTextContentAttribute()
    {
        return $this->getFirstTextContent();
    }

    public function getFirstTextContent($locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();

        $content = json_decode($this->content);

        foreach ($content->positions as $position) {
            foreach ($position->modules as $module) {
                if ($module->type != 'text-content') continue;

                foreach ($module->data->localized as $localized_item) {
                    if ($localized_item->locale != $locale) continue;

                    return $localized_item->content;
                }
            }
        }
    }

    public function setPathAttribute($path)
    {
        $this->attributes['path'] = empty($path) ? '/' : $path;
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

        $this->icon_image_id = $image->id;
    }
    
    
     public function addPageImages(UploadedFile $uploaded_image)
    {
        $image = new Image([
            'caption' => $this->getCaption(),
        ]);

        $image->upload($uploaded_image, [
            'width' => 100,
            'thumb_width' => 100,
            'thumb_height' => 100
        ])->save();

        return $image->id;
    }
    

    public function render($locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();


        $content = json_decode($this->content);
                

        if (is_null($content) || is_null($content->layout)) return '';

        $layout = config('pages.layouts.' . $content->layout);

        return (new $layout($content->positions, $this))->render($locale);
    }

    public static function listPages()
    {
        $builder = static::query();

        $builder->select('id');
        $builder->orderBy('title_' . defaultLocale());

        foreach (siteLocales() as $locale) {
            $builder->addSelect('title_' . $locale);
        }

        $pages = $builder->get();

        $list = [];
        foreach ($pages as $page) {
            $list[$page->id] = $page->title_translated_piped;
        }

        return $list;
    }
}
