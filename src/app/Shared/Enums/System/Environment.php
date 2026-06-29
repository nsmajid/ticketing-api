<?php

namespace App\Shared\Enums\System;

enum Environment: string
{
    case Local = 'local';
    case Development = 'development';
    case Staging = 'staging';
    case Production = 'production';
}