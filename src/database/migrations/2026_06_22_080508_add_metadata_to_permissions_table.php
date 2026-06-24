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
        Schema::table('permissions', function (Blueprint $table) {

            $table->string('label')
                ->nullable()
                ->after('name');

            $table->string('group')
                ->nullable()
                ->after('label');

            $table->text('description')
                ->nullable()
                ->after('group');
            $table->integer('sort_order')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn([
                'label',
                'group',
                'description',
                'sort_order'
            ]);
        });
    }
};
