<?php

namespace App\Attachment\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class AttachmentNotFoundException extends Exception
{
    /**
     * Create exception from missing ULIDs.
     */
    public static function fromUlids(
        Collection $ulids
    ): self {

        return new self(

            'Attachment not found. Missing ULID(s): ' .
            $ulids->implode(', ')

        );
    }
}