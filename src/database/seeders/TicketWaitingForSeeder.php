<?php

namespace Database\Seeders;

use App\Models\TicketWaitingFor;
use App\Shared\Enums\Ticket\TicketWaitingForCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketWaitingForSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $waitingFors = [

            [
                'name' => 'Client',
                'code' => TicketWaitingForCode::Client->value,
                'description' => 'Waiting for response or confirmation from client.',
                'sort_order' => 1,
            ],

            [
                'name' => 'Vendor',
                'code' => TicketWaitingForCode::Vendor->value,
                'description' => 'Waiting for action from vendor.',
                'sort_order' => 2,
            ],

            [
                'name' => 'Third Party',
                'code' => TicketWaitingForCode::ThirdParty->value,
                'description' => 'Waiting for third-party service or provider.',
                'sort_order' => 3,
            ],

            [
                'name' => 'Infrastructure',
                'code' => TicketWaitingForCode::Infrastructure->value,
                'description' => 'Waiting for infrastructure team.',
                'sort_order' => 4,
            ],

            [
                'name' => 'DBA',
                'code' => TicketWaitingForCode::DBA->value,
                'description' => 'Waiting for database administrator.',
                'sort_order' => 5,
            ],

            [
                'name' => 'DevOps',
                'code' => TicketWaitingForCode::DevOps->value,
                'description' => 'Waiting for DevOps team.',
                'sort_order' => 6,
            ],

            [
                'name' => 'Management',
                'code' => TicketWaitingForCode::Management->value,
                'description' => 'Waiting for management approval.',
                'sort_order' => 7,
            ],

            [
                'name' => 'Other',
                'code' => TicketWaitingForCode::Other->value,
                'description' => 'Waiting for other dependencies.',
                'sort_order' => 99,
            ],

        ];

        foreach ($waitingFors as $waitingFor) {

            TicketWaitingFor::updateOrCreate(

                [
                    'code' => $waitingFor['code'],
                ],

                [
                    'name' => $waitingFor['name'],
                    'description' => $waitingFor['description'],
                    'is_active' => true,
                    'sort_order' => $waitingFor['sort_order'],
                ]

            );
        }
    }
}
