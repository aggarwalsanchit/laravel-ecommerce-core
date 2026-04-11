<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorActivityLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VendorActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:admin',
            new Middleware('permission:view_logs|vendor', only: ['index', 'show', 'export']),
        ];
    }

    public function index(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $query = VendorActivityLog::where('vendor_id', $vendor->id);

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by entity type
        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->entity_type);
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
        $actions = VendorActivityLog::where('vendor_id', $vendor->id)
            ->distinct()
            ->pluck('action');

        $entityTypes = VendorActivityLog::where('vendor_id', $vendor->id)
            ->whereNotNull('entity_type')
            ->distinct()
            ->pluck('entity_type');

        return view('marketplace.pages.activity-logs.index', compact('logs', 'actions', 'entityTypes'));
    }

    public function show($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $log = VendorActivityLog::where('vendor_id', $vendor->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('marketplace.pages.activity-logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $logs = VendorActivityLog::where('vendor_id', $vendor->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Log the export
        $vendor->logActivity('export', 'activity_logs', null, null, null, null, 'Exported activity logs');

        // Return CSV
        $fileName = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Action', 'Entity Type', 'Entity Name', 'Description', 'IP Address', 'Device']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->human_action,
                    $log->entity_type,
                    $log->entity_name,
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
