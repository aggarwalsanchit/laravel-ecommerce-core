<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminActivityLogController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    //     // $this->middleware('permission:view_activity_logs');
    // }
    
    public function index(Request $request)
    {
        $query = AdminActivityLog::with('admin');
        
        // Filter by admin
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        
        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        
        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        
        // Get filter options
        $admins = \App\Models\Admin::select('id', 'name')->get();
        $actions = AdminActivityLog::distinct()->pluck('action');
        $modules = AdminActivityLog::distinct()->pluck('module');
        
        // Get statistics
        $stats = [
            'total' => AdminActivityLog::count(),
            'today' => AdminActivityLog::whereDate('created_at', today())->count(),
            'this_week' => AdminActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => AdminActivityLog::whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('admin.pages.activity-logs.index', compact('logs', 'admins', 'actions', 'modules', 'stats'));
    }
    
    public function show($id)
    {
        $log = AdminActivityLog::with('admin')->findOrFail($id);
        
        return view('admin.pages.activity-logs.show', compact('log'));
    }
    
    public function export(Request $request)
    {
        $query = AdminActivityLog::with('admin');
        
        // Apply same filters as index
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }
        
        $logs = $query->orderBy('created_at', 'desc')->get();
        
        $fileName = 'admin-activity-logs-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Admin', 'Action', 'Module', 'Entity', 'Description', 'IP Address', 'Device']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->admin->name ?? 'Unknown',
                    $log->human_action,
                    $log->module,
                    $log->entity_name ?? $log->entity_type,
                    $log->description,
                    $log->ip_address,
                    $log->device,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}