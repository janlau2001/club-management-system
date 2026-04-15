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
        $deadline = Club::renewalDeadlineForYear(now()->year)->format('F j, Y');

        $clubUsers = ClubUser::where('club_id', $club->id)->get();

        foreach ($clubUsers as $clubUser) {
            Notification::create([
                'type'    => 'renewal_reminder',
                'title'   => 'Club Renewal Reminder',
                'message' => "Your club \"{$club->name}\" has not yet renewed for this year. "
                           . "The renewal deadline is {$deadline}. "
                           . "Please submit your renewal application to continue club operations.",
                'club_id' => $club->id,
                'user_id' => $clubUser->id,
                'is_read' => false,
                'data'    => ['club_name' => $club->name],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Renewal reminder sent to {$clubUsers->count()} member(s) of {$club->name}.",
        ]);
    }

    /**
     * Send bulk renewal reminders to all unrenewed active clubs.
     * Called from the SAASS renewals page header button.
     */
    public function sendBulkRenewalReminder(Request $request)
    {
        $currentYear  = now()->year;
        $deadline     = Club::renewalDeadlineForYear($currentYear)->format('F j, Y');

        $renewedIds = \App\Models\ClubRenewal::where('status', 'approved')
            ->whereYear('created_at', $currentYear)
            ->pluck('club_id')
            ->toArray();

        $clubs = Club::where('status', 'active')
            ->whereNotIn('id', $renewedIds)
            ->get();

        if ($clubs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'All active clubs have already renewed this year. No reminders sent.',
            ]);
        }

        $clubCount  = 0;
        $totalUsers = 0;

        foreach ($clubs as $club) {
            $clubUsers = ClubUser::where('club_id', $club->id)->get();

            foreach ($clubUsers as $clubUser) {
                Notification::create([
                    'type'    => 'renewal_reminder',
                    'title'   => 'Club Renewal Reminder',
                    'message' => "Your club \"{$club->name}\" has not yet renewed for this year. "
                               . "The renewal deadline is {$deadline}. "
                               . "Please submit your renewal application to continue club operations.",
                    'club_id' => $club->id,
                    'user_id' => $clubUser->id,
                    'is_read' => false,
                    'data'    => ['club_name' => $club->name],
                ]);
            }

            $clubCount++;
            $totalUsers += $clubUsers->count();
        }

        return response()->json([
            'success' => true,
            'message' => "Sent reminders to {$clubCount} club(s), notifying {$totalUsers} member(s) total.",
            'clubs_notified' => $clubCount,
            'users_notified' => $totalUsers,
        ]);
    }

    /**
     * Delete all notifications for the authenticated club user.
     */
    public function clearAll()
    {
        $user = session('club_user');

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        Notification::where('user_id', $user->id)->delete();

        return response()->json(['success' => true]);
    }
}
