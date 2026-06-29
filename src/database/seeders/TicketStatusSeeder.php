<?php

namespace Database\Seeders;

use App\Models\TicketStatus;
use App\Shared\Enums\Ticket\TicketStatusCode;
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
                'name' => TicketStatusCode::Draft->label(),
                'code' => TicketStatusCode::Draft->value,
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
                'name' => TicketStatusCode::Submitted->label(),
                'code' => TicketStatusCode::Submitted->value,
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
                'name' => TicketStatusCode::Reviewed->label(),
                'code' => TicketStatusCode::Reviewed->value,
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
                'name' => TicketStatusCode::Assigned->label(),
                'code' => TicketStatusCode::Assigned->value,
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
                'name' => TicketStatusCode::InProgress->label(),
                'code' => TicketStatusCode::InProgress->value,
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
                'name' => TicketStatusCode::PendingClient->label(),
                'code' => TicketStatusCode::PendingClient->value,
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
                'name' => TicketStatusCode::PendingVendor->label(),
                'code' => TicketStatusCode::PendingVendor->value,
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
                'name' => TicketStatusCode::Resolved->label(),
                'code' => TicketStatusCode::Resolved->value,
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
                'name' => TicketStatusCode::Closed->label(),
                'code' => TicketStatusCode::Closed->value,
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
                'name' => TicketStatusCode::Rejected->label(),
                'code' => TicketStatusCode::Rejected->value,
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
                'name' => TicketStatusCode::Cancelled->label(),
                'code' => TicketStatusCode::Cancelled->value,
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
