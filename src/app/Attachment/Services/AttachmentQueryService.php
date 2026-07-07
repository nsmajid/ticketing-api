<?php

namespace App\Attachment\Services;

use App\Models\Attachment;
use App\Models\AttachmentUsage;
use App\Shared\Enums\Attachment\AttachmentOwnerType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttachmentQueryService
{
    /**
     * Get attachments by owner.
     *
     * @return Collection<int, Attachment>
     */
    public function attachments(
        AttachmentOwnerType $ownerType,
        int $ownerId
    ): Collection {

        return Attachment::query()

            ->select('attachments.*')
            ->join(
                'attachment_usages',
                'attachments.id',
                '=',
                'attachment_usages.attachment_id'
            )
            ->where(
                'attachment_usages.owner_type',
                $ownerType
            )
            ->where(
                'attachment_usages.owner_id',
                $ownerId
            )
            ->orderBy('attachments.id')
            ->get();
    }

    /**
     * Get attachment ids by owner.
     *
     * @return Collection<int,int>
     */
    public function attachmentIds(
        AttachmentOwnerType $ownerType,
        int $ownerId
    ): Collection {

        return $this->baseQuery()

            ->where(
                'owner_type',
                $ownerType
            )

            ->where(
                'owner_id',
                $ownerId
            )

            ->pluck(
                'attachment_id'
            );
    }

    /**
     * Base Query.
     */
    private function baseQuery(): Builder
    {
        return AttachmentUsage::query();
    }
}
