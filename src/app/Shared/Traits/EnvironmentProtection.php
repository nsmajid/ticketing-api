<?php

namespace App\Shared\Traits;

trait EnvironmentProtection
{
    protected function ensureDevelopmentEnvironment(): void
    {
        abort_unless(
            app()->environment([
                'local',
                'development',
            ]),
            403,
            'This action is not allowed in environment.'
        );
    }

     protected function ensureNotProduction(): void
    {
        abort_if(
            app()->environment('production'),
            403,
            'This action is not allowed in environment.'
        );
    }
}