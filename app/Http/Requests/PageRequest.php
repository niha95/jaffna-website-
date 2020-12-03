<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Http\Request as IlluminateRequest;
use App\Models\Page;

class PageRequest extends Request {
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
     * @param \Illuminate\Http\Request $request ;
     * @return array
     */
    public function rules(IlluminateRequest $request)
    {
        $rules = [
            'title_' . defaultLocale() => 'required|min:3|max:255',
            'slug' => 'required',
            'content' => 'json',
            'featured_image' => 'sometimes|image'
        ];

        $id = $this->route('id');
        if (!is_null($id)) {
            $page = Page::findOrFail($id);

            if ($request->get('slug') != $page->slug)
                $rules['slug'] .= '|unique:pages,slug,NULL,id,page_category_id,' . $page->page_category_id;
        }

        if ($request->get('page_category_id') != 0)
            $rules['page_category_id'] = 'required|exists:pages_categories,id';

        foreach (siteLocales() as $locale)
            $rules['status_' . $locale] = 'required|in:active,inactive,featured,home';

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach (siteLocales() as $locale) {
            $attributes['title_' . $locale] = localizedLabel('title', $locale);
            $attributes['status_' . $locale] = localizedLabel('status', $locale);
        }

        return $attributes;
    }
}