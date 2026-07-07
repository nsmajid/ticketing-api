<?php

namespace App\Attachment\Services;

use App\Models\Attachment;
use App\Models\AttachmentUsage;
use App\Shared\Enums\Attachment\AttachmentOwnerType;
use App\Shared\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttachmentUsageService extends BaseService
{
    /**
     * Synchronize attachment usages.
     */
    public function sync(
        AttachmentOwnerType $ownerType,
        int $ownerId,
        Collection $attachmentIds
    ): void {

        $this->transaction(function () use (

            $ownerType,

            $ownerId,

            $attachmentIds

        ) {

            /*
            |--------------------------------------------------------------------------
            | Existing Usage
            |--------------------------------------------------------------------------
            */

            $existing = $this->baseQuery()

                ->where(
                    'owner_type',
                    $ownerType->value
                )

                ->where(
                    'owner_id',
                    $ownerId
                )

                ->pluck(
                    'attachment_id'
                );

            /*
            |--------------------------------------------------------------------------
            | Delete Removed Usage
            |--------------------------------------------------------------------------
            */

            $removeIds = $existing

                ->diff(
                    $attachmentIds
                );

            if ($removeIds->isNotEmpty()) {

                $this->baseQuery()

                    ->where(
                        'owner_type',
                        $ownerType->value
                    )

                    ->where(
                        'owner_id',
                        $ownerId
                    )

                    ->whereIn(
                        'attachment_id',
                        $removeIds
                    )

                    ->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Insert New Usage
            |--------------------------------------------------------------------------
            */

            $newIds = $attachmentIds

                ->diff(
                    $existing
                );

            foreach ($newIds as $attachmentId) {

                AttachmentUsage::create([

                    'attachment_id' => $attachmentId,

                    'owner_type' => $ownerType->value,

                    'owner_id' => $ownerId,

                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Mark Attachment Permanent
        |--------------------------------------------------------------------------
        */

            $this->markPermanent(
                $attachmentIds
            );
        });
    }

    /**
     * Delete owner usages.
     */
    public function deleteOwner(
        AttachmentOwnerType $ownerType,
        int $ownerId
    ): void {

        $this->transaction(function () use (

            $ownerType,

            $ownerId

        ) {

            $this->baseQuery()

                ->where(
                    'owner_type',
                    $ownerType->value
                )

                ->where(
                    'owner_id',
                    $ownerId
                )

                ->delete();
        });
    }

    /**
     * Base Query.
     */
    private function baseQuery(): Builder
    {
        return AttachmentUsage::query();
    }

    /**
     * Mark attachments as permanent.
     */
    private function markPermanent(
        Collection $attachmentIds
    ): void {

        if ($attachmentIds->isEmpty()) {
            return;
        }

        Attachment::query()

            ->whereIn(
                'id',
                $attachmentIds
            )

            ->update([

                'is_temporary' => false,

                'expired_at' => null,

            ]);
    }
}
