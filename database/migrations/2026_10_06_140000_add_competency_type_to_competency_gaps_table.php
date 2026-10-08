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
        if (!Schema::hasColumn('competency_gaps', 'competency_type')) {
            Schema::table('competency_gaps', function (Blueprint $table) {
                $table->string('competency_type', 50)->default('Manajerial')->after('competency');
            });
        }

        // Set semua data gap yang sudah ada menjadi 'Manajerial'
        DB::table('competency_gaps')->whereNull('competency_type')->orWhere('competency_type', '')->update([
            'competency_type' => 'Manajerial',
        ]);

        // Tambahkan technical competency gaps untuk karyawan Budi Santoso (NIK: 012345) jika belum ada
        $budi = DB::table('employees')->where('nik', '012345')->first();
        if ($budi) {
            $hasTechnical = DB::table('competency_gaps')
                ->where('employee_id', $budi->id)
                ->where('competency_type', 'Technical')
                ->exists();

            if (!$hasTechnical) {
                $now = now();
                $technicalGaps = [
                    [
                        'employee_id' => $budi->id,
                        'order_no' => 1,
                        'competency' => 'Manufacturing Process & Line Balancing',
                        'competency_type' => 'Technical',
                        'current_level' => 4,
                        'current_desc' => 'Menguasai proses manufaktur komponen & optimasi lini',
                        'standard_level' => 5,
                        'standard_desc' => 'Menetapkan standar arsitektur teknologi manufaktur advance',
                        'gap' => 1,
                        'gap_severity' => 'Sedang',
                        'expected_improvement' => 'Menguasai perancangan lini terintegrasi & optimasi siklus produksi advance',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'employee_id' => $budi->id,
                        'order_no' => 2,
                        'competency' => 'Smart Manufacturing & Automation (Industry 4.0)',
                        'competency_type' => 'Technical',
                        'current_level' => 3,
                        'current_desc' => 'Memahami dasar PLC dan sensor lini produksi',
                        'standard_level' => 5,
                        'standard_desc' => 'Merancang roadmap smart factory, IoT, dan robotika industri',
                        'gap' => 2,
                        'gap_severity' => 'Tinggi',
                        'expected_improvement' => 'Meningkatkan penguasaan integrasi SCADA, predictive maintenance & otomatisasi',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'employee_id' => $budi->id,
                        'order_no' => 3,
                        'competency' => 'Automotive Quality Standards & IATF 16949',
                        'competency_type' => 'Technical',
                        'current_level' => 4,
                        'current_desc' => 'Menerapkan standar kontrol kualitas & analisis cacat',
                        'standard_level' => 5,
                        'standard_desc' => 'Membangun sistem pencegahan cacat zero defect berstandar global',
                        'gap' => 1,
                        'gap_severity' => 'Sedang',
                        'expected_improvement' => 'Menguasai audit komprehensif sistem mutu dan failure mode avoidance',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                    [
                        'employee_id' => $budi->id,
                        'order_no' => 4,
                        'competency' => 'Lean Manufacturing & Kaizen Engineering',
                        'competency_type' => 'Technical',
                        'current_level' => 4,
                        'current_desc' => 'Menerapkan Kaizen rutin dan eliminasi waste di tempat kerja',
                        'standard_level' => 4,
                        'standard_desc' => 'Memelihara dan memfasilitasi continuous improvement divisi',
                        'gap' => 0,
                        'gap_severity' => 'Rendah',
                        'expected_improvement' => 'Mempertahankan efisiensi lini dan membimbing tim dalam proyek efisiensi kerja',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ];

                foreach ($technicalGaps as $tg) {
                    DB::table('competency_gaps')->insert($tg);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('competency_gaps', 'competency_type')) {
            Schema::table('competency_gaps', function (Blueprint $table) {
                $table->dropColumn('competency_type');
            });
        }
    }
};
