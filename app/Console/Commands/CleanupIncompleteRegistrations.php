<?php

namespace App\Console\Commands;

use App\Models\Officer;
use App\Models\ClubRegistrationRequest;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupIncompleteRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrations:cleanup {--hours=24 : Hours after which incomplete registrations are deleted}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up incomplete registrations that were abandoned or not completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        // Find officers who:
        // 1. Haven't completed registration (registration_status != 'completed')
        // 2. Were created more than X hours ago
        $incompleteOfficers = Officer::where('created_at', '<', $cutoffTime)
            ->where(function($query) {
                $query->whereNull('registration_status')
                      ->orWhere('registration_status', '!=', 'completed');
            })
            ->get();

        if ($incompleteOfficers->isEmpty()) {
            $this->info('No incomplete registrations found to clean up.');
            return 0;
        }

        $count = $incompleteOfficers->count();
        
        foreach ($incompleteOfficers as $officer) {
            // Delete associated club registration request if exists
            if ($officer->clubRegistrationRequest) {
                $officer->clubRegistrationRequest->delete();
            }
            
            // Delete the officer
            $officer->delete();
        }

        $this->info("Cleaned up {$count} incomplete registration(s) older than {$hours} hours.");
        return 0;
    }
}
