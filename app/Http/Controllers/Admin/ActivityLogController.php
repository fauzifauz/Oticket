<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\ActivityLog::with('user');

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', \App\Casts\DeterministicEncrypted::encryptForSearch($search));
                  });
            });
        }
        
        $sort = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sort);

        $logs = $query->paginate(20)->withQueryString();
        
        // Get unique actions for filter
        $actions = \App\Models\ActivityLog::select('action')->distinct()->pluck('action');
        
        // Get all staff (Admin and Support) for the user filter
        $staff = \App\Models\User::whereIn('role', ['admin', 'support'])->orderBy('name')->get();
        
        return view('admin.activity_logs.index', compact('logs', 'actions', 'staff'));
    }

    public function clearHistory()
    {
        \App\Models\ActivityLog::truncate();
        return redirect()->back()->with('success', 'Activity logs cleared successfully.');
    }
}
