<?php
namespace App\Blackburn\Traits;

use App\Blackburn\Filters\QueryFilters;

trait QueryFiltersTrait {

    public function scopeFilter($builder, QueryFilters $filters){
        return $filters->apply($builder);
    }

}