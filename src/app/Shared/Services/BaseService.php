<?php

namespace App\Shared\Services;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Shared\Traits\EnvironmentProtection;

abstract class BaseService
{
    use EnvironmentProtection;

    /**
     * Execute database transaction.
     */
    protected function transaction(
        Closure $callback
    ): mixed {

        return DB::transaction($callback);

    }

    /**
     * Delete model (Development Only).
     */
    protected function deleteModel(
        Model $model
    ): void {

        $this->ensureDevelopmentEnvironment();

        $model->delete();

    }
}