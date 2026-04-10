<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->whereNotNull('user_id')
            ->latest();

        // Search filter
        if ($request->filled('q')) {
            $query->where('description', 'like', '%' . $request->q . '%');
        }

        // Role filter
        if ($request->filled('role')) {
            if ($request->role === 'pengunjung') {
                $query->whereNull('user_id');
            } else {
                $query->whereHas('user', function($q) use ($request) {
                    $q->role($request->role);
                });
            }
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }

    public function clear(Request $request)
    {
        $months = 3;
        $count = ActivityLog::where('created_at', '<', now()->subMonths($months))->count();
        
        ActivityLog::where('created_at', '<', now()->subMonths($months))->delete();

        return back()->with('success', "$count log lama (di atas $months bulan) berhasil dihapus.");
    }
}
