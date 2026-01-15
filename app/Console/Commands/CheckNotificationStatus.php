<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class CheckNotificationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the current status of notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalNotifications = Notification::count();
        $pendingNotifications = Notification::where('is_read', false)->count();
        $readNotifications = Notification::where('is_read', true)->count();
        $renewalReminders = Notification::where('type', 'renewal_reminder')->count();
        $pendingRenewalReminders = Notification::where('type', 'renewal_reminder')->where('is_read', false)->count();
        $generalNotifications = Notification::where('type', 'general')->count();
        $pendingGeneralNotifications = Notification::where('type', 'general')->where('is_read', false)->count();
        
        $this->info('=== NOTIFICATION STATUS ===');
        $this->info("Total notifications: {$totalNotifications}");
        $this->info("Pending notifications: {$pendingNotifications}");
        $this->info("Read notifications: {$readNotifications}");
        $this->info('');
        $this->info('=== BY TYPE ===');
        $this->info("Renewal reminders: {$renewalReminders} (Pending: {$pendingRenewalReminders})");
        $this->info("General notifications: {$generalNotifications} (Pending: {$pendingGeneralNotifications})");
        
        if ($pendingNotifications > 0) {
            $this->warn("\nThere are {$pendingNotifications} pending notifications that can be cleared from the head office dashboard.");
        } else {
            $this->info("\nNo pending notifications found.");
        }
    }
}
