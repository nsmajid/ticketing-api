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
        Schema::create('tickets', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Ticket Information
            |--------------------------------------------------------------------------
            */

            $table->string('ticket_number')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Requester
            |--------------------------------------------------------------------------
            */

            $table->foreignId('requester_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Master Data
            |--------------------------------------------------------------------------
            */


            $table->foreignId('application_feature_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('ticket_category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('ticket_priority_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('ticket_status_id')
                ->constrained()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $table->string('contact_name');

            $table->string('contact_phone');

            /*
            |--------------------------------------------------------------------------
            | Ticket
            |--------------------------------------------------------------------------
            */

            $table->string('subject');

            $table->longText('description');

            /*
            |--------------------------------------------------------------------------
            | SLA
            |--------------------------------------------------------------------------
            */

            $table->timestamp('response_due_at')
                ->nullable();

            $table->timestamp('resolution_due_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->timestamp('submitted_at')->nullable();

    

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('ticket_number');

            $table->index('requester_id');

            $table->index('application_feature_id');

            $table->index('ticket_category_id');

            $table->index('ticket_priority_id');

            $table->index('ticket_status_id');

            $table->index('submitted_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
