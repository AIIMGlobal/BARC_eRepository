<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

/* included models */
use App\Models\Notification;

class NotificationController extends Controller
{
    public function read_notification($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)->first();

        $notification->read_status = 1;
        $notification->read_at = now();

        $notification->save();

        return redirect($notification->route_name);
    }

    public function markAllRead()
    {
        try {
            $notifications = Notification::where('read_status', '!=', 1)->get();

            foreach ($notifications as $notification) {
                $notification->read_status = 1;
                $notification->read_at = now();

                $notification->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'All Notifications Marked Read!',
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
            ], 500);
        }
    }
}
