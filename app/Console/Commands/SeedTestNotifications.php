<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Club;
use App\Models\ClubUser;

class SeedTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:seed {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed test notifications for testing the clearing functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        
        // Get some clubs and club users
        $clubs = Club::take(5)->get();
        $clubUsers = ClubUser::take(10)->get();
        
        if ($clubs->isEmpty() || $clubUsers->isEmpty()) {
            $this->error('No clubs or club users found. Please seed some clubs first.');
            return;
        }
        
        $created = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $club = $clubs->random();
            $clubUser = $clubUsers->where('club_id', $club->id)->first() ?? $clubUsers->random();
            
            $types = ['renewal_reminder', 'general'];
            $type = $types[array_rand($types)];
            
            Notification::create([
                'type' => $type,
                'title' => $type === 'renewal_reminder' ? 'Club Renewal Due' : 'General Notice',
                'message' => $type === 'renewal_reminder' 
                    ? "Your club '{$club->name}' is due for renewal. Please submit your renewal application."
                    : 'This is a general notification from the head office.',
                'club_id' => $club->id,
                'user_id' => $clubUser->id,
                'is_read' => false,
                'data' => [
                    'club_name' => $club->name,
                    'created_by' => 'head_office_seeder'
                ]
            ]);
            
            $created++;
        }
        
        $this->info("Created {$created} test notifications successfully.");
        
        // Show current notification counts
        $totalPending = Notification::where('is_read', false)->count();
        $renewalReminders = Notification::where('type', 'renewal_reminder')->where('is_read', false)->count();
        
        $this->info("Total pending notifications: {$totalPending}");
        $this->info("Pending renewal reminders: {$renewalReminders}");
    }
}
