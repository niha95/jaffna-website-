<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MenuRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        if($this->get('parent_id') != 0)
            $rules['parent_id'] = 'required|exists:menus,id';

        foreach(siteLocales() as $locale) {
            $rules['title_' . $locale] = 'required|max:255';
            $rules['status_' . $locale] = 'required|max:255';
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach(siteLocales() as $locale) {
            $attributes['title_' . $locale] = localizedLabel('title', $locale);
            $attributes['status_' . $locale] = localizedLabel('status', $locale);
        }

        return $attributes;
    }
}
