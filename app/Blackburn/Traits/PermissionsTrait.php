<?php
namespace App\Blackburn\Traits;

trait PermissionsTrait {

    public function loadPermissions()
    {
        if ($this->permissionsIsLoaded()) return;

        $permissions = new Collection();

        $traverse = function ($d) use (&$traverse, &$permissions) {
            foreach ($d['permissions'] as $p) {
                $permissions->push($p);
            }

            if (array_key_exists('inherit_from', $d)) {
                foreach ($d['inherit_from'] as $s) {
                    $traverse(config('permissions.roles.' . $s));
                }
            }
        };

        $traverse(config('permissions.roles.' . $this->role));

        $this->permissions = $permissions;
    }

    public function hasPermission($p)
    {
        if (!$this->permissionsIsLoaded()) $this->loadPermissions();

        return $this->permissions->contains($p);
    }

    protected function permissionsIsLoaded()
    {
        return !empty($this->permissions);
    }

    public function getLevelAttribute()
    {
        return array_search($this->role, array_keys(config('permissions.roles')));
    }
}