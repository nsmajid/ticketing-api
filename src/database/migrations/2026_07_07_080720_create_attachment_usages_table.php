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
        Schema::create('attachment_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attachment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('owner_type', 50);

            $table->unsignedBigInteger('owner_id');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('attachment_id');

            $table->index([
                'owner_type',
                'owner_id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Usage
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'attachment_id',
                'owner_type',
                'owner_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachment_usages');
    }
};
