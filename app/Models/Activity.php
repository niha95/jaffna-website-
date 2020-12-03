<?php

namespace App\Models;

use App\Blackburn\Traits\LocalizedFieldsTrait;

class Activity extends BaseModel {

    use LocalizedFieldsTrait;

    protected $fillable = [];

    protected $table = 'activities';


    protected function setFillableFields()
    {
        $fillable = ['user_id', 'reference'];

        

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

    public function hasReference()
    {
        return !empty($this->reference);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function log($message, $reference = '', $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        if (!is_array($message)) $message = ['message' => $message];

        $data = [
            'reference' => $reference
        ];

        $options = [
            'user' => $user->email,
        ];

        foreach (siteLocales() as $locale) {
            if (array_key_exists('entity', $message)) {
                $options['entity'] = trans($message['entity'], [], 'messages', $locale);
            }

            $data['description_' . $locale]
                = trans('activities.' . $message['message'], $options, 'messages', $locale);
        }

        $user->activities()->create($data);
    }
}
