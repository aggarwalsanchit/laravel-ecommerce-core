<?php

namespace App\Traits;

use App\Models\Vendor\VendorActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsVendorActivity
{
    protected function logActivity(
        string $action,
        string $entityType = null,
        $entityId = null,
        string $entityName = null,
        $oldValues = null,
        $newValues = null,
        string $description = null
    ) {
        $vendor = Auth::guard('vendor')->user();

        if (!$vendor) {
            return;
        }

        // Get device type
        $userAgent = Request::userAgent();
        $device = 'desktop';
        if (preg_match('/(mobile|android|iphone|ipad|ipod)/i', $userAgent)) {
            $device = 'mobile';
        } elseif (preg_match('/(tablet|ipad)/i', $userAgent)) {
            $device = 'tablet';
        }

        VendorActivityLog::create([
            'shop_id' => $vendor->shop_id,
            'vendor_id' => $vendor->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $entityName,
            'old_values' => $oldValues ? (is_array($oldValues) ? $oldValues : json_decode(json_encode($oldValues), true)) : null,
            'new_values' => $newValues ? (is_array($newValues) ? $newValues : json_decode(json_encode($newValues), true)) : null,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'device' => $device,
        ]);
    }

    protected function logCreate($entityType, $entity, $description = null)
    {
        $this->logActivity(
            'create',
            $entityType,
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            $entity->toArray(),
            $description ?? "Created {$entityType}"
        );
    }

    protected function logUpdate($entityType, $entity, $oldValues, $description = null)
    {
        $this->logActivity(
            'update',
            $entityType,
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            $oldValues,
            $entity->toArray(),
            $description ?? "Updated {$entityType}"
        );
    }

    protected function logDelete($entityType, $entity, $description = null)
    {
        $this->logActivity(
            'delete',
            $entityType,
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            $entity->toArray(),
            null,
            $description ?? "Deleted {$entityType}"
        );
    }

    protected function logLogin($description = null)
    {
        $this->logActivity('login', null, null, null, null, null, $description ?? 'Logged into vendor panel');
    }

    protected function logLogout($description = null)
    {
        $this->logActivity('logout', null, null, null, null, null, $description ?? 'Logged out from vendor panel');
    }

    protected function logUpload($entityType, $file, $description = null)
    {
        $this->logActivity(
            'upload',
            $entityType,
            null,
            $file,
            null,
            null,
            $description ?? "Uploaded {$entityType}: {$file}"
        );
    }
}
