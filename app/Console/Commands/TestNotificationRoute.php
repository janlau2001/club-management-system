<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\ClubUser;

class TestNotificationRoute extends Command
{
    protected $signature = 'notifications:test-route {user_id}';
    protected $description = 'Test notification route for specific user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $this->info("Testing notification route for user ID: {$userId}");
        
        // Find the user
        $user = ClubUser::find($userId);
        if (!$user) {
            $this->error("User not found!");
            return;
        }
        
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Club ID: {$user->club_id}");
        
        // Test the notification query
        $notifications = Notification::where('user_id', $userId)
            ->with('club')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
            
        $this->info("Notifications found: " . $notifications->count());
        $this->info("Unread count: " . $unreadCount);
        
        // Show the response format
        $response = [
            'notifications' => $notifications->items(),
            'unread_count' => $unreadCount,
        ];
        
        $this->info("Response structure:");
        $this->info("- notifications array count: " . count($response['notifications']));
        $this->info("- unread_count: " . $response['unread_count']);
        
        foreach ($response['notifications'] as $notif) {
            $this->info("  Notification: {$notif->title} (ID: {$notif->id})");
        }
    }
}
