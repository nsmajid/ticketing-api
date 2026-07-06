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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Public Identifier
            |--------------------------------------------------------------------------
            */

            $table->ulid('ulid')->unique();

            /*
            |--------------------------------------------------------------------------
            | Storage
            |--------------------------------------------------------------------------
            */


            $table->string('directory', 255);

            $table->string('path', 500);

            /*
            |--------------------------------------------------------------------------
            | File Information
            |--------------------------------------------------------------------------
            */

            $table->string('filename', 255);

            $table->string('original_filename', 255);

            $table->string('extension', 10);

            $table->string('mime_type', 100);

            $table->unsignedBigInteger('size');

            $table->string('checksum', 64)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Upload Information
            |--------------------------------------------------------------------------
            */

            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Temporary Upload
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_temporary')
                ->default(true);

            $table->timestamp('expired_at')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('uploaded_by');

            $table->index('is_temporary');

            $table->index('expired_at');

            $table->index([
                'directory'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
