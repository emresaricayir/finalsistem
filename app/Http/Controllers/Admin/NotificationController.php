<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    // Bildirimleri getir (AJAX)
    public function getNotifications(): JsonResponse
    {
        $notifications = Notification::getUnread();
        $count = Notification::getUnreadCount();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count
        ]);
    }

    // Bildirimi okundu olarak işaretle
    public function markAsRead(Request $request): JsonResponse
    {
        $notification = Notification::findOrFail($request->notification_id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Tüm bildirimleri okundu olarak işaretle
    public function markAllAsRead(): JsonResponse
    {
        Notification::markAllAsRead();

        return response()->json(['success' => true]);
    }

    // Bildirim sayfasını göster
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}
