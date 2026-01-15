<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\ClubUser;
use Illuminate\Support\Facades\Auth;

class DebugNotifications extends Command
{
    protected $signature = 'notifications:debug';
    protected $description = 'Debug notification issues';

    public function handle()
    {
        $this->info('=== NOTIFICATION DEBUG ===');
        
        // Check all notifications
        $notifications = Notification::with(['user', 'club'])->get();
        $this->info("Total notifications in database: " . $notifications->count());
        
        foreach ($notifications as $notification) {
            $this->info("Notification ID: {$notification->id}");
            $this->info("  User ID: {$notification->user_id}");
            $this->info("  Club ID: {$notification->club_id}");
            $this->info("  Title: {$notification->title}");
            $this->info("  Type: {$notification->type}");
            $this->info("  Is Read: " . ($notification->is_read ? 'Yes' : 'No'));
            
            if ($notification->user) {
                $this->info("  User Name: {$notification->user->name}");
                $this->info("  User Email: {$notification->user->email}");
            } else {
                $this->warn("  User not found for this notification!");
            }
            
            if ($notification->club) {
                $this->info("  Club Name: {$notification->club->name}");
            }
            $this->info("---");
        }
        
        // Check all club users
        $this->info("\n=== CLUB USERS ===");
        $clubUsers = ClubUser::all();
        foreach ($clubUsers as $user) {
            $this->info("ClubUser ID: {$user->id}, Name: {$user->name}, Club ID: {$user->club_id}, Email: {$user->email}");
        }
        
        // Try to simulate the notification fetch
        $this->info("\n=== SIMULATING NOTIFICATION FETCH ===");
        
        // Get first club user to test
        $testUser = ClubUser::first();
        if ($testUser) {
            $this->info("Testing with user: {$testUser->name} (ID: {$testUser->id})");
            
            $userNotifications = Notification::where('user_id', $testUser->id)
                ->with('club')
                ->orderBy('created_at', 'desc')
                ->get();
                
            $this->info("Notifications for this user: " . $userNotifications->count());
            
            $unreadCount = Notification::where('user_id', $testUser->id)
                ->where('is_read', false)
                ->count();
                
            $this->info("Unread notifications for this user: " . $unreadCount);
        } else {
            $this->warn("No club users found!");
        }
    }
}
