<?php

namespace App\Shared\Enums\Attachment;

enum AttachmentField: string
{
    case Description = 'DESCRIPTION';

    case Comment = 'COMMENT';

    case ReviewNotes = 'REVIEW_NOTES';

    case ProgressNotes = 'PROGRESS_NOTES';

    case ResolutionNotes = 'RESOLUTION_NOTES';

    case CloseNotes = 'CLOSE_NOTES';
}