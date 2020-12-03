<?php
namespace App\Blackburn\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait SlugifyTrait {

    public static function findBySlugOrFail($slug)
    {
        $instance = new static;

        $model = $instance->newQuery()
            ->where($instance->getSlugColumn(), $slug)
            ->first();

        if (!is_null($model)) return $model;

        throw (new ModelNotFoundException())->setModel(get_called_class());
    }

    public function getSlugColumn()
    {
        return 'slug';
    }
}