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
        Schema::table('employees', function (Blueprint $table) {
            // C1 Performance 3 Tahun
            $table->string('performance_fy24')->nullable()->default('B+');
            $table->string('performance_fy25')->nullable()->default('A');
            $table->string('performance_fy26')->nullable()->default('A');
            $table->text('performance_notes')->nullable();

            // C2 Potential Assessment - POTASS Detail
            $table->string('potass_score_prev')->nullable()->default('94%');
            $table->string('potass_period_prev')->nullable()->default('Aug-24');
            $table->string('potass_position_prev')->nullable()->default('Section Head');
            $table->string('potass_category_prev')->nullable()->default('Average');
            $table->string('potass_assessor_prev')->nullable()->default('HR Development');

            $table->string('potass_period_last')->nullable()->default('Aug-26');
            $table->string('potass_position_last')->nullable()->default('Manager');
            $table->string('potass_category_last')->nullable()->default('High');
            $table->string('potass_assessor_last')->nullable()->default('HR Development');

            // C3 HAV 16 Box
            $table->string('hav_box_category')->nullable()->default('High Performance / High Potential');

            // Development Gap Target & Method
            $table->string('target_position_gap')->nullable()->default('Engineering Manager (JC5 / G5-1)');
            $table->string('target_department_gap')->nullable()->default('Manufacturing Engineering');
            $table->string('gap_method')->nullable()->default('Perbandingan Kompetensi: Posisi Saat Ini vs Posisi Target (Engineering Manager)');

            // IDP Summary Fields
            $table->string('idp_readiness_desc')->nullable()->default('Siap dalam 1–3 Tahun');
            $table->text('idp_primary_goal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'performance_fy24',
                'performance_fy25',
                'performance_fy26',
                'performance_notes',
                'potass_score_prev',
                'potass_period_prev',
                'potass_position_prev',
                'potass_category_prev',
                'potass_assessor_prev',
                'potass_period_last',
                'potass_position_last',
                'potass_category_last',
                'potass_assessor_last',
                'hav_box_category',
                'target_position_gap',
                'target_department_gap',
                'gap_method',
                'idp_readiness_desc',
                'idp_primary_goal',
            ]);
        });
    }
};
