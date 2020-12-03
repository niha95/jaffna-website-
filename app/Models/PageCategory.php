<?php

namespace App\Models;

use App\Blackburn\Traits\SlugifyTrait;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Blackburn\Traits\LocalizedFieldsTrait;

class PageCategory extends BaseModel {

    use NodeTrait;
    use LocalizedFieldsTrait;
    use SlugifyTrait;

    protected $fillable = [];

    protected $table = 'pages_categories';


    protected function setFillableFields()
    {
        $fillable = ['slug', 'parent_id'];

        foreach (config('site.locales') as $locale) {
            $fillable[] = 'name_' . $locale;
        }

        $this->fillable = $fillable;
    }

    public static function getRules($input = [], $category = null)
    {
        $default_locale = config('site.default_locale');

        $rules = [
            'name_' . $default_locale => 'required|max:255',
        ];

        if (!$category || $category->slug != $input['slug']) {
            $rules['slug'] = 'required|unique:pages_categories,slug';
        }

        return $rules;
    }

    public function pages()
    {
        return $this->hasMany('App\Models\Page', 'page_category_id', 'id');
    }

    public function pagesCountRelation()
    {
        return $this->hasOne('App\Models\Page')
            ->selectRaw('page_category_id, count(*) as count')
            ->groupBy('page_category_id');
    }

    public function getPagesCountAttribute()
    {
        if ($this->pagesCountRelation) {
            return $this->pagesCountRelation->count;
        }

        return 0;
    }

    public function fullPath()
    {
        return ($this->path == '/' ? '' : $this->path) . $this->slug . '/';
    }

    public function fullUrl($locale = null, $absolute = true)
    {
        $uri = '/' . $this->fullPath();

        return $absolute ? url($uri) : $uri;
    }

    public function generatePath()
    {
        $parent = $this->parent;

        $this->path = !is_null($parent) ? $parent->fullPath() : '/';
    }

    public function syncChildren()
    {
        if($this->isDirty('parent_id')) {
            $this->path = $this->parent()->first()->fullPath();
        }

        $children = $this->descendants->toTree();

        if($children->isEmpty()) return;

        $stmt = 'UPDATE pages_categories SET `path` = (CASE ';
        $ids = [];

        $traverse = function ($categories, $prefix = '') use (&$traverse, &$stmt, &$ids) {
            if (empty($prefix)) $prefix = $this->fullPath();

            foreach ($categories as $category) {
                $stmt .= "WHEN `id` = {$category->id} THEN '{$prefix}' ";
                $ids[] = $category->id;

                $traverse($category->children, $prefix . $category->slug . '/');
            }
        };

        $traverse($children);

        $stmt .= 'ELSE `path` END) ';
        $stmt .= "WHERE id IN (" . implode(', ', $ids) . ")";

        \DB::statement($stmt);
    }

    public static function mappedList($withPlaceholder = true)
    {
        $default_locale = config('site.default_locale');

        $nodes = static::orderBy('name_' . $default_locale)->get()->toTree();


        if ($withPlaceholder) {
            $list = [
                0 => placeholderText(trans('labels.parent'))
            ];
        } else {
            $list = [];
        }


        $traverse = function ($categories, $prefix = '') use (&$traverse, &$list) {
            foreach ($categories as $category) {
                $list[$category->id] = $prefix . ' ' . $category->name_translated_piped;

                $traverse($category->children, $prefix . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
            }
        };

        $traverse($nodes);

        return $list;
    }

    public static function boot()
    {
        static::saving(function ($model) {
            $model->generatePath();
        });

        static::updating(function ($model) {
            if ($model->isDirty('slug') || $model->isDirty('parent_id')) {
                $model->syncChildren();
            }
        });

        parent::boot();
    }
}
