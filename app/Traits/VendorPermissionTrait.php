<?php

namespace App\Traits;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

trait VendorPermissionTrait
{
    // Check if vendor has specific permission
    public function hasVendorPermission($permission)
    {
        return $this->hasPermissionTo($permission, 'vendor');
    }

    // Assign role to vendor
    public function assignVendorRole($roleName)
    {
        $role = Role::where('name', $roleName)->where('guard_name', 'vendor')->first();
        if ($role) {
            return $this->assignRole($role);
        }
        return false;
    }

    // Get all vendor permissions
    public function getVendorPermissions()
    {
        return $this->getAllPermissions()->where('guard_name', 'vendor');
    }

    // Sync vendor roles
    public function syncVendorRoles(array $roleNames)
    {
        $roles = Role::whereIn('name', $roleNames)->where('guard_name', 'vendor')->get();
        return $this->syncRoles($roles);
    }

    // Check if vendor is store owner
    public function isStoreOwner()
    {
        return $this->hasRole('store_owner', 'vendor');
    }

    // Check if vendor has any role
    public function hasAnyVendorRole($roles)
    {
        return $this->hasAnyRole($roles, 'vendor');
    }
}
