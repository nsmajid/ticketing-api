<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'dashboard.view',
                'label' => 'View Dashboard',
                'group' => 'Dashboard',
                'description' => 'Can view dashboard',
                'sort_order' => 1,
            ],

            /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'user.view',
                'label' => 'View User',
                'group' => 'User',
                'description' => 'Can view users',
                'sort_order' => 10,
            ],

            [
                'name' => 'user.create',
                'label' => 'Create User',
                'group' => 'User',
                'description' => 'Can create users',
                'sort_order' => 11,
            ],

            [
                'name' => 'user.update',
                'label' => 'Update User',
                'group' => 'User',
                'description' => 'Can update users',
                'sort_order' => 12,
            ],

            [
                'name' => 'user.delete',
                'label' => 'Delete User',
                'group' => 'User',
                'description' => 'Can delete users',
                'sort_order' => 13,
            ],

            /*
    |--------------------------------------------------------------------------
    | Role & Permission
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'role.view',
                'label' => 'View Role',
                'group' => 'Role',
                'description' => 'Can view roles',
                'sort_order' => 20,
            ],

            [
                'name' => 'role.manage',
                'label' => 'Manage Role',
                'group' => 'Role',
                'description' => 'Can manage roles',
                'sort_order' => 21,
            ],

            [
                'name' => 'permission.view',
                'label' => 'View Permission',
                'group' => 'Role',
                'description' => 'Can view permissions',
                'sort_order' => 22,
            ],

            [
                'name' => 'permission.manage',
                'label' => 'Manage Permission',
                'group' => 'Role',
                'description' => 'Can manage permissions',
                'sort_order' => 23,
            ],

            /*
    |--------------------------------------------------------------------------
    | Ticket
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'ticket.view.all',
                'label' => 'View All Tickets',
                'group' => 'Ticket',
                'description' => 'Can view all tickets',
                'sort_order' => 100,
            ],

            [
                'name' => 'ticket.view.assigned',
                'label' => 'View Assigned Tickets',
                'group' => 'Ticket',
                'description' => 'Can view assigned tickets',
                'sort_order' => 101,
            ],

            [
                'name' => 'ticket.view.own',
                'label' => 'View Own Tickets',
                'group' => 'Ticket',
                'description' => 'Can view own tickets',
                'sort_order' => 102,
            ],

            [
                'name' => 'ticket.create',
                'label' => 'Create Ticket',
                'group' => 'Ticket',
                'description' => 'Can create ticket',
                'sort_order' => 103,
            ],

            [
                'name' => 'ticket.update',
                'label' => 'Update Ticket',
                'group' => 'Ticket',
                'description' => 'Can update ticket',
                'sort_order' => 104,
            ],

            [
                'name' => 'ticket.delete',
                'label' => 'Delete Ticket',
                'group' => 'Ticket',
                'description' => 'Can delete ticket',
                'sort_order' => 105,
            ],

            [
                'name' => 'ticket.close',
                'label' => 'Close Ticket',
                'group' => 'Ticket',
                'description' => 'Can close ticket',
                'sort_order' => 106,
            ],

            [
                'name' => 'ticket.reopen',
                'label' => 'Reopen Ticket',
                'group' => 'Ticket',
                'description' => 'Can reopen ticket',
                'sort_order' => 107,
            ],

            /*
    |--------------------------------------------------------------------------
    | Review
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'ticket.review',
                'label' => 'Review Ticket',
                'group' => 'Review',
                'description' => 'Can review ticket',
                'sort_order' => 200,
            ],

            [
                'name' => 'ticket.approve',
                'label' => 'Approve Ticket',
                'group' => 'Review',
                'description' => 'Can approve ticket',
                'sort_order' => 201,
            ],

            [
                'name' => 'ticket.reject',
                'label' => 'Reject Ticket',
                'group' => 'Review',
                'description' => 'Can reject ticket',
                'sort_order' => 202,
            ],

            /*
    |--------------------------------------------------------------------------
    | Assignment
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'ticket.assign',
                'label' => 'Assign Ticket',
                'group' => 'Assignment',
                'description' => 'Can assign ticket',
                'sort_order' => 300,
            ],

            [
                'name' => 'ticket.reassign',
                'label' => 'Reassign Ticket',
                'group' => 'Assignment',
                'description' => 'Can reassign ticket',
                'sort_order' => 301,
            ],

            /*
    |--------------------------------------------------------------------------
    | Progress
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'progress.view',
                'label' => 'View Progress',
                'group' => 'Progress',
                'description' => 'Can view progress',
                'sort_order' => 400,
            ],

            [
                'name' => 'progress.create',
                'label' => 'Create Progress',
                'group' => 'Progress',
                'description' => 'Can create progress',
                'sort_order' => 401,
            ],

            [
                'name' => 'progress.update',
                'label' => 'Update Progress',
                'group' => 'Progress',
                'description' => 'Can update progress',
                'sort_order' => 402,
            ],

            /*
    |--------------------------------------------------------------------------
    | Estimation
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'estimation.view',
                'label' => 'View Estimation',
                'group' => 'Estimation',
                'description' => 'Can view estimation',
                'sort_order' => 500,
            ],

            [
                'name' => 'estimation.create',
                'label' => 'Create Estimation',
                'group' => 'Estimation',
                'description' => 'Can create estimation',
                'sort_order' => 501,
            ],

            [
                'name' => 'estimation.update',
                'label' => 'Update Estimation',
                'group' => 'Estimation',
                'description' => 'Can update estimation',
                'sort_order' => 502,
            ],

            [
                'name' => 'estimation.approve',
                'label' => 'Approve Estimation',
                'group' => 'Estimation',
                'description' => 'Can approve estimation',
                'sort_order' => 503,
            ],

            [
                'name' => 'estimation.reject',
                'label' => 'Reject Estimation',
                'group' => 'Estimation',
                'description' => 'Can reject estimation',
                'sort_order' => 504,
            ],

            /*
    |--------------------------------------------------------------------------
    | Change Request Approval
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'change.approve',
                'label' => 'Approve Change Request',
                'group' => 'Approval',
                'description' => 'Can approve change request',
                'sort_order' => 600,
            ],

            [
                'name' => 'change.reject',
                'label' => 'Reject Change Request',
                'group' => 'Approval',
                'description' => 'Can reject change request',
                'sort_order' => 601,
            ],

            /*
    |--------------------------------------------------------------------------
    | Attachment
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'attachment.view',
                'label' => 'View Attachment',
                'group' => 'Attachment',
                'description' => 'Can view attachment',
                'sort_order' => 700,
            ],

            [
                'name' => 'attachment.upload',
                'label' => 'Upload Attachment',
                'group' => 'Attachment',
                'description' => 'Can upload attachment',
                'sort_order' => 701,
            ],

            [
                'name' => 'attachment.delete',
                'label' => 'Delete Attachment',
                'group' => 'Attachment',
                'description' => 'Can delete attachment',
                'sort_order' => 702,
            ],

            /*
    |--------------------------------------------------------------------------
    | SLA
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'sla.view',
                'label' => 'View SLA',
                'group' => 'SLA',
                'description' => 'Can view SLA',
                'sort_order' => 800,
            ],

            [
                'name' => 'sla.manage',
                'label' => 'Manage SLA',
                'group' => 'SLA',
                'description' => 'Can manage SLA',
                'sort_order' => 801,
            ],

            /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'notification.view',
                'label' => 'View Notification',
                'group' => 'Notification',
                'description' => 'Can view notification',
                'sort_order' => 900,
            ],

            [
                'name' => 'notification.send',
                'label' => 'Send Notification',
                'group' => 'Notification',
                'description' => 'Can send notification',
                'sort_order' => 901,
            ],

            /*
    |--------------------------------------------------------------------------
    | Reporting
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'report.view',
                'label' => 'View Report',
                'group' => 'Reporting',
                'description' => 'Can view report',
                'sort_order' => 1000,
            ],

            [
                'name' => 'report.export',
                'label' => 'Export Report',
                'group' => 'Reporting',
                'description' => 'Can export report',
                'sort_order' => 1001,
            ],

            /*
    |--------------------------------------------------------------------------
    | Audit Trail
    |--------------------------------------------------------------------------
    */

            [
                'name' => 'audit.view',
                'label' => 'View Audit Trail',
                'group' => 'Audit',
                'description' => 'Can view audit trail',
                'sort_order' => 1100,
            ],
        ];

        foreach ($permissions as $permission) {

            Permission::updateOrCreate(
                [
                    'name' => $permission['name'],
                    'guard_name' => 'web',
                ],
                [
                    'label' => $permission['label'],
                    'group' => $permission['group'],
                    'description' => $permission['description'],
                    'sort_order' => $permission['sort_order'],
                ]
            );
        }
    }
}
