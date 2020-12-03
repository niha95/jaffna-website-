<?php

namespace App\Models;

use App\Blackburn\Traits\StatusAttributeTrait;

class VisitorMessage extends BaseModel {

    use StatusAttributeTrait;

    protected $table = 'visitors_messages';

    protected $fillable = [];

    protected $status_localized = false;


    protected function setFillableFields()
    {
        $this->fillable = ['sender_name', 'sender_email_address', 'sender_phone_number', 'subject', 'message', 'status'];
    }

    public function read()
    {
        if($this->status == 'new') {
            $this->status = 'read';
            $this->save();
        }
    }

    public static function getRules()
    {
        return [
            'sender_name' => 'required|max:255',
            'sender_email_address' => 'required|max:255|email',
            'subject' => 'required',
            'message' => 'required',
            'status' => 'required|in:read,new'
        ];
    }
}
