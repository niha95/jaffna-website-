<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;
use App\Blackburn\Traits\ReorderingTrait;
use App\Blackburn\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\Collection;

class MenuItem extends BaseModel {

    use LocalizedFieldsTrait;
    use StatusAttributeTrait;
    use ReorderingTrait;

    protected $table = 'menus';

    protected $fillable = [];


    protected function setFillableFields()
    {
        $this->fillable = ['link', 'order', 'parent_id', 'position', 'icon'];

        foreach (config('site.locales') as $locale) {
            $this->fillable[] = 'title_' . $locale;
            $this->fillable[] = 'status_' . $locale;
        }
    }

    public static function getRules()
    {
        $rules = [
            'link' => 'required',
            'position' => 'required',
            'parent_id' => 'required',
        ];

        foreach (sitelocales() as $locale) {
            $rules['title_' . $locale] = 'required';
            $rules['status_' . $locale] = 'required';
        }

        return $rules;
    }

    public function subMenus()
    {
        return $this->hasMany('App\Models\MenuItem', 'parent_id', 'id');
    }

    public function getPosition()
    {
        return trans('labels.positions.' . $this->position);
    }

    public function scopeParents($query)
    {
        return $query->where('parent_id', 0);
    }

    public function isParent()
    {
        return $this->parent_id == 0;
    }

    public function hasSubMenus()
    {
        if (!$this->subMenus()) $this->subMenus()->get();

        return $this->subMenus->count() != 0;
    }

    public function fullUrl()
    {
        return generateNavLink($this->link);
    }

    public static function availablePositions()
    {
        $positions = [];

        foreach (config('site.menus_positions') as $position) {
            $p = new \stdClass();
            $p->slug = $position;
            $p->thumbnail = url('assets/control-panel/images/positions/' . $position . '.jpg');
            $p->title = trans('labels.positions.' . $position);

            $positions[] = $p;
        }

        return $positions;
    }

    public static function positionsCount()
    {
        return count(config('site.menus_positions'));
    }

    public static function listParents($id = null)
    {
        $builder = static::parents();
        $builder->orderBy('position')->orderBy('order');
        $builder->select('id');

        foreach (siteLocales() as $locale) {
            $builder->addSelect('title_' . $locale);
        }

        if (!is_null($id)) $builder->where('id', '!=', $id);

        $items = $builder->get();

        $list = [];
        foreach ($items as $item) {
            $list[$item->id] = $item->title_translated_piped;
        }

        return $list;
    }

    public static function boot()
    {
        static::updated(function ($item) {
            if ($item->getOriginal('parent_id') == 0) {
                if ($item->parent_id != $item->getOriginal('parent_id')) {
                    MenuItem::where('parent_id', $item->id)->update(['parent_id' => $item->parent_id]);
                }
            }
        });

        parent::boot();
    }

    public static function mappedList($with_icons = true, $locale = null)
    {
        if (is_null($locale)) $locale = app()->getLocale();

        $parents = static::with('subMenus')->active($locale)->parents()->get();

        $list = $parents->map(function ($p) use ($locale) {
            $i['title'] = $p->{'title_' . $locale};
            $i['url'] = url($p->{'link'});

            $i['subMenus'] = $p->subMenus->map(function ($s) use ($locale) {
                $j['title'] = $s->{'title_' . $locale};
                $j['url'] = url($s->{'link'});

                return $j;
            })->toArray();

            return $i;
        });

        return $list;
    }
}
