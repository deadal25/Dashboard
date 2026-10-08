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
        Schema::table('training_histories', function (Blueprint $table) {
            $table->string('start_date')->nullable()->after('training_date');
            $table->string('end_date')->nullable()->after('start_date');
            $table->string('documentation')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_histories', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'documentation']);
        });
    }
};
