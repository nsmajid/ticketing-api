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
        Schema::create('ticket_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Assignment
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assigned_to')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('assigned_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('assigned_at');

            /*
            |--------------------------------------------------------------------------
            | Reassignment
            |--------------------------------------------------------------------------
            */

            $table->text('assignment_notes')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_assignments');
    }
};
