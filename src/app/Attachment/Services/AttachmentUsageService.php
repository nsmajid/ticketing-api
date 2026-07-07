<?php

namespace App\Attachment\Services;

use App\Attachment\Services\AttachmentParserService;
use App\Models\Attachment;
use App\Models\AttachmentUsage;
use App\Shared\Enums\Attachment\AttachmentOwnerType;
use App\Shared\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttachmentUsageService extends BaseService
{
    public function __construct(
        private AttachmentParserService $attachmentParser,

    ) {}

    /**
     * Synchronize attachment usages.
     */
    public function sync(
        AttachmentOwnerType $ownerType,
        int $ownerId,
        ?string $markdown
    ): void {


        $attachmentIds = $this->attachmentParser
            ->attachmentIds($markdown);

        $attachments = Attachment::query()
            ->whereIn(
                'id',
                $attachmentIds
            )
            ->get();

        $this->validateAttachments(
            $attachments,
            $attachmentIds
        );

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

    /**
     * Validate attachments before creating usages.
     *
     * @param Collection<int, Attachment> $attachments
     * @param Collection<int, int> $attachmentIds
     */
    private function validateAttachments(
        Collection $attachments,
        Collection $attachmentIds,
    ): void {

        /*
    |--------------------------------------------------------------------------
    | Attachment Not Found
    |--------------------------------------------------------------------------
    */

        if ($attachments->count() !== $attachmentIds->count()) {

            abort(
                422,
                'One or more attachments were not found.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Validate Each Attachment
    |--------------------------------------------------------------------------
    */

        foreach ($attachments as $attachment) {

            /*
        |--------------------------------------------------------------------------
        | Expired Attachment
        |--------------------------------------------------------------------------
        */

            if (
                $attachment->expired_at &&
                $attachment->expired_at->isPast()
            ) {

                abort(
                    422,
                    "Attachment '{$attachment->original_filename}' has expired."
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Deleted Attachment
        |--------------------------------------------------------------------------
        */

            if ($attachment->deleted_at) {

                abort(
                    422,
                    "Attachment '{$attachment->original_filename}' is no longer available."
                );
            }
        }
    }
}
