<?php

namespace App\Http\Requests\ControlPanel;

use App\Http\Requests\Request;

class SlideRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title_' . defaultLocale() => 'required|max:255',
        ];

        if($this->method() == 'POST')
            $rules['image'] = 'required|max:4096|image';

        foreach(siteLocales() as $locale) {
            $rules['status_' . $locale] = 'required|in:active,inactive';
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'title_' . defaultLocale() => localizedLabel('title', defaultLocale()),

        ];

        foreach(siteLocales() as $locale) {
            $attributes['status_' . $locale] = localizedLabel('status', $locale);
        }

        return $attributes;
    }
}
