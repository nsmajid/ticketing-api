<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::firstOrCreate(
            [
                'email' => 'admin@ticketing.local',
            ],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('Super Admin');

        $roles = [

            'Super Admin' => [
                'Arif Super Admin',
                'Bima Super Admin',
                'Candra Super Admin',
            ],

            'Vendor Manager' => [
                'Dedi Vendor Manager',
                'Erwin Vendor Manager',
                'Fajar Vendor Manager',
            ],

            'Support' => [
                'Andi Support',
                'Budi Support',
                'Citra Support',
            ],

            'Developer' => [
                'Dimas Developer',
                'Eko Developer',
                'Farhan Developer',
            ],

            'QA' => [
                'Gita QA',
                'Hana QA',
                'Indra QA',
            ],

            'Client Admin' => [
                'Joko Client Admin',
                'Kiki Client Admin',
                'Lina Client Admin',
            ],

            'Client User' => [
                'Maya Client User',
                'Nanda Client User',
                'Rizky Client User',
            ],

        ];

        foreach ($roles as $roleName => $users) {

            $role = Role::findByName($roleName);

            foreach ($users as $index => $name) {

                $email = strtolower(
                    str_replace(
                        ' ',
                        '.',
                        preg_replace('/[^A-Za-z0-9 ]/', '', $name)
                    )
                ) . '@example.com';

                $user = User::updateOrCreate(

                    [

                        'email' => $email,

                    ],

                    [

                        'name' => $name,

                        'password' => Hash::make('password'),

                    ]

                );

                $user->syncRoles([$role]);
            }
        }
    }
}
