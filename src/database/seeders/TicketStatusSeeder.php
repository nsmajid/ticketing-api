<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [

            [
                'name' => 'Draft',
                'code' => 'draft',
                'description' => 'Ticket masih dalam proses pembuatan dan belum dikirim.',
                'color' => '#6B7280',
                'icon' => 'file',
                'is_initial' => true,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],

            [
                'name' => 'Submitted',
                'code' => 'submitted',
                'description' => 'Ticket telah dikirim oleh pengguna.',
                'color' => '#3B82F6',
                'icon' => 'send',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],

            [
                'name' => 'Reviewed',
                'code' => 'reviewed',
                'description' => 'Ticket telah direview oleh petugas.',
                'color' => '#0EA5E9',
                'icon' => 'search',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],

            [
                'name' => 'Assigned',
                'code' => 'assigned',
                'description' => 'Ticket telah ditugaskan kepada teknisi.',
                'color' => '#8B5CF6',
                'icon' => 'user-check',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],

            [
                'name' => 'In Progress',
                'code' => 'in_progress',
                'description' => 'Pengerjaan ticket sedang berlangsung.',
                'color' => '#F59E0B',
                'icon' => 'loader',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],

            [
                'name' => 'Pending Client',
                'code' => 'pending_client',
                'description' => 'Menunggu respon atau konfirmasi dari client.',
                'color' => '#F97316',
                'icon' => 'clock',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],

            [
                'name' => 'Pending Vendor',
                'code' => 'pending_vendor',
                'description' => 'Menunggu tindak lanjut dari vendor.',
                'color' => '#FB923C',
                'icon' => 'building',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 7,
            ],

            [
                'name' => 'Resolved',
                'code' => 'resolved',
                'description' => 'Permasalahan telah diselesaikan.',
                'color' => '#22C55E',
                'icon' => 'check-circle',
                'is_initial' => false,
                'is_closed' => false,
                'is_resolved' => true,
                'is_active' => true,
                'sort_order' => 8,
            ],

            [
                'name' => 'Closed',
                'code' => 'closed',
                'description' => 'Ticket telah ditutup.',
                'color' => '#16A34A',
                'icon' => 'lock',
                'is_initial' => false,
                'is_closed' => true,
                'is_resolved' => true,
                'is_active' => true,
                'sort_order' => 9,
            ],

            [
                'name' => 'Rejected',
                'code' => 'rejected',
                'description' => 'Ticket ditolak.',
                'color' => '#DC2626',
                'icon' => 'x-circle',
                'is_initial' => false,
                'is_closed' => true,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 10,
            ],

            [
                'name' => 'Cancelled',
                'code' => 'cancelled',
                'description' => 'Ticket dibatalkan oleh pengguna atau sistem.',
                'color' => '#991B1B',
                'icon' => 'ban',
                'is_initial' => false,
                'is_closed' => true,
                'is_resolved' => false,
                'is_active' => true,
                'sort_order' => 11,
            ],

        ];

        foreach ($statuses as $status) {

            TicketStatus::updateOrCreate(
                [
                    'code' => $status['code'],
                ],
                $status
            );
        }
    }
}
