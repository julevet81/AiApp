<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['waiting', 'created', 'uploaded', 'verified'];

        $apps = [

            [
                'app_name' => 'Volunteer Manager',
                'idea' => 'System to manage volunteers and their activities',
                'domain' => 'volunteer-manager.com',
            ],

            [
                'app_name' => 'School System',
                'idea' => 'Manage students, teachers and classes',
                'domain' => 'school-system.com',
            ],

            [
                'app_name' => 'Clinic Manager',
                'idea' => 'Manage clinic appointments and patients',
                'domain' => 'clinic-manager.com',
            ],

            [
                'app_name' => 'Task Manager',
                'idea' => 'Manage tasks and projects',
                'domain' => 'task-manager.com',
            ],

            [
                'app_name' => 'Donation System',
                'idea' => 'Manage donations and donors',
                'domain' => 'donation-system.com',
            ],

            [
                'app_name' => 'Inventory System',
                'idea' => 'Manage products and inventory',
                'domain' => 'inventory-system.com',
            ],

            [
                'app_name' => 'Booking System',
                'idea' => 'Online booking system',
                'domain' => 'booking-system.com',
            ],

            [
                'app_name' => 'HR System',
                'idea' => 'Manage employees and HR operations',
                'domain' => 'hr-system.com',
            ],

            [
                'app_name' => 'E-commerce Platform',
                'idea' => 'Online store platform',
                'domain' => 'ecommerce-platform.com',
            ],

            [
                'app_name' => 'Learning Platform',
                'idea' => 'Online learning management system',
                'domain' => 'learning-platform.com',
            ],

        ];

        foreach ($apps as $index => $app) {

            Application::create([

                'app_name' => $app['app_name'],

                'idea' => $app['idea'],

                'domain' => $app['domain'],

                'status' => $statuses[array_rand($statuses)],

                'site_url' => 'https://' . $app['domain'],

                'privacy_url' => 'https://' . $app['domain'] . '/privacy',

                'delete_url' => 'https://' . $app['domain'] . '/delete-app',

                'design_url' => 'https://figma.com/' . Str::random(10),
                'files_url' => 'https://' . $app['domain'] . '/files',

                'site_status' => $statuses[array_rand($statuses)],

                'privacy_status' => $statuses[array_rand($statuses)],

                'delete_status' => $statuses[array_rand($statuses)],
                
                'files_status' => $statuses[array_rand($statuses)],

                'chort_description' => 'Short description for ' . $app['app_name'],

                'long_description' => 'This is a long description for ' . $app['app_name'] . '. It explains all features and functionality.',

                'email_access' => 'admin@' . $app['domain'],

                'note' => 'Seeder generated record',

            ]);
        }
    }
}
