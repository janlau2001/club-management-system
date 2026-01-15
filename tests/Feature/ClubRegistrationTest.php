<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Officer;
use App\Models\Club;
use App\Models\ClubUser;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class ClubRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_register_an_officer_with_auto_generated_credentials()
    {
        $response = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe', 
            'phone' => '09171234567',
            'student_id' => '2024001234',
            'year_level' => '4th Year',
            'course' => 'Computer Science',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        // Check if officer was created
        $this->assertDatabaseHas('officers', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'student_id' => '2024001234',
            'email' => 'john.doe.1234@club.system',
            'position' => 'Officer',
            'registration_status' => 'pending_club_registration'
        ]);

        // Should redirect to club registration form
        $officer = Officer::where('student_id', '2024001234')->first();
        $response->assertRedirect("/club/club-registration/{$officer->id}");
    }

    /** @test */
    public function it_prevents_duplicate_student_ids_across_all_tables()
    {
        // Create an existing officer
        Officer::create([
            'name' => 'Existing Officer',
            'first_name' => 'Existing',
            'last_name' => 'Officer',
            'username' => 'existing.officer.1234',
            'student_id' => '2024001234',
            'email' => 'existing@test.com',
            'phone' => '09171234567',
            'password' => Hash::make('password'),
            'year_level' => '3rd Year',
            'course' => 'Information Technology',
            'department' => 'SITE',
            'position' => 'Officer',
            'club_status' => 'approved',
            'registration_status' => 'approved'
        ]);

        // Try to register with same student ID
        $response = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09171234567',
            'student_id' => '2024001234', // Same as existing
            'year_level' => '4th Year',
            'course' => 'Computer Science',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        $response->assertSessionHasErrors(['student_id']);
    }

    /** @test */
    public function it_prevents_duplicate_student_ids_in_club_users_table()
    {
        // Create a test club first
        $club = Club::create([
            'name' => 'Test Club',
            'department' => 'SITE',
            'club_type' => 'Academic',
            'status' => 'active',
            'adviser_name' => 'Test Adviser',
            'adviser_email' => 'adviser@test.com',
            'date_registered' => now()
        ]);

        // Create an existing club user
        ClubUser::create([
            'club_id' => $club->id,
            'name' => 'Existing Member',
            'student_id' => '2024001234',
            'email' => 'existing@test.com',
            'phone' => '09171234567',
            'password' => Hash::make('password'),
            'year_level' => '3rd Year',
            'role' => 'member',
            'position' => 'Member',
            'department' => 'SITE',
            'joined_date' => now(),
            'status' => 'active'
        ]);

        // Try to register officer with same student ID
        $response = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09171234567',
            'student_id' => '2024001234', // Same as existing club user
            'year_level' => '4th Year',
            'course' => 'Computer Science',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        $response->assertSessionHasErrors(['student_id']);
    }

    /** @test */
    public function it_generates_unique_email_addresses()
    {
        // Create first officer
        $response1 = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09171234567',
            'student_id' => '2024001234',
            'year_level' => '4th Year',
            'course' => 'Computer Science',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        // Approve the first officer to simulate conflict
        $officer1 = Officer::where('student_id', '2024001234')->first();
        $officer1->update(['registration_status' => 'approved']);

        // Create second officer with same name but different student ID
        $response2 = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09171234568',
            'student_id' => '2024005678',
            'year_level' => '3rd Year',
            'course' => 'Information Technology',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        $officer2 = Officer::where('student_id', '2024005678')->first();

        // Emails should be different
        $this->assertNotEquals($officer1->email, $officer2->email);
        
        // First should be john.doe.1234@club.system
        $this->assertEquals('john.doe.1234@club.system', $officer1->email);
        
        // Second should be john.doe.5678@club.system 
        $this->assertEquals('john.doe.5678@club.system', $officer2->email);
    }

    /** @test */
    public function it_sets_position_to_officer_automatically()
    {
        $response = $this->post('/club/officer-registration', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '09171234567',
            'student_id' => '2024001234',
            'year_level' => '4th Year',
            'course' => 'Computer Science',
            'department' => 'SITE',
            'password' => 'TestPassword123',
        ]);

        $officer = Officer::where('student_id', '2024001234')->first();
        $this->assertEquals('Officer', $officer->position);
    }
}
