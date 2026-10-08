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
        // 1. Add competency_type to development_reviews
        if (!Schema::hasColumn('development_reviews', 'competency_type')) {
            Schema::table('development_reviews', function (Blueprint $table) {
                $table->string('competency_type', 50)->default('Manajerial')->nullable()->after('competency');
            });
        }

        DB::table('development_reviews')
            ->whereNull('competency_type')
            ->orWhere('competency_type', '')
            ->update(['competency_type' => 'Manajerial']);

        // 2. Add feedback and recommendation fields to employees
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'review_feedback_text')) {
                $table->text('review_feedback_text')->nullable();
            }
            if (!Schema::hasColumn('employees', 'review_reviewer_name')) {
                $table->string('review_reviewer_name', 150)->nullable()->default('Andi Wijaya');
            }
            if (!Schema::hasColumn('employees', 'review_reviewer_title')) {
                $table->string('review_reviewer_title', 150)->nullable()->default('Engineering Division Head');
            }
            if (!Schema::hasColumn('employees', 'review_date')) {
                $table->string('review_date', 50)->nullable()->default('20 Mei 2027');
            }
            if (!Schema::hasColumn('employees', 'review_recommendations')) {
                $table->text('review_recommendations')->nullable();
            }
        });

        // Set default values for employee Budi Santoso (012345)
        $defaultFeedback = 'Budi Santosoo menunjukkan perkembangan yang baik selama periode ini. Terlihat peningkatan dalam kepemimpinan, komunikasi, dan kemampuan eksekusi. Fokus selanjutnya adalah memperkuat kemampuan analisis strategis dan pengambilan keputusan berbasis data untuk siap menempati posisi Engineering Manager saat penugasan berikutnya.';
        $defaultRecs = "Lanjutkan program pengembangan sesuai IDP dengan fokus pada Analysis & Judgement.\nBerikan kesempatan memimpin proyek strategis yang berdampak lintas departemen.\nCoaching/mentoring dengan Engineering Manager untuk mempercepat kesiapan.\nReview berikutnya dilakukan pada Mei 2028.";

        DB::table('employees')->whereNull('review_feedback_text')->update([
            'review_feedback_text' => $defaultFeedback,
            'review_reviewer_name' => 'Andi Wijaya',
            'review_reviewer_title' => 'Engineering Division Head',
            'review_date' => '20 Mei 2027',
            'review_recommendations' => $defaultRecs,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('development_reviews', 'competency_type')) {
            Schema::table('development_reviews', function (Blueprint $table) {
                $table->dropColumn('competency_type');
            });
        }

        Schema::table('employees', function (Blueprint $table) {
            $cols = [
                'review_feedback_text',
                'review_reviewer_name',
                'review_reviewer_title',
                'review_date',
                'review_recommendations'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('employees', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
