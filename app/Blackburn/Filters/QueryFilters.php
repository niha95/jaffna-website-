<?php
namespace App\Blackburn\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilters {

    protected $request;
    protected $builder;
    protected $string = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function filters()
    {
        return $this->request->all();
    }

    public function has($key)
    {
        return $this->request->has($key);
    }

    public function queryString()
    {
        return array_map(function ($item) {
            return empty($item) ? 'true' : $item;
        }, $this->request->query());
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters() as $name => $value) {
            $name = camel_case($name);

            if (method_exists($this, $name))
                call_user_func_array([$this, $name], array_filter([$value]));

            $this->stringify($name, $value);
        }

        return $builder;
    }

    protected function stringify($name, $value)
    {
        if (method_exists($this, $name . 'Stringify'))
            $this->string[] = call_user_func_array([$this, $name . 'Stringify'], array_filter([$value]));
    }

    public function toString()
    {
        if(!count($this->string)) return '';

        return implode(' / ', $this->string);
    }

}