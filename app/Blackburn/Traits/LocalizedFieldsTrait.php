<?php
namespace App\Blackburn\Traits;

trait LocalizedFieldsTrait {

    public function __get($name)
    {
        if($this->isLocaleAttribute($name)) {
            return $this->getLocaleAttribute($name);
        }

        if($this->isDefaultLocaleAttribute($name)) {
            return $this->getDefaultLocaleAttribute($name);
        }

        if($this->isTranslatedAttribute($name)) {
            return $this->getTranslatedAttribute($name);
        }

        if($this->isArrayAttribute($name)) {
            return $this->getArrayAttribute($name);
        }

        return parent::__get($name);
    }

    public function isLocaleAttribute($name){
        return preg_match('/^[a-z_]+_locale/', $name);
    }

    public function getLocaleAttribute($name) {
        if(count(siteLocales()) == 1) {
            $name = str_replace('_locale', '_default', $name);
            return $this->getDefaultLocaleAttribute($name);
        }

        $attribute = substr($name, 0, strpos($name, '_locale')) . '_' . \App::getLocale();

        return parent::__get($attribute);
    }

    public function isDefaultLocaleAttribute($name){
        return preg_match('/^[a-z_]+_default/', $name);
    }

    public function getDefaultLocaleAttribute($name) {
        $attribute = substr($name, 0, strpos($name, '_default')) . '_' . defaultLocale();
        return parent::__get($attribute);
    }

    public function isTranslatedAttribute($name){
        return preg_match('/^[a-z_]+_translated/', $name) || preg_match('/^[a-z_]+_translated_piped/', $name);
    }

    public function getTranslatedAttribute($name) {
        $translated = '';
        $attribute_name = substr($name, 0, strpos($name, '_translated'));

        if(count(config('site.locales')) == 1) {
            return $this->getLocaleAttribute($attribute_name . '_locale');
        }

        $locales = \Config::get('site.locales');
        $default_locale = \Config::get('site.default_locale');

        if(in_array($default_locale, $locales)) {
            $translated .= parent::__get($attribute_name . '_' .$default_locale);
            unset($locales[array_search($default_locale, $locales)]);
        }

        foreach ($locales as $locale) {
            $data = parent::__get($attribute_name . '_' . $locale);

            if(!empty($data)) {
                $separator = strpos($name, '_translated_piped') ? ' | ' : '<br />';

                $translated .= $separator . $data;
            }

        }

        return $translated;
    }

    public function isArrayAttribute($name){
        return preg_match('/^[a-z_]+_array/', $name);
    }

    public function getArrayAttribute($name) {
        $attribute = substr($name, 0, strpos($name, '_array'));

        $list = [];

        foreach(siteLocales() as $locale) {
            $list[] = $this->{$attribute . '_' . $locale};
        }

        return $list;
    }


}
