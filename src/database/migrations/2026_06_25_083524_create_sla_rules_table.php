<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sla_rules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ticket_category_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->foreignId('ticket_priority_id')
                ->constrained()
                ->cascadeOnUpdate();

            $table->integer('response_hours');

            $table->integer('resolution_hours');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'ticket_category_id',
                'ticket_priority_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_rules');
    }
};
