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
        Schema::table('tickets', function (Blueprint $table) {

            $table->foreignId('reviewed_by')
                ->nullable()
                ->after('requester_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable()
                ->after('submitted_at');

            $table->text('review_notes')
                ->nullable()
                ->after('description');
            $table->foreignId('closed_by')
                ->nullable()
                ->after('review_notes')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('closed_at')
                ->nullable()
                ->after('closed_by');

            $table->text('close_notes')
                ->nullable()
                ->after('closed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropForeign(['closed_by']);

            $table->dropColumn([

                'reviewed_at',

                'review_notes',
                
                'closed_by',

                'closed_at',
                
                'close_notes',

            ]);
        });
    }
};
