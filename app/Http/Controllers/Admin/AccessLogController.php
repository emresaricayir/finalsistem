<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\Member;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    /**
     * Display a listing of access logs
     */
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }
        $query = AccessLog::with(['member', 'user'])
            ->orderByDesc('created_at');

        // Filter by member
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();

        // Get members and users for filters
        $members = Member::orderBy('surname')->orderBy('name')->get(['id', 'name', 'surname']);
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.access-logs.index', compact('logs', 'members', 'users'));
    }
}
