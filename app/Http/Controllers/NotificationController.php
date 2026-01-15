<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Club;
use App\Models\ClubUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        \Log::info('NotificationController::index called');
        
        $user = session('club_user');
        
        if (!$user) {
            \Log::warning('No authenticated club user found in session');
            return response()->json([
                'error' => 'Not authenticated',
                'notifications' => [],
                'unread_count' => 0,
            ], 401);
        }
        
        \Log::info('Authenticated user from session', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'club_id' => $user->club_id
        ]);
        
        $notifications = Notification::forUser($user->id)
            ->with('club')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = Notification::forUser($user->id)->unread()->count();
        
        \Log::info('Notification query results', [
            'user_id' => $user->id,
            'notifications_count' => $notifications->count(),
            'unread_count' => $unreadCount
        ]);

        return response()->json([
            'notifications' => $notifications->items(),
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $user = session('club_user');
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = session('club_user');
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        Notification::forUser($user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            
        return response()->json(['success' => true]);
    }

    public function sendRenewalReminder(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id'
        ]);

        $club = Club::findOrFail($request->club_id);
        
        // Calculate renewal due date
        $renewalDueDate = $club->date_registered ? $club->date_registered->copy()->addYear() : null;
        
        // Get all club members (officers, members, and advisers)
        $clubUsers = ClubUser::where('club_id', $club->id)->get();
        
        foreach ($clubUsers as $clubUser) {
            Notification::create([
                'type' => 'renewal_reminder',
                'title' => 'Club Renewal Due',
                'message' => "Your club '{$club->name}' is due for renewal. Please submit your renewal application as soon as possible to avoid suspension.",
                'club_id' => $club->id,
                'user_id' => $clubUser->id,
                'data' => [
                    'club_name' => $club->name,
                    'renewal_due_date' => $renewalDueDate ? $renewalDueDate->format('Y-m-d') : null,
                    'action_url' => route('club.officer.renewal'),
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Renewal reminder sent to all {$clubUsers->count()} members of {$club->name}."
        ]);
    }
}
