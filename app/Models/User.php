<?php

namespace App\Models;

use App\Blackburn\Traits\PermissionsTrait;
use App\Blackburn\Traits\StatusAttributeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable {

    use SoftDeletes;
    use StatusAttributeTrait;
    use PermissionsTrait;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'status', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $status_localized = false;

    /**
     * @var Collection;
     */
    protected $permissions;


    public function canAccessControlPanel()
    {
        return in_array($this->role, config('permissions.can_access_control_panel'));
    }

    public function getRole()
    {
        return trans('roles.' . $this->role);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getFullNameAttribute()
    {
        $full_name = $this->first_name;

        if (!empty($this->last_name)) {
            $full_name .= ' ' . $this->last_name;
        }

        return $full_name;
    }


    public static function getRules($input = [], $user = null)
    {
        $rules = [];

        $rules['first_name'] = 'required';

        if (!$user || $user->email != $input['email']) {
            $rules['email'] = 'required|email|unique:users,email';
        }

        if (!empty($input['password'])) {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }

    public function activities()
    {
        return $this->hasMany('App\Models\Activity');
    }

    public function canReport()
    {
        return in_array($this->role, ['marketing_representative', 'marketing_supervisor',
            'sales_representative', 'sales_supervisor', 'distribution_representative']);
    }

    public static function availableRoles()
    {
        $roles = [];

        foreach (array_keys(config('permissions.roles')) as $role) {
            $roles['role'] = trans('permissions.' . $role);
        }

        return $roles;
    }
}
