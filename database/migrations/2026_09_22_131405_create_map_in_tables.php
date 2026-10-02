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
        // 1. Employees Table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 50)->unique()->index();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('position');
            $table->string('department');
            $table->string('section')->nullable();
            $table->integer('age')->default(35);
            $table->integer('tenure_years')->default(5);
            $table->string('education')->nullable();
            $table->string('current_job_class')->default('JC4');
            $table->string('current_grade')->default('G4-1');
            $table->string('grade_since')->nullable();
            $table->string('position_since')->nullable();
            
            // Ringkasan Profil Badges & Stats
            $table->string('performance_current')->default('A');
            $table->string('potass_current')->default('100%');
            $table->string('hav_box_current')->default('Box 15');
            $table->string('talent_pool_status')->default('YA');
            $table->string('flying_risk')->default('MEDIUM');
            $table->string('flying_risk_reason')->nullable();
            $table->string('next_possible_position')->nullable();
            $table->string('career_projection')->nullable();
            $table->string('readiness_level')->nullable();
            $table->string('retirement_year')->nullable();
            $table->text('profile_notes')->nullable();
            
            $table->timestamps();
        });

        // 2. Career Histories
        Schema::create('career_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('effective_date');
            $table->string('department_section');
            $table->string('position');
            $table->string('job_class_grade');
            $table->string('change_type'); // Kenaikan Pangkat Reguler, Promosi, Rotasi, Mutasi, Demosi, Penempatan Awal
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Talent Assessments (POTASS history & 3-year performance)
        Schema::create('talent_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('assessment_date'); // Aug-26, etc.
            $table->string('position_standard');
            $table->string('potass_score');
            $table->string('category');
            $table->string('assessor')->default('HR Development');
            $table->timestamps();
        });

        // 4. Key Strengths (C4. Kekuatan Utama)
        Schema::create('key_strengths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('strength');
            $table->text('short_description');
            $table->string('source');
            $table->timestamps();
        });

        // 5. Career Plans (D1. Arah Karir)
        Schema::create('career_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('interested_area')->nullable();
            $table->string('recommended_career_path')->default('Managerial');
            $table->string('next_possible_position')->nullable();
            $table->string('next_possible_department')->nullable();
            $table->string('career_projection')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. Job Class & Grade Projections (D2. Rencana Job Class & Grade)
        Schema::create('job_class_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('plan_year');
            $table->integer('projected_age');
            $table->string('job_class');
            $table->string('grade');
            $table->string('change_type');
            $table->string('target_position')->nullable();
            $table->string('target_department')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Succession Positions (E1. Posisi Jabatan untuk Suksesi)
        Schema::create('succession_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('target_position');
            $table->string('department');
            $table->string('position_level');
            $table->text('reason')->nullable();
            $table->string('needed_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('readiness')->nullable();
            $table->integer('ranking')->default(1);
            $table->timestamps();
        });

        // 8. Succession Candidates (E2. Calon Pengganti)
        Schema::create('succession_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('candidate_name');
            $table->string('current_department');
            $table->string('current_position');
            $table->string('readiness'); // Siap Sekarang, 1–2 Tahun, 3–5 Tahun, >5 Tahun, Belum Siap
            $table->string('needed_at')->nullable();
            $table->integer('ranking')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 9. Competency Gaps (Development Gap Module)
        Schema::create('competency_gaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('competency');
            $table->integer('current_level');
            $table->string('current_desc')->nullable();
            $table->integer('standard_level');
            $table->string('standard_desc')->nullable();
            $table->integer('gap');
            $table->string('gap_severity'); // Tinggi, Sedang, Rendah
            $table->text('expected_improvement')->nullable();
            $table->timestamps();
        });

        // 10. IDP Action Plans (Individual Development Plan)
        Schema::create('idp_action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('competency');
            $table->text('specific_goal')->nullable();
            $table->string('development_methods')->nullable(); // Training, On the Job, Coaching, Exposure
            $table->text('activity_program')->nullable();
            $table->string('pic_supporter')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->text('success_indicator')->nullable();
            $table->string('status')->default('On Progress'); // On Progress, Planning, Selesai, Dibatalkan
            $table->integer('progress_percent')->default(0);
            $table->timestamps();
        });

        // 11. Development Reviews (Review Hasil Pengembangan)
        Schema::create('development_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('period')->default('Juni 2026 – Mei 2027');
            $table->string('competency');
            $table->integer('previous_level');
            $table->integer('current_level');
            $table->integer('target_level');
            $table->integer('growth')->default(0);
            $table->string('status')->default('Meningkat'); // Meningkat, Stabil, Belum Meningkat
            $table->text('reviewer_notes')->nullable();
            $table->timestamps();
        });

        // 12. Training Histories (Riwayat Pelatihan)
        Schema::create('training_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('training_date');
            $table->string('training_name');
            $table->string('category')->default('Functional'); // Functional, Managerial
            $table->string('training_type')->default('Classroom'); // Classroom, On the Job, E-Learning
            $table->string('organizer')->nullable();
            $table->integer('duration_hours')->default(8);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 13. Certifications (Sertifikasi Yang Dimiliki)
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->integer('order_no')->default(1);
            $table->string('name');
            $table->string('obtained_date')->nullable();
            $table->string('issuer')->nullable();
            $table->string('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('training_histories');
        Schema::dropIfExists('development_reviews');
        Schema::dropIfExists('idp_action_plans');
        Schema::dropIfExists('competency_gaps');
        Schema::dropIfExists('succession_candidates');
        Schema::dropIfExists('succession_positions');
        Schema::dropIfExists('job_class_plans');
        Schema::dropIfExists('career_plans');
        Schema::dropIfExists('key_strengths');
        Schema::dropIfExists('talent_assessments');
        Schema::dropIfExists('career_histories');
        Schema::dropIfExists('employees');
    }
};
