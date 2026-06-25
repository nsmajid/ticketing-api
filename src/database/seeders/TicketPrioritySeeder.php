<?php

namespace Database\Seeders;

use App\Models\TicketPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = [

            [
                'name' => 'Low',
                'description' => 'Low Priority',
                'response_hours' => 72,
                'resolution_hours' => 120,
                'color' => '#22C55E',
                'sort_order' => 1,
            ],

            [
                'name' => 'Medium',
                'description' => 'Medium Priority',
                'response_hours' => 24,
                'resolution_hours' => 72,
                'color' => '#3B82F6',
                'sort_order' => 2,
            ],

            [
                'name' => 'High',
                'description' => 'High Priority',
                'response_hours' => 8,
                'resolution_hours' => 24,
                'color' => '#F97316',
                'sort_order' => 3,
            ],

            [
                'name' => 'Critical',
                'description' => 'Critical Priority',
                'response_hours' => 1,
                'resolution_hours' => 4,
                'color' => '#EF4444',
                'sort_order' => 4,
            ],
        ];

        foreach ($priorities as $priority) {

            TicketPriority::updateOrCreate(
                ['name' => $priority['name']],
                $priority
            );
        }
    }
}
