<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $superAdmin = Role::findByName('Super Admin');
        $vendorManager = Role::findByName('Vendor Manager');
        $developer = Role::findByName('Developer');
        $qa = Role::findByName('QA');
        $support = Role::findByName('Support');
        $clientAdmin = Role::findByName('Client Admin');
        $clientUser = Role::findByName('Client User');

        // Super Admin
        $superAdmin->givePermissionTo(
            \Spatie\Permission\Models\Permission::all()
        );

        // Vendor Manager
        $vendorManager->givePermissionTo([
            'dashboard.view',

            'ticket.view.all',
            'ticket.create',
            'ticket.update',

            'ticket.review',
            'ticket.approve',
            'ticket.reject',

            'ticket.assign',
            'ticket.reassign',

            'progress.view',

            'estimation.create',
            'estimation.update',

            'attachment.view',
            'attachment.upload',

            'sla.view',
            'sla.manage',

            'report.view',
            'report.export',
        ]);

        // Developer
        $developer->givePermissionTo([
            'dashboard.view',

            'ticket.view.assigned',

            'progress.view',
            'progress.create',
            'progress.update',

            'estimation.create',
            'estimation.update',

            'attachment.view',
            'attachment.upload',
        ]);

        // QA
        $qa->givePermissionTo([
            'dashboard.view',

            'ticket.view.assigned',

            'ticket.review',

            'progress.view',
            'progress.create',
            'progress.update',

            'attachment.view',
            'attachment.upload',
        ]);

        // Support
        $support->givePermissionTo([
            'dashboard.view',

            'ticket.view.assigned',

            'ticket.review',

            'attachment.view',
            'attachment.upload',
        ]);

        // Client Admin
        $clientAdmin->givePermissionTo([
            'dashboard.view',

            'ticket.view.own',
            'ticket.create',
            'ticket.update',

            'change.approve',
            'change.reject',

            'estimation.approve',

            'attachment.view',
            'attachment.upload',
        ]);

        // Client User
        $clientUser->givePermissionTo([
            'dashboard.view',

            'ticket.view.own',
            'ticket.create',

            'attachment.view',
            'attachment.upload',
        ]);
    }
}
