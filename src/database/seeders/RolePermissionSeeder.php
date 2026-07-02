<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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
        $superAdmin->syncPermissions(
            Permission::pluck('name')->toArray()
        );
        // Vendor Manager
        $vendorManagerPermissions = [

            'dashboard.view',

            'user.view',
            'user.create',
            'user.update',

            'ticket-category.view',
            'ticket-category.create',
            'ticket-category.update',

            'ticket-priority.view',
            'ticket-priority.create',
            'ticket-priority.update',

            'ticket-status.view',
            'ticket-status.create',
            'ticket-status.update',

            'sla.view',
            'sla.manage',

            'ticket.view.all',
            'ticket.view.assigned',
            'ticket.view.own',

            'ticket.create',
            'ticket.update',

            'ticket.review',
            // 'ticket.reject',
            // 'ticket.approve',

            'ticket.assign',
            'ticket.reassign',

            'progress.view',
            'progress.create',
            'progress.update',

            'estimation.create',
            'estimation.update',
            'estimation.approve',

            'change.approve',
            'change.reject',

            'attachment.view',
            'attachment.upload',
            'attachment.delete',

            'report.view',
            'report.export',

            'application.view',
            'application.create',
            'application.update',

            'application-feature.view',
            'application-feature.create',
            'application-feature.update',

            'ticket-waiting-for.view',

            'ticket.progress',
            'ticket.resolve',
        ];

        // Developer
        $developerPermissions = [

            'dashboard.view',

            'ticket-category.view',
            'ticket-priority.view',
            'ticket-status.view',

            'ticket.view.assigned',
            'ticket.view.own',

            'progress.view',
            'progress.create',
            'progress.update',

            'estimation.create',
            'estimation.update',

            'attachment.view',
            'attachment.upload',

            'sla.view',

            'application.view',

            'application-feature.view',

            'ticket-waiting-for.view',

            'ticket.progress',
            'ticket.resolve',

        ];

        // QA
        $qaPermissions = [

            'dashboard.view',

            'ticket-category.view',
            'ticket-priority.view',
            'ticket-status.view',

            'ticket.view.assigned',
            'ticket.view.own',

            'ticket.review',
            // 'ticket.reject',

            'progress.view',
            'progress.create',
            'progress.update',

            'attachment.view',
            'attachment.upload',

            'sla.view',

            'application.view',

            'application-feature.view',

            'ticket-waiting-for.view',

            'ticket.progress',
            'ticket.resolve',
        ];

        // Support
        $supportPermissions = [

            'dashboard.view',

            'ticket-category.view',
            'ticket-priority.view',
            'ticket-status.view',

            'ticket.view.assigned',
            'ticket.view.own',

            'ticket.review',
            // 'ticket.reject',

            'ticket.update',

            'progress.view',

            'attachment.view',
            'attachment.upload',

            'sla.view',

            'application.view',

            'application-feature.view',

            'ticket-waiting-for.view'
        ];

        // Client Admin
        $clientAdminPermissions = [

            'dashboard.view',

            'ticket.view.own',

            'ticket.create',
            'ticket.update',

            'progress.view',

            'estimation.approve',

            'change.approve',
            'change.reject',

            'attachment.view',
            'attachment.upload',

            'sla.view',

            'application-feature.view',
            
            'ticket.close',
        ];

        // Client User
        $clientUserPermissions = [

            'dashboard.view',

            'ticket.view.own',

            'ticket.create',
            'ticket.update',

            'progress.view',

            'attachment.view',
            'attachment.upload',

            'sla.view',

            'application-feature.view',
        ];



        $vendorManager->syncPermissions(
            $vendorManagerPermissions
        );

        $developer->syncPermissions(
            $developerPermissions
        );

        $qa->syncPermissions(
            $qaPermissions
        );

        $support->syncPermissions(
            $supportPermissions
        );

        $clientAdmin->syncPermissions(
            $clientAdminPermissions
        );

        $clientUser->syncPermissions(
            $clientUserPermissions
        );
    }
}
