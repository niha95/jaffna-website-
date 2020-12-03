<?php

namespace App\Models;

use App\Blackburn\Traits\StatusAttributeTrait;

class Contact extends BaseModel {

    use StatusAttributeTrait;

    protected $table = 'contacts';

    protected $fillable = [];

    protected $status_localized = false;


    protected function setFillableFields()
    {
        $this->fillable = ['name', 'email_address', 'newsletter', 'token'];
    }

    public function unsubscribe()
    {
        if ($this->newsletter == 1) {
            $this->newsletter = 0;
            $this->save();
        }
    }

    public static function validateToken($email, $token)
    {
        return static::where(['email' => $email,'token' => $token,])->first();
    }

    public static function getRules()
    {
        return [
            'name' => 'required|max:255',
            'email_address' => 'required|max:255|email|unique:contacts,email_address',
            'newsletter' => 'required',
        ];
    }

    public static function boot()
    {
        static::creating(function($model){
            $model->token = str_random(64);
            $model->newsletter = 1;
        });

        parent::boot();
    }
}
