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
        Schema::create('performance_appraisals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('year')->index();
            $table->string('rating', 20);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'year']);
        });

        // Seed data historis untuk karyawan yang sudah ada di database
        $employees = DB::table('employees')->get();
        $now = now();

        foreach ($employees as $emp) {
            $records = [
                [
                    'employee_id' => $emp->id,
                    'year' => 2024,
                    'rating' => $emp->performance_fy24 ?: 'B+',
                    'notes' => $emp->performance_notes,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'employee_id' => $emp->id,
                    'year' => 2025,
                    'rating' => $emp->performance_fy25 ?: 'A',
                    'notes' => $emp->performance_notes,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'employee_id' => $emp->id,
                    'year' => 2026,
                    'rating' => $emp->performance_fy26 ?: ($emp->performance_current ?: 'A'),
                    'notes' => $emp->performance_notes,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ];

            foreach ($records as $rec) {
                DB::table('performance_appraisals')->insertOrIgnore($rec);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_appraisals');
    }
};
