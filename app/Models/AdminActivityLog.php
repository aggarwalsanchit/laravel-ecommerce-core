<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivityLog extends Model
{
    protected $table = 'admin_activity_logs';
    
    protected $fillable = [
        'admin_id',
        'action',
        'module',
        'entity_type',
        'entity_id',
        'entity_name',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'device',
        'url',
        'method',
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];
    
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    
    // Get action badge class
    public function getActionBadgeClassAttribute()
    {
        return match($this->action) {
            'login' => 'primary',
            'logout' => 'secondary',
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'approve' => 'success',
            'reject' => 'danger',
            'suspend' => 'warning',
            'activate' => 'success',
            'import' => 'info',
            'export' => 'secondary',
            default => 'secondary',
        };
    }
    
    // Get human readable action
    public function getHumanActionAttribute()
    {
        $actions = [
            'login' => 'Logged In',
            'logout' => 'Logged Out',
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'suspend' => 'Suspended',
            'activate' => 'Activated',
            'import' => 'Imported',
            'export' => 'Exported',
        ];
        
        return $actions[$this->action] ?? ucfirst($this->action);
    }
    
    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function scopeLastWeek($query)
    {
        return $query->whereBetween('created_at', [now()->subWeek(), now()]);
    }
    
    public function scopeLastMonth($query)
    {
        return $query->whereBetween('created_at', [now()->subMonth(), now()]);
    }
    
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }
    
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }
}