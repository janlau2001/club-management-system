<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class TestClearNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test-clear {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the notification clearing functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        // Show current stats
        $totalPending = Notification::where('is_read', false)->count();
        $renewalReminders = Notification::where('type', 'renewal_reminder')->where('is_read', false)->count();
        
        $this->info("Before clearing:");
        $this->info("Total pending notifications: {$totalPending}");
        $this->info("Pending renewal reminders: {$renewalReminders}");
        
        if ($type === 'renewal') {
            // Clear only renewal reminders
            $deleted = Notification::where('is_read', false)
                ->where('type', 'renewal_reminder')
                ->delete();
            $this->info("Cleared {$deleted} renewal reminder notifications.");
        } elseif ($type === 'all') {
            // Clear all pending notifications
            $deleted = Notification::where('is_read', false)->delete();
            $this->info("Cleared {$deleted} pending notifications.");
        } else {
            $this->info("Use 'renewal' or 'all' as argument to test clearing.");
            return;
        }
        
        // Show updated stats
        $totalPending = Notification::where('is_read', false)->count();
        $renewalReminders = Notification::where('type', 'renewal_reminder')->where('is_read', false)->count();
        
        $this->info("After clearing:");
        $this->info("Total pending notifications: {$totalPending}");
        $this->info("Pending renewal reminders: {$renewalReminders}");
    }
}
