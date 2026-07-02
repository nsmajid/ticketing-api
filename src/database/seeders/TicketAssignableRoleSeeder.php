<?php

namespace Database\Seeders;

use App\Models\TicketAssignableRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class TicketAssignableRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            'Support',

            'Developer',

            'QA',

        ];

        foreach ($roles as $roleName) {

            $role = Role::findByName($roleName);

            TicketAssignableRole::updateOrCreate(

                [

                    'role_id' => $role->id,

                ]

            );
        }
    }
}
