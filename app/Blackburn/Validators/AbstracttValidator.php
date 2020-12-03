<?php
namespace App\Blackburn\Validators;

abstract class Validator {

    abstract function rules();

    abstract function input();

    public function validate()
    {

    }

}