<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('career_histories', function (Blueprint $table) {
            $table->string('job_class')->nullable()->after('position');
            $table->string('grade')->nullable()->after('job_class');
        });

        // Backfill data from existing job_class_grade
        $histories = DB::table('career_histories')->get();
        foreach ($histories as $history) {
            if (!empty($history->job_class_grade)) {
                $parts = explode('/', $history->job_class_grade);
                $jc = trim($parts[0] ?? '');
                $gr = trim($parts[1] ?? '');
                DB::table('career_histories')
                    ->where('id', $history->id)
                    ->update([
                        'job_class' => $jc ?: null,
                        'grade' => $gr ?: null,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_histories', function (Blueprint $table) {
            $table->dropColumn(['job_class', 'grade']);
        });
    }
};
