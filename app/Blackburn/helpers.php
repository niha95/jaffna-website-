<?php

function flashMessage($message, $type = 'success')
{
    session()->flash('flash_message', $message);
    session()->flash('flash_message_type', $type);
}

function createLocalizedColumns(&$table, array $columns)
{
    $site_locales = config('site.locales');

    foreach ($columns as $column_name => $data_type) {
        foreach ($site_locales as $locale) {
            if (is_array($data_type)) {
                $table->{$data_type[0]}($column_name . '_' . $locale, $data_type[1]);
            } else {
                $table->$data_type($column_name . '_' . $locale);
            }
        }
    }
}

function changeCPLocaleUrl($locale = null)
{
    if (is_null($locale)) {
        $locale = config('control_panel.default_locale');
    }

    $segments = request()->segments();

    $segments[1] = $locale;

    return url(implode('/', $segments));
}

function changeSiteLocale($locale = null)
{
    if (is_null($locale)) {
        $locale = config('site.default_locale');
    }

    $segments = request()->segments();

    $segments[0] = $locale;

    $url = url(implode('/', $segments));

    if (request()->getQueryString()) $url .= '?' . request()->getQueryString();

    return $url;
}

function otherCPLocale()
{
    foreach (config('control_panel.locales') as $locale) {
        if ($locale != app()->getLocale()) return $locale;
    }
}

function siteLocales()
{
    return config('site.locales');
}

function defaultLocale()
{
    return config('site.default_locale');
}

function slugLocale()
{
    if(config('site.slug_locale')) {
        return config('site.slug_locale');
    } elseif (in_array('en', siteLocales())) {
        return 'en';
    } else {
        return defaultLocale();
    }
}

function localizedTitle($locale)
{
    if (count(siteLocales()) == 1) return;

    return trans('labels.localized_title', ['locale' => trans('locales.' . $locale)]);
}

function LocalizedLabel($label, $locale)
{
    if (count(siteLocales()) == 1) return trans('labels.' . $label);

    return trans('labels.translated_label', [
        'locale' => trans('locales.' . $locale),
        'label' => trans('labels.' . $label)
    ]);
}

function extractLabelAndLocale($label)
{
    $l = explode('_', $label);

    $site_locales = siteLocales();

    $name = [];
    $locale = '';

    foreach ($l as $i) {
        in_array($i, $site_locales) ? $locale = $i : $name[] = $i;
    }

    $name = implode('_', $name);

    return [$name, $locale];
}

function pageLayouts()
{
    $layouts = [];

    foreach (config('layouts') as $layout_class) {
        $layout = new $layout_class();

        $layouts[] = [
            'name' => $layout->getName(),
            'slug' => $layout->getSlug(),
            'thumbnail' => $layout->renderThumbnail(),
            'positions' => $layout->getPositions()
        ];
    }

    return $layouts;
}

function pageModules()
{
    $modules = [];

    foreach (config('modules') as $module_class) {
        $module = new $module_class();

        $modules[] = [
            'slug' => $module->getSlug(),
            'name' => $module->getName(),
        ];
    }

    return $modules;
}

function locales()
{
    $locales = [];

    foreach (config('site.locales') as $locale) {
        $locales[] = [
            'code' => $locale,
            'name' => trans('locales.' . $locale),
            'localized_title' => localizedTitle($locale),
        ];
    }

    return $locales;
}

function layouts()
{
    $layouts = [];

    foreach (config('layouts') as $layout_class) {
        $layout = new $layout_class();

        $layouts[] = [
            'name' => $layout->getName(),
            'slug' => $layout->getSlug(),
            'thumbnail' => $layout->renderThumbnail(),
            'positions' => $layout->getPositions()
        ];
    }

    return $layouts;
}

function modules()
{
    $modules = [];

    foreach (config('modules') as $module_class) {
        $module = new $module_class();

        $modules[] = [
            'slug' => $module->getSlug(),
            'name' => $module->getName(),
        ];
    }

    return $modules;
}

function localeIcon($locale = null)
{
    if (is_null($locale)) {
        $locale = app()->getLocale();
    }

    switch ($locale) {
        case 'en':
            return 'EN';
        case 'ar':
            return 'ع';
    }
}

function selectPlaceholder($list, $label)
{
    return [0 => placeholderText($label)] + $list;
}

function placeholderText($label)
{
    return ':: ' . trans('actions.select_entity', ['entity' => $label]) . ' ::';
}

function miscLinks()
{
    return [
        ['label' => trans('labels.home'), 'uri' => '/'],
        ['label' => trans('labels.products'), 'uri' => '/shop'],
        ['label' => trans('labels.wholesale_price'), 'uri' => '/wholesale-price'],
        ['label' => trans('labels.contact_us'), 'uri' => '/contact-us'],
    ];
}

function labelTranslated($key, $parameters = [], $delimiter = ' | ')
{
    $translated = [];

    foreach (siteLocales() as $locale) {
        $translated[] = trans($key, $parameters, 'messages', $locale);
    }

    return implode($delimiter, $translated);
}

function currentNav()
{
    return config('site.current_nav');
}

use Illuminate\Support\Str;

function isValidUrl($path)
{
    if (Str::startsWith($path, ['#', '//', 'mailto:', 'tel:', 'http://', 'https://'])) {
        return true;
    }

    return filter_var($path, FILTER_VALIDATE_URL) !== false;
}

function generateNavLink($uri, $locale = null)
{
    if (is_null($locale)) {
        if (count(config('site.locales')) == 1) {
            $locale = '';
        } else {
            $appLocale = app()->getLocale();

            if (in_array($appLocale, siteLocales())) {
                $locale = $appLocale;
            } else {
                $locale = config('site.default_locale');
            }
        }
    }

    return isValidUrl($uri) ? $uri : url($locale . $uri);
}

function slugify($value)
{
    return Str::slug($value);
}

function getSiteLocaleSegment()
{
    if (count(siteLocales()) == 1) {
        $insertSegment = config('site.insert_locale_segment');

        return $insertSegment ? defaultLocale() : '';
    } else {
        $locale = request()->segment(1);

        return in_array($locale, siteLocales()) ? $locale : '';
    }
}