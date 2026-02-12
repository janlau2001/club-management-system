<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Google is natively supported by Laravel Socialite
        // No custom provider configuration needed

        // Share sidebar notification counts with the sidebar component
        View::composer('components.dashboard.sidebar', function ($view) {
            $adminRole = session('admin_role');
            $lastVisited = session('sidebar_last_visited', []);

            if ($adminRole === 'head_student_affairs') {
                // Registration Monitoring: pending registrations created after last visit
                $regLastVisited = $lastVisited['approvals'] ?? null;
                $regQuery = \App\Models\ClubRegistrationRequest::where('status', 'pending');
                if ($regLastVisited) {
                    $regQuery->where('created_at', '>', $regLastVisited);
                }
                $registrationCount = $regQuery->count();

                // Renewals: pending_admin renewals created after last visit
                $renewalLastVisited = $lastVisited['renewals'] ?? null;
                $renewalQuery = \App\Models\ClubRenewal::where('status', 'pending_admin');
                if ($renewalLastVisited) {
                    $renewalQuery->where('created_at', '>', $renewalLastVisited);
                }
                $renewalCount = $renewalQuery->count();

                // Appeals: pending appeals created after last visit
                $appealsLastVisited = $lastVisited['appeals'] ?? null;
                $appealsQuery = \App\Models\ViolationAppeal::where('status', 'pending');
                if ($appealsLastVisited) {
                    $appealsQuery->where('submitted_at', '>', $appealsLastVisited);
                }
                $appealsCount = $appealsQuery->count();

                // Violations: confirmed violations (new ones since last visit)
                $violationsLastVisited = $lastVisited['violations'] ?? null;
                $violationsQuery = \App\Models\Violation::where('status', 'confirmed');
                if ($violationsLastVisited) {
                    $violationsQuery->where('created_at', '>', $violationsLastVisited);
                }
                $violationsCount = $violationsQuery->count();

                // Organizations: new clubs created after last visit
                $orgsLastVisited = $lastVisited['organizations'] ?? null;
                $orgsQuery = \App\Models\Club::query();
                if ($orgsLastVisited) {
                    $orgsQuery->where('created_at', '>', $orgsLastVisited);
                } else {
                    $orgsQuery->where('created_at', '>', now());
                }
                $organizationsCount = $orgsQuery->count();

                $view->with('sidebarBadges', [
                    'organizations' => $organizationsCount,
                    'renewals' => $renewalCount,
                    'approvals' => $registrationCount,
                    'appeals' => $appealsCount,
                    'violations' => $violationsCount,
                ]);

            } elseif ($adminRole === 'dean') {
                $regLastVisited = $lastVisited['approvals'] ?? null;
                // Dean is first step: pending registrations not yet endorsed by dean
                $regQuery = \App\Models\ClubRegistrationRequest::where('status', 'pending')
                    ->where('endorsed_by_dean', false);
                if ($regLastVisited) {
                    $regQuery->where('created_at', '>', $regLastVisited);
                }

                $renewalLastVisited = $lastVisited['renewals'] ?? null;
                $renewalQuery = \App\Models\ClubRenewal::where('status', 'pending_admin')
                    ->where('prepared_by_president', true)
                    ->where('certified_by_adviser', true)
                    ->where('noted_by_dean', false);
                if ($renewalLastVisited) {
                    $renewalQuery->where('created_at', '>', $renewalLastVisited);
                }

                $view->with('sidebarBadges', [
                    'approvals' => $regQuery->count(),
                    'renewals' => $renewalQuery->count(),
                ]);

            } elseif ($adminRole === 'psg_council_adviser') {
                $regLastVisited = $lastVisited['approvals'] ?? null;
                // PSG Council: needs dean endorsement first
                $regQuery = \App\Models\ClubRegistrationRequest::where('status', 'pending')
                    ->where('endorsed_by_dean', true)
                    ->where('approved_by_psg_council', false);
                if ($regLastVisited) {
                    $regQuery->where('endorsed_by_dean_at', '>', $regLastVisited);
                }

                $renewalLastVisited = $lastVisited['renewals'] ?? null;
                $renewalQuery = \App\Models\ClubRenewal::where('status', 'pending_admin')
                    ->where('prepared_by_president', true)
                    ->where('certified_by_adviser', true)
                    ->where('reviewed_by_psg', false);
                if ($renewalLastVisited) {
                    $renewalQuery->where('created_at', '>', $renewalLastVisited);
                }

                $view->with('sidebarBadges', [
                    'approvals' => $regQuery->count(),
                    'renewals' => $renewalQuery->count(),
                ]);

            } elseif ($adminRole === 'director_student_affairs') {
                $regLastVisited = $lastVisited['approvals'] ?? null;
                // Director: needs PSG council approval first
                $regQuery = \App\Models\ClubRegistrationRequest::where('status', 'pending')
                    ->where('approved_by_psg_council', true)
                    ->where('noted_by_director', false);
                if ($regLastVisited) {
                    $regQuery->where('approved_by_psg_council_at', '>', $regLastVisited);
                }

                $renewalLastVisited = $lastVisited['renewals'] ?? null;
                $renewalQuery = \App\Models\ClubRenewal::where('status', 'pending_admin')
                    ->where('prepared_by_president', true)
                    ->where('certified_by_adviser', true)
                    ->where('endorsed_by_osa', false);
                if ($renewalLastVisited) {
                    $renewalQuery->where('created_at', '>', $renewalLastVisited);
                }

                $view->with('sidebarBadges', [
                    'approvals' => $regQuery->count(),
                    'renewals' => $renewalQuery->count(),
                ]);

            } elseif ($adminRole === 'vp_academics') {
                $regLastVisited = $lastVisited['approvals'] ?? null;
                // VP: needs director noting first (final registration step)
                $regQuery = \App\Models\ClubRegistrationRequest::where('status', 'pending')
                    ->where('noted_by_director', true)
                    ->where('approved_by_vp', false);
                if ($regLastVisited) {
                    $regQuery->where('noted_by_director_at', '>', $regLastVisited);
                }

                $renewalLastVisited = $lastVisited['renewals'] ?? null;
                $renewalQuery = \App\Models\ClubRenewal::where('status', 'pending_admin')
                    ->where('prepared_by_president', true)
                    ->where('certified_by_adviser', true)
                    ->where('approved_by_vp', false);
                if ($renewalLastVisited) {
                    $renewalQuery->where('created_at', '>', $renewalLastVisited);
                }

                $view->with('sidebarBadges', [
                    'approvals' => $regQuery->count(),
                    'renewals' => $renewalQuery->count(),
                ]);
            }
        });
    }
}
