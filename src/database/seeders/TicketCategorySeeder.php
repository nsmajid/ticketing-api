<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Bug',
                'description' => 'Application defect',
                'sort_order' => 1,
            ],

            [
                'name' => 'Feature Request',
                'description' => 'New feature request',
                'sort_order' => 2,
            ],

            [
                'name' => 'Change Request',
                'description' => 'Modification request',
                'sort_order' => 3,
            ],

            [
                'name' => 'Enhancement',
                'description' => 'Existing feature enhancement',
                'sort_order' => 4,
            ],

            [
                'name' => 'Question',
                'description' => 'General question',
                'sort_order' => 5,
            ],

            [
                'name' => 'Incident',
                'description' => 'Operational issue',
                'sort_order' => 6,
            ],

        ];

        foreach ($categories as $category) {

            TicketCategory::updateOrCreate(
                [
                    'name' => $category['name']
                ],
                $category
            );

        }
    }
}
