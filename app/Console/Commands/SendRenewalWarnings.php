<?php

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\ClubRenewal;
use App\Models\ClubUser;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendRenewalWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renewals:send-warnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal deadline warning notifications to unrenewed clubs (run on August 21)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!Club::isRenewalWarningSoon()) {
            $this->info('Not in the warning window (Aug 21–31). No notifications sent.');
            return Command::SUCCESS;
        }

        $currentYear = now()->year;
        $deadline    = Club::renewalDeadlineForYear($currentYear)->toFormattedDateString(); // e.g. "Aug 31, 2025"

        // Collect IDs of clubs already renewed this year (approved renewal exists for current year)
        $renewedClubIds = ClubRenewal::where('status', 'approved')
            ->whereYear('created_at', $currentYear)
            ->pluck('club_id')
            ->toArray();

        // Active clubs that have NOT renewed yet this year
        $unrenewedClubs = Club::where('status', 'active')
            ->whereNotIn('id', $renewedClubIds)
            ->get();

        if ($unrenewedClubs->isEmpty()) {
            $this->info('All active clubs have already renewed. No notifications sent.');
            return Command::SUCCESS;
        }

        $sent   = 0;
        $skipped = 0;

        foreach ($unrenewedClubs as $club) {
            // Idempotency: skip if a renewal_reminder was already sent today for this club
            $alreadySent = Notification::where('club_id', $club->id)
                ->where('type', 'renewal_reminder')
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($alreadySent) {
                $skipped++;
                continue;
            }

            // Get all officer / adviser user IDs for this club
            $recipientIds = ClubUser::where('club_id', $club->id)
                ->whereIn('role', ['officer', 'adviser'])
                ->pluck('user_id')
                ->unique();

            if ($recipientIds->isEmpty()) {
                // Fall back to all members
                $recipientIds = ClubUser::where('club_id', $club->id)
                    ->pluck('user_id')
                    ->unique();
            }

            foreach ($recipientIds as $userId) {
                Notification::create([
                    'type'    => 'renewal_reminder',
                    'title'   => 'Club Renewal Deadline Approaching',
                    'message' => "Your club \"{$club->name}\" has not yet renewed for this year. "
                               . "The renewal window closes on {$deadline}. "
                               . "Please submit your renewal application to continue club operations.",
                    'club_id' => $club->id,
                    'user_id' => $userId,
                    'is_read' => false,
                ]);
            }

            $sent++;
            $this->line("  Notified: {$club->name} ({$recipientIds->count()} recipients)");
        }

        $this->info("Done. Warned {$sent} clubs, skipped {$skipped} (already notified today).");
        Log::info("renewals:send-warnings completed — warned: {$sent}, skipped: {$skipped}");

        return Command::SUCCESS;
    }
}
