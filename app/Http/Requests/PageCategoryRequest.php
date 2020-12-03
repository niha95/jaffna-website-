<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\PageCategory;

class PageCategoryRequest extends Request
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
        $rules = [
            'name_' . defaultLocale() => 'required|max:255',
            'slug' => 'required',
        ];

        $id = $this->route('id');
        if(!is_null($id)) {
            $category = PageCategory::findOrFail($id);

            if($this->get('slug') != $category->slug)
                $rules['slug'] .= '|unique:pages_categories,slug,NULL,id,parent,' . $category->parent_id;
        }

        if($this->get('page_category_id') != 0)
            $rules['parent_id'] = 'required|exists:pages_categories,id';

        return $rules;
    }

    public function attributes()
    {
        return [
            'name_' . defaultLocale() => localizedLabel('name', defaultLocale()),
        ];
    }
}
