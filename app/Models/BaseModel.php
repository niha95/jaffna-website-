<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {

    public function __construct(array $attributes = [])
    {
        if(method_exists($this, 'setFillableFields')) {
            $this->setFillableFields();
        }

        parent::__construct($attributes);
    }

    public function scopeByName($query, $order = 'asc')
    {
        return $query->orderBy('name', $order);
    }

}