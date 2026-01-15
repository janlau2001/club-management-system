<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing phone numbers to the new format if they exist
        // This migration will format existing phone numbers to +63XXXXXXXXXX format
        
        // Use PHP/Laravel Eloquent to handle phone number formatting instead of raw SQL
        // This ensures compatibility with both MySQL and SQLite
        
        // For Officers table - get all records with phone numbers
        $officers = DB::table('officers')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();
        
        foreach ($officers as $officer) {
            $phone = $officer->phone;
            
            // Clean and format the phone number
            if ($phone && (
                str_starts_with($phone, '09') || 
                str_starts_with($phone, '+639') || 
                (strlen(preg_replace('/[^0-9]/', '', $phone)) == 10 && str_starts_with(preg_replace('/[^0-9]/', '', $phone), '9'))
            )) {
                // Remove all non-numeric characters
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                // If it starts with 09, remove the 0
                if (str_starts_with($cleanPhone, '09')) {
                    $cleanPhone = substr($cleanPhone, 1);
                }
                
                // If it starts with 639, remove the 63
                if (str_starts_with($cleanPhone, '639')) {
                    $cleanPhone = substr($cleanPhone, 2);
                }
                
                // Format to +63XXXXXXXXXX
                if (strlen($cleanPhone) == 10 && str_starts_with($cleanPhone, '9')) {
                    $formattedPhone = '+63' . $cleanPhone;
                    
                    DB::table('officers')
                        ->where('id', $officer->id)
                        ->update(['phone' => $formattedPhone]);
                }
            }
        }
        
        // For ClubUsers table - get all records with phone numbers
        $clubUsers = DB::table('club_users')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();
        
        foreach ($clubUsers as $clubUser) {
            $phone = $clubUser->phone;
            
            // Clean and format the phone number
            if ($phone && (
                str_starts_with($phone, '09') || 
                str_starts_with($phone, '+639') || 
                (strlen(preg_replace('/[^0-9]/', '', $phone)) == 10 && str_starts_with(preg_replace('/[^0-9]/', '', $phone), '9'))
            )) {
                // Remove all non-numeric characters
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                
                // If it starts with 09, remove the 0
                if (str_starts_with($cleanPhone, '09')) {
                    $cleanPhone = substr($cleanPhone, 1);
                }
                
                // If it starts with 639, remove the 63
                if (str_starts_with($cleanPhone, '639')) {
                    $cleanPhone = substr($cleanPhone, 2);
                }
                
                // Format to +63XXXXXXXXXX
                if (strlen($cleanPhone) == 10 && str_starts_with($cleanPhone, '9')) {
                    $formattedPhone = '+63' . $cleanPhone;
                    
                    DB::table('club_users')
                        ->where('id', $clubUser->id)
                        ->update(['phone' => $formattedPhone]);
                }
            }
        }
    }

    public function down(): void
    {
        // Revert phone numbers back to 09XXXXXXXXX format
        
        // For Officers table
        $officers = DB::table('officers')
            ->where('phone', 'LIKE', '+639%')
            ->get();
        
        foreach ($officers as $officer) {
            $revertedPhone = '09' . substr($officer->phone, 3);
            DB::table('officers')
                ->where('id', $officer->id)
                ->update(['phone' => $revertedPhone]);
        }
        
        // For ClubUsers table
        $clubUsers = DB::table('club_users')
            ->where('phone', 'LIKE', '+639%')
            ->get();
        
        foreach ($clubUsers as $clubUser) {
            $revertedPhone = '09' . substr($clubUser->phone, 3);
            DB::table('club_users')
                ->where('id', $clubUser->id)
                ->update(['phone' => $revertedPhone]);
        }
    }
};
