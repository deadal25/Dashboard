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
            $table->integer('flying_risk_score')->nullable()->default(1);
            $table->string('flying_risk_career_growth')->nullable()->default('Clear advancement opportunities');
            $table->integer('flying_risk_career_growth_pts')->nullable()->default(0);
            $table->string('flying_risk_job_market')->nullable()->default('Moderate demand');
            $table->integer('flying_risk_job_market_pts')->nullable()->default(1);
            $table->string('flying_risk_compensation')->nullable()->default('Above industry standard');
            $table->integer('flying_risk_compensation_pts')->nullable()->default(0);
            $table->text('flying_risk_interpretation')->nullable()->default('Employee is highly likely to stay. Minimal intervention needed.');
        });

        Schema::table('talent_assessments', function (Blueprint $table) {
            $table->decimal('b1_vision_business', 4, 1)->nullable()->default(4.0);
            $table->decimal('b2_customer_focus', 4, 1)->nullable()->default(4.0);
            $table->decimal('b3_interpersonal_skill', 4, 1)->nullable()->default(4.0);
            $table->decimal('b4_analysis_judgment', 4, 1)->nullable()->default(3.0);
            $table->decimal('b5_planning_driving', 4, 1)->nullable()->default(3.0);
            $table->decimal('b6_leading_motivating', 4, 1)->nullable()->default(4.0);
            $table->decimal('b7_teamwork', 4, 1)->nullable()->default(4.0);
            $table->decimal('b8_drive_courage_integrity', 4, 1)->nullable()->default(4.0);
            $table->decimal('weighted_score', 4, 2)->nullable()->default(3.70);
            $table->decimal('score_percentage', 5, 2)->nullable()->default(74.00);
            $table->string('kolom_hav', 10)->nullable()->default('C3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'flying_risk_score',
                'flying_risk_career_growth',
                'flying_risk_career_growth_pts',
                'flying_risk_job_market',
                'flying_risk_job_market_pts',
                'flying_risk_compensation',
                'flying_risk_compensation_pts',
                'flying_risk_interpretation',
            ]);
        });

        Schema::table('talent_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'b1_vision_business',
                'b2_customer_focus',
                'b3_interpersonal_skill',
                'b4_analysis_judgment',
                'b5_planning_driving',
                'b6_leading_motivating',
                'b7_teamwork',
                'b8_drive_courage_integrity',
                'weighted_score',
                'score_percentage',
                'kolom_hav',
            ]);
        });
    }
};
