<?php

namespace App\Models;

class Action extends BaseModel {

    protected $table = 'actions';

    protected $fillable = ['user_id', 'description', 'reference'];

    public static function getRules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'description' => 'required',
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
