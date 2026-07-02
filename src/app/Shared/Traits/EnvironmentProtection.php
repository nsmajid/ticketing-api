<?php

namespace App\Shared\Traits;

use App\Shared\Enums\System\Environment;


trait EnvironmentProtection
{
    protected function ensureDevelopmentEnvironment(): void
    {
        abort_unless(
            app()->environment([
                Environment::Local->value,
                Environment::Development->value,
            ]),
            403,
            'This action is not allowed in environment.'
        );
    }

    protected function ensureNotProduction(): void
    {
        abort_if(
            app()->environment(Environment::Production->value),
            403,
            'This action is not allowed in environment.'
        );
    }
}
