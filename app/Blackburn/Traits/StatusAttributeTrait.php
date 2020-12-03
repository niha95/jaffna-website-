<?php
namespace App\Blackburn\Traits;

trait StatusAttributeTrait
{

    protected $icon_size;

    public function isLocalized()
    {
        return isset($this->status_localized) ? $this->status_localized : true;
    }

    public function getStatusColumn()
    {
        return isset($this->status_column) ? $this->status_column : 'status';
    }

    public function getStatusColumnLocalized($locale = null)
    {
        if (is_null($locale)) $locale = defaultLocale();

        return $this->getStatusColumn() . '_' . $locale;
    }

    public function getStatusColumns()
    {
        if (!$this->isLocalized()) return $this->getStatusColumn();

        $columns = [];

        foreach (siteLocales() as $locale) {
            $columns = $this->getStatusColumnLocalized($locale);
        }

        return $columns;
    }

    public function scopeActive($builder, $locale = null)
    {
        return $this->scopeStatus($builder, 'active', $locale);
    }

    public function scopeInactive($builder, $locale = null)
    {
        return $this->scopeStatus($builder, 'inactive', $locale);
    }

    public function scopeFeatured($builder, $locale = null)
    {
        return $this->scopeStatus($builder, 'featured', $locale);
    }

    public function scopeStatus($builder, $status, $locale = null)
    {
        if (!$this->isLocalized())
            return $builder->where($this->getStatusColumn(), $status);

        if (!is_null($locale))
            return $builder->where($this->getStatusColumnLocalized($locale), $status);

        foreach (siteLocales() as $locale)
            $builder->where($this->getStatusColumnLocalized($locale), $status);

        return $builder;
    }

    public function scopeNotInactive($builder, $locale = null)
    {
        return $this->scopeNotStatus($builder, 'inactive', $locale);
    }

    public function scopeNotStatus($builder, $status, $locale = null)
    {
        $column = $this->isLocalized()
            ? $this->getStatusColumnLocalized($locale)
            : $this->getStatusColumn();

        return $builder->where(function ($query) use ($column, $status) {
            if(!is_array($status)) $status = [$status];

            foreach ($status as $s) $query->where($column, '!=', $s);
        });
    }

    public function renderStatus($locale = null, $icon_size = 'fa-lg')
    {
        $this->icon_size = $icon_size;

        if (!$this->isLocalized()) return $this->renderUnlocalizedStatus();

        if (!is_null($locale))
            return $this->renderLocalizedStatus($locale);

        $html = [];
        foreach (siteLocales() as $locale) {
            $html[] = $this->renderLocalizedStatus($locale);
        }
        return implode('&nbsp&nbsp|&nbsp&nbsp', $html);
    }

    public function renderUnlocalizedStatus()
    {
        return $this->presentStatus($this->{$this->getStatusColumn()});
    }

    public function renderLocalizedStatus($locale = null)
    {
        if (is_null($locale)) $locale = defaultLocale();

        if (count(siteLocales()) > 1) {
            return trans('locales.' . $locale) . ': '
                . $this->presentStatus($this->{$this->getStatusColumnLocalized($locale)});
        } else {
            return $this->presentStatus($this->{$this->getStatusColumnLocalized($locale)});
        }
    }

    public function presentStatus($status)
    {
        $size = $this->icon_size;

        switch ($status) {
            case 'active':
            case 'new':
                return '<i class="fa fa-check fa-fw ' . $size . '" aria-hidden="true"></i>';
            case 'inactive':
                return '<i class="fa fa-times fa-fw ' . $size . '" aria-hidden="true"></i>';
            case 'featured':
                return '<i class="fa fa-star fa-fw ' . $size . '" aria-hidden="true"></i>';
            case 'home':
                return '<i class="fa fa-home fa-fw ' . $size . '" aria-hidden="true"></i>';
        }
    }
}