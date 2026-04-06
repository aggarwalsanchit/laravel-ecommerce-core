<?php

namespace App\Traits;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsAdminActivity
{
    protected function logActivity(
        string $action,
        string $module = null,
        string $entityType = null,
        $entityId = null,
        string $entityName = null,
        $oldValues = null,
        $newValues = null,
        string $description = null
    ) {
        if (!auth()->guard('admin')->check()) {
            return;
        }
        
        $admin = auth()->guard('admin')->user();
        
        // Get device type
        $userAgent = Request::userAgent();
        $device = 'desktop';
        if (preg_match('/(mobile|android|iphone|ipad|ipod)/i', $userAgent)) {
            $device = 'mobile';
        } elseif (preg_match('/(tablet|ipad)/i', $userAgent)) {
            $device = 'tablet';
        }
        
        AdminActivityLog::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'module' => $module,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_name' => $entityName,
            'old_values' => $oldValues ? (is_array($oldValues) ? $oldValues : json_decode(json_encode($oldValues), true)) : null,
            'new_values' => $newValues ? (is_array($newValues) ? $newValues : json_decode(json_encode($newValues), true)) : null,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'device' => $device,
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ]);
    }
    
    // Convenience methods
    protected function logLogin($description = null)
    {
        $this->logActivity('login', 'auth', null, null, null, null, null, $description ?? 'Admin logged in');
    }
    
    protected function logLogout($description = null)
    {
        $this->logActivity('logout', 'auth', null, null, null, null, null, $description ?? 'Admin logged out');
    }
    
    protected function logCreate($module, $entity, $description = null)
    {
        $this->logActivity(
            'create',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            $entity->toArray(),
            $description ?? "Created {$module}"
        );
    }
    
    protected function logUpdate($module, $entity, $oldValues, $description = null)
    {
        $this->logActivity(
            'update',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            $oldValues,
            $entity->toArray(),
            $description ?? "Updated {$module}"
        );
    }
    
    protected function logDelete($module, $entity, $description = null)
    {
        $this->logActivity(
            'delete',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            $entity->toArray(),
            null,
            $description ?? "Deleted {$module}"
        );
    }
    
    protected function logApprove($module, $entity, $description = null)
    {
        $this->logActivity(
            'approve',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            null,
            $description ?? "Approved {$module}"
        );
    }
    
    protected function logReject($module, $entity, $reason = null, $description = null)
    {
        $this->logActivity(
            'reject',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            null,
            $description ?? "Rejected {$module}" . ($reason ? ": {$reason}" : '')
        );
    }
    
    protected function logSuspend($module, $entity, $reason = null, $description = null)
    {
        $this->logActivity(
            'suspend',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            null,
            $description ?? "Suspended {$module}" . ($reason ? ": {$reason}" : '')
        );
    }
    
    protected function logActivate($module, $entity, $description = null)
    {
        $this->logActivity(
            'activate',
            $module,
            class_basename($entity),
            $entity->id,
            $entity->name ?? $entity->title ?? $entity->shop_name ?? null,
            null,
            null,
            $description ?? "Activated {$module}"
        );
    }
}