<?php

namespace App\Sla\Services;

use App\Models\SlaRule;
use App\Shared\Traits\EnvironmentProtection;
use Illuminate\Support\Facades\DB;

class SlaRuleService
{
    use EnvironmentProtection;

    public function create(
        array $data
    ): SlaRule {
        return DB::transaction(function () use ($data) {

            return SlaRule::create(
                $data
            );
        });
    }

    public function update(
        SlaRule $slaRule,
        array $data
    ): SlaRule {

        return DB::transaction(function () use ($slaRule, $data) {

            $slaRule->update(
                $data
            );

            return $slaRule->fresh();
        });
    }

    public function delete(
        SlaRule $slaRule
    ): void {

        $this->ensureDevelopmentEnvironment();

        $slaRule->delete();

        
    }
}
