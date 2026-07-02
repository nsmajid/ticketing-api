<?php

namespace Database\Seeders;

use App\Models\TicketPriority;
use App\Shared\Enums\Ticket\TicketPriorityLevel;
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
                'name' => TicketPriorityLevel::Low->label(),
                'code' => TicketPriorityLevel::Low->value,
                'description' => 'Low impact issue. No immediate action required.',
                'response_hours' => 24,
                'resolution_hours' => 72,
                'color' => '#22C55E',
                'is_active' => true,
                'sort_order' => 1,
            ],

            [
                'name' => TicketPriorityLevel::Medium->label(),
                'code' => TicketPriorityLevel::Medium->value,
                'description' => 'Normal business issue.',
                'response_hours' => 8,
                'resolution_hours' => 24,
                'color' => '#EAB308',
                'is_active' => true,
                'sort_order' => 2,
            ],

            [
                'name' => TicketPriorityLevel::High->label(),
                'code' => TicketPriorityLevel::High->value,
                'description' => 'High impact issue requiring immediate attention.',
                'response_hours' => 4,
                'resolution_hours' => 12,
                'color' => '#F97316',
                'is_active' => true,
                'sort_order' => 3,
            ],

            [
                'name' => TicketPriorityLevel::Critical->label(),
                'code' => TicketPriorityLevel::Critical->value,
                'description' => 'Critical issue affecting business operations.',
                'response_hours' => 1,
                'resolution_hours' => 4,
                'color' => '#DC2626',
                'is_active' => true,
                'sort_order' => 4,
            ],

        ];

        foreach ($priorities as $priority) {

            TicketPriority::updateOrCreate(
                ['code' => $priority['code']],
                $priority
            );
        }
    }
}
