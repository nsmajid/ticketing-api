<?php

namespace Database\Seeders;

use App\Models\SlaRule;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlaRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [

            /*
            |--------------------------------------------------------------------------
            | Bug
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Bug',
                'ticket_priority' => 'Critical',
                'response_hours' => 1,
                'resolution_hours' => 4,
            ],

            [
                'ticket_category' => 'Bug',
                'ticket_priority' => 'High',
                'response_hours' => 2,
                'resolution_hours' => 8,
            ],

            [
                'ticket_category' => 'Bug',
                'ticket_priority' => 'Medium',
                'response_hours' => 8,
                'resolution_hours' => 24,
            ],

            [
                'ticket_category' => 'Bug',
                'ticket_priority' => 'Low',
                'response_hours' => 24,
                'resolution_hours' => 72,
            ],

            /*
            |--------------------------------------------------------------------------
            | Feature Request
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Feature Request',
                'ticket_priority' => 'Critical',
                'response_hours' => 4,
                'resolution_hours' => 24,
            ],

            [
                'ticket_category' => 'Feature Request',
                'ticket_priority' => 'High',
                'response_hours' => 8,
                'resolution_hours' => 40,
            ],

            [
                'ticket_category' => 'Feature Request',
                'ticket_priority' => 'Medium',
                'response_hours' => 24,
                'resolution_hours' => 72,
            ],

            [
                'ticket_category' => 'Feature Request',
                'ticket_priority' => 'Low',
                'response_hours' => 72,
                'resolution_hours' => 120,
            ],

            /*
            |--------------------------------------------------------------------------
            | Change Request
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Change Request',
                'ticket_priority' => 'Critical',
                'response_hours' => 4,
                'resolution_hours' => 24,
            ],

            [
                'ticket_category' => 'Change Request',
                'ticket_priority' => 'High',
                'response_hours' => 8,
                'resolution_hours' => 48,
            ],

            [
                'ticket_category' => 'Change Request',
                'ticket_priority' => 'Medium',
                'response_hours' => 24,
                'resolution_hours' => 96,
            ],

            [
                'ticket_category' => 'Change Request',
                'ticket_priority' => 'Low',
                'response_hours' => 72,
                'resolution_hours' => 168,
            ],

            /*
            |--------------------------------------------------------------------------
            | Enhancement
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Enhancement',
                'ticket_priority' => 'Critical',
                'response_hours' => 4,
                'resolution_hours' => 24,
            ],

            [
                'ticket_category' => 'Enhancement',
                'ticket_priority' => 'High',
                'response_hours' => 8,
                'resolution_hours' => 48,
            ],

            [
                'ticket_category' => 'Enhancement',
                'ticket_priority' => 'Medium',
                'response_hours' => 24,
                'resolution_hours' => 96,
            ],

            [
                'ticket_category' => 'Enhancement',
                'ticket_priority' => 'Low',
                'response_hours' => 72,
                'resolution_hours' => 168,
            ],

            /*
            |--------------------------------------------------------------------------
            | Question
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Question',
                'ticket_priority' => 'Critical',
                'response_hours' => 2,
                'resolution_hours' => 8,
            ],

            [
                'ticket_category' => 'Question',
                'ticket_priority' => 'High',
                'response_hours' => 4,
                'resolution_hours' => 16,
            ],

            [
                'ticket_category' => 'Question',
                'ticket_priority' => 'Medium',
                'response_hours' => 8,
                'resolution_hours' => 24,
            ],

            [
                'ticket_category' => 'Question',
                'ticket_priority' => 'Low',
                'response_hours' => 24,
                'resolution_hours' => 48,
            ],

            /*
            |--------------------------------------------------------------------------
            | Incident
            |--------------------------------------------------------------------------
            */

            [
                'ticket_category' => 'Incident',
                'ticket_priority' => 'Critical',
                'response_hours' => 1,
                'resolution_hours' => 2,
            ],

            [
                'ticket_category' => 'Incident',
                'ticket_priority' => 'High',
                'response_hours' => 2,
                'resolution_hours' => 6,
            ],

            [
                'ticket_category' => 'Incident',
                'ticket_priority' => 'Medium',
                'response_hours' => 4,
                'resolution_hours' => 12,
            ],

            [
                'ticket_category' => 'Incident',
                'ticket_priority' => 'Low',
                'response_hours' => 8,
                'resolution_hours' => 24,
            ],

        ];

        foreach ($rules as $rule) {

            $category = TicketCategory::where(
                'name',
                $rule['ticket_category']
            )->first();

            $priority = TicketPriority::where(
                'name',
                $rule['ticket_priority']
            )->first();

            SlaRule::updateOrCreate(

                [
                    'ticket_category_id' => $category->id,
                    'ticket_priority_id' => $priority->id,
                ],

                [
                    'response_hours' => $rule['response_hours'],
                    'resolution_hours' => $rule['resolution_hours'],
                    'is_active' => true,
                ]

            );

        }
    }
}
