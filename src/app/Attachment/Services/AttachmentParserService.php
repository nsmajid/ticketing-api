<?php

namespace App\Attachment\Services;

use App\Models\Attachment;
use Illuminate\Support\Collection;

class AttachmentParserService
{
    /**
     * Attachment URI Pattern.
     *
     * attachment://01KWVBK2CXBAF55ES4ZAEQZE6Q
     */
    private const PATTERN = '/attachment:\/\/([A-Z0-9]{26})/';

    /**
     * Determine markdown contains attachment.
     */
    public function contains(
        string $markdown
    ): bool {

        return preg_match(
            self::PATTERN,
            $markdown
        ) === 1;
    }

    /**
     * Extract attachment ULIDs.
     *
     * @return Collection<int,string>
     */
    public function extractUlids(
        string $markdown
    ): Collection {

        preg_match_all(
            self::PATTERN,
            $markdown,
            $matches
        );

        return collect(
            $matches[1] ?? []
        )

            ->filter()

            ->unique()

            ->values();
    }

    /**
     * Extract attachments from markdown.
     *
     * @return Collection<int,Attachment>
     */
    public function extract(
        string $markdown
    ): Collection {

        $ulids = $this->extractUlids(
            $markdown
        );

        if ($ulids->isEmpty()) {

            return collect();
        }

        $attachments = Attachment::query()

            ->whereIn(
                'ulid',
                $ulids
            )

            ->get()

            ->keyBy(
                'ulid'
            );

        /*
        |--------------------------------------------------------------------------
        | Validate Missing Attachment
        |--------------------------------------------------------------------------
        */

        $missing = $ulids->diff(
            $attachments->keys()
        );

        if ($missing->isNotEmpty()) {

            abort(
                422,
                'Attachment not found.'
            );
        }

        return $ulids

            ->map(
                fn (string $ulid) => $attachments[$ulid]
            )

            ->values();
    }

    /**
     * Convert attachments to attachment ids.
     *
     * @return Collection<int,int>
     */
    public function attachmentIds(
        string $markdown
    ): Collection {

        return $this->extract(
            $markdown
        )

            ->pluck(
                'id'
            )

            ->values();
    }
}