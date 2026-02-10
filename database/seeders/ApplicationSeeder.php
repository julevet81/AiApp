<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use Illuminate\Support\Carbon;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $applications = [
            [
                'app_name' => 'Relief Management System',
                'idea'     => 'Platform to manage relief aid and beneficiaries',
                'domain'   => 'https://relief.example.com',
                'status'   => 'waiting',
                'note'     => 'Core system for NGO operations',
            ],
            [
                'app_name' => 'Volunteer Tracker',
                'idea'     => 'Track volunteers participation and skills',
                'domain'   => 'https://volunteers.example.com',
                'status'   => 'created',
                'note'     => null,
            ],
            [
                'app_name' => 'Donation Portal',
                'idea'     => 'Online donations and donor management',
                'domain'   => 'https://donate.example.com',
                'status'   => 'verified',
                'note'     => 'Waiting for payment gateway integration',
            ],
            [
                'app_name' => 'Event Organizer',
                'idea'     => 'Organize charity events and campaigns',
                'domain'   => 'https://events.example.com',
                'status'   => 'uploaded',
                'note'     => 'Paused due to budget limitations',
            ],
        ];

        foreach ($applications as $app) {
            Application::create($app);
        }
    }
}
