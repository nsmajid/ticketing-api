<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $applications = [

            [
                'name' => 'Anode Tracking System',
                'code' => 'ats',
                'description' => 'Anode Tracking System.',
                'url' => 'https://ats.example.com',
            ],

            [
                'name' => 'Dashboard Anode Tracking System',
                'code' => 'dashboard-ats',
                'description' => 'Dashboard Anode Tracking System.',
                'url' => 'https://dashboard-ats.example.com',
            ],
         ];

         foreach ($applications as $application) {

            Application::updateOrCreate(

                [
                    'code' => $application['code'],
                ],

                $application

            );

        }


    }
}
