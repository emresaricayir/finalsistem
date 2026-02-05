<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmailLogController extends Controller
{
    /**
     * Display email logs
     */
    public function index(Request $request)
    {
        $query = EmailLog::query();

        // Filter by template
        if ($request->filled('template')) {
            $query->where('template_key', $request->template);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by batch
        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by email or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('recipient_email', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get available templates for filter
        $templates = EmailTemplate::getActive()->pluck('name', 'key');

        // Get unique batch IDs for filter
        $batchIds = EmailLog::whereNotNull('batch_id')
            ->distinct()
            ->pluck('batch_id')
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'pending' => EmailLog::where('status', 'pending')->count(),
        ];

        return view('admin.email-logs.index', compact(
            'logs',
            'templates',
            'batchIds',
            'stats'
        ));
    }


    /**
     * Get batch details
     */
    public function batch($batchId)
    {
        $logs = EmailLog::where('batch_id', $batchId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($logs->isEmpty()) {
            abort(404, 'Batch not found');
        }

        $stats = [
            'total' => $logs->count(),
            'sent' => $logs->where('status', 'sent')->count(),
            'failed' => $logs->where('status', 'failed')->count(),
            'pending' => $logs->where('status', 'pending')->count(),
        ];

        return view('admin.email-logs.batch', compact('logs', 'batchId', 'stats'));
    }

    /**
     * Clean email logs
     */
    public function clean(Request $request)
    {
        try {
            if ($request->input('all')) {
                // Delete all logs
                $deletedCount = EmailLog::count();
                EmailLog::truncate();

                return response()->json([
                    'success' => true,
                    'message' => "Tüm {$deletedCount} e-posta logu silindi."
                ]);
            } else {
                $days = $request->input('days', 30);
                $cutoffDate = now()->subDays($days);

                $deletedCount = EmailLog::where('created_at', '<', $cutoffDate)->delete();

                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} adet {$days} günden eski e-posta logu silindi."
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Log temizleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
