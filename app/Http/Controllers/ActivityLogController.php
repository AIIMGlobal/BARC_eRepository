<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/* included models */
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (Gate::allows('activity_log_list', $user)) {
            $activityTypes = [
                'login' => 'Login',
                'logout' => 'Logout',
                'content_create' => 'Content Created',
                'content_edit' => 'Content Edited',
                'content_submit' => 'Content Submitted',
                'content_publish' => 'Content Published',
                'content_archive' => 'Content Archived',
                'content_unarchive' => 'Content Unarchived',
                'content_delete' => 'Content Deleted',
            ];

            $query = ActivityLog::query();

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $logs = $query->latest()->get();

            if ($request->ajax()) {
                $html = view('backend.admin.activityLog.table', compact('logs'))->render();

                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }

            return view('backend.admin.activityLog.index', compact('logs', 'activityTypes'));
        } else {
            return abort(403, "You don't have permission!");
        }
    }
}
