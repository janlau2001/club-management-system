<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'head.studentaffairs@club.com'],
            [
                'name' => 'Head of Student Affairs',
                'password' => Hash::make('admin123'),
                'role' => 'head_student_affairs',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'director.studentaffairs@club.com'],
            [
                'name' => 'Director of Student Affairs',
                'password' => Hash::make('director123'),
                'role' => 'director_student_affairs',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'vp.academics@club.com'],
            [
                'name' => 'Vice President for Academics',
                'password' => Hash::make('vp123'),
                'role' => 'vp_academics',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'dean@club.com'],
            [
                'name' => 'Dean',
                'password' => Hash::make('dean123'),
                'role' => 'dean',
            ]
        );
    }
}




