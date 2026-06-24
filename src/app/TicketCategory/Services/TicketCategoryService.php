<?php

namespace App\TicketCategory\Services;

use App\Models\TicketCategory;
use App\Shared\Traits\EnvironmentProtection;

class TicketCategoryService
{
    use EnvironmentProtection;

    public function create(
        array $data
    ): TicketCategory {

        return TicketCategory::create(
            $data
        );
    }

    public function update(
        TicketCategory $category,
        array $data
    ): TicketCategory {

        $category->update(
            $data
        );

        return $category->fresh();
    }

    public function delete(
        TicketCategory $category
    ): void {
        
        $this->ensureDevelopmentEnvironment();

        $category->delete();
    }
}
