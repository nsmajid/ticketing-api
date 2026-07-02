<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = [

            'ats' => [

                [
                    'name' => 'Dashboard',
                    'code' => 'dashboard',
                ],

                [
                    'name' => 'Green -> General',
                    'code' => 'green-general',
                ],

                [
                    'name' => 'Green -> Granulometry',
                    'code' => 'green-granulometry',
                ],

                [
                    'name' => 'Green -> Cm5',
                    'code' => 'green-cm5',
                ],

                [
                    'name' => 'Baking -> General',
                    'code' => 'baking-general',
                ],

                [
                    'name' => 'Transport -> General',
                    'code' => 'transport-general',
                ],

                [
                    'name' => 'Rodding -> General',
                    'code' => 'rodding-general',
                ],

                [
                    'name' => 'Report & Export',
                    'code' => 'report-export',
                ],

                [
                    'name' => 'Other',
                    'code' => 'other',
                ],

            ],

            'dashboard-ats' => [

                [
                    'name' => 'Manage Dashboard',
                    'code' => 'manage-dashboard',
                ],

                [
                    'name' => 'Show Dashboard',
                    'code' => 'show-dashboard',
                ],

                [
                    'name' => 'Other',
                    'code' => 'other',
                ],

            ],

        ];

        foreach ($applications as $applicationCode => $features) {

            $application = Application::where(
                'code',
                $applicationCode
            )->first();

            if (! $application) {
                continue;
            }

            foreach ($features as $feature) {

                ApplicationFeature::updateOrCreate(

                    [
                        'application_id' => $application->id,
                        'code' => $feature['code'],
                    ],

                    [
                        'name' => $feature['name'],
                        'description' => null,
                        'sort_order' => null,
                    ]

                );
            }
        }
    }
}
