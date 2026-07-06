<?php

return [

    'disk' => env(
        'FILESYSTEM_DISK',
        'local'
    ),

    'max_size' => env(
        'ATTACHMENT_MAX_SIZE',
        20480
    ),

    'temporary_hours' => (int) env(
        'ATTACHMENT_TEMPORARY_HOURS',
        24
    ),

    'allowed_extensions' => [

        'jpg',

        'jpeg',

        'png',

        'gif',

        'webp',

        'pdf',

        'doc',

        'docx',

        'xls',

        'xlsx',

        'csv',

        'zip',

        'rar',

        'txt',

        'log',

    ],

    'allowed_mime_types' => [

        'image/jpeg',

        'image/png',

        'image/gif',

        'image/webp',

        'application/pdf',

        'application/msword',

        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

        'application/vnd.ms-excel',

        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

        'text/plain',

        'text/csv',

        'application/zip',

        'application/x-rar-compressed',

    ],

];