<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\CareerHistory;
use App\Models\TalentAssessment;
use App\Models\KeyStrength;
use App\Models\CareerPlan;
use App\Models\JobClassPlan;
use App\Models\SuccessionPosition;
use App\Models\SuccessionCandidate;
use App\Models\CompetencyGap;
use App\Models\IdpActionPlan;
use App\Models\DevelopmentReview;
use App\Models\TrainingHistory;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default Users (Super Admin & HR Admin)
        User::updateOrCreate(
            ['email' => 'superadmin@map-in.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'hradmin@map-in.com'],
            [
                'name' => 'HR Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'hr_admin',
            ]
        );

        // Check if employees are already seeded
        if (Employee::count() === 0) {
            // 1. Primary Mockup Employee: Budi Santoso (NIK: 012345)
        $budi = Employee::create([
            'nik' => '012345',
            'name' => 'Budi Santoso',
            'avatar' => '/images/avatar-budi.png',
            'position' => 'Section Head – Manufacturing Engineering',
            'department' => 'Manufacturing Engineering',
            'section' => 'Process Engineering',
            'age' => 41,
            'tenure_years' => 17,
            'education' => 'S1 Teknik Mesin',
            'current_job_class' => 'JC4',
            'current_grade' => 'G4-2',
            'grade_since' => 'April 2026',
            'position_since' => 'April 2023',
            'performance_current' => 'A',
            'potass_current' => '106% (High)',
            'hav_box_current' => 'Box 15',
            'talent_pool_status' => 'YA',
            'flying_risk' => 'MEDIUM',
            'flying_risk_reason' => 'Career Progression',
            'next_possible_position' => 'Engineering Manager',
            'career_projection' => 'Engineering Division Head',
            'readiness_level' => '68%',
            'retirement_year' => '2044',
            'profile_notes' => 'Catatan: Proyeksi karir dan rencana pengembangan dapat berubah sesuai hasil talent review berikutnya.',
        ]);

        // Career Histories
        $careerHistories = [
            [
                'order_no' => 1,
                'effective_date' => 'Apr-26',
                'department_section' => 'Manufacturing Engineering / Process Eng.',
                'position' => 'Section Head',
                'job_class_grade' => 'JC4 / G4-2',
                'change_type' => 'Kenaikan Pangkat Reguler',
                'notes' => '-',
            ],
            [
                'order_no' => 2,
                'effective_date' => 'Apr-23',
                'department_section' => 'Manufacturing Engineering / Process Eng.',
                'position' => 'Section Head',
                'job_class_grade' => 'JC4 / G4-1',
                'change_type' => 'Promosi',
                'notes' => 'Supervisor → Section Head',
            ],
            [
                'order_no' => 3,
                'effective_date' => 'Apr-20',
                'department_section' => 'Manufacturing Engineering / Process Eng.',
                'position' => 'Supervisor',
                'job_class_grade' => 'JC3 / G3-2',
                'change_type' => 'Kenaikan Pangkat Reguler',
                'notes' => '-',
            ],
            [
                'order_no' => 4,
                'effective_date' => 'Apr-18',
                'department_section' => 'Manufacturing Engineering / Process Eng.',
                'position' => 'Supervisor',
                'job_class_grade' => 'JC3 / G3-1',
                'change_type' => 'Promosi',
                'notes' => 'Leader → Supervisor',
            ],
            [
                'order_no' => 5,
                'effective_date' => 'Apr-15',
                'department_section' => 'Manufacturing Engineering / Process Eng.',
                'position' => 'Leader',
                'job_class_grade' => 'JC2 / G2-2',
                'change_type' => 'Rotasi',
                'notes' => 'Production → Engineering',
            ],
            [
                'order_no' => 6,
                'effective_date' => 'Apr-09',
                'department_section' => 'Production / Machining',
                'position' => 'Leader',
                'job_class_grade' => 'JC2 / G2-1',
                'change_type' => 'Promosi',
                'notes' => 'Operator → Leader',
            ],
        ];
        foreach ($careerHistories as $ch) {
            $budi->careerHistories()->create($ch);
        }

        // Talent Assessments
        $assessments = [
            ['assessment_date' => 'Aug-26', 'position_standard' => 'Manager', 'potass_score' => '106%', 'category' => 'High', 'assessor' => 'HR Development'],
            ['assessment_date' => 'Aug-24', 'position_standard' => 'Section Head', 'potass_score' => '94%', 'category' => 'Average', 'assessor' => 'HR Development'],
            ['assessment_date' => 'Aug-22', 'position_standard' => 'Section Head', 'potass_score' => '88%', 'category' => 'Average', 'assessor' => 'HR Development'],
            ['assessment_date' => 'Aug-20', 'position_standard' => 'Supervisor', 'potass_score' => '78%', 'category' => 'Below Average', 'assessor' => 'HR Development'],
        ];
        foreach ($assessments as $ast) {
            $budi->talentAssessments()->create($ast);
        }

        // Key Strengths
        $strengths = [
            [
                'order_no' => 1,
                'strength' => 'Problem Solving',
                'short_description' => 'Mampu menganalisis masalah kompleks dan memberikan solusi efektif dan aplikatif.',
                'source' => 'PA, POTASS',
            ],
            [
                'order_no' => 2,
                'strength' => 'Technical Expertise',
                'short_description' => 'Penguasaan teknis di bidang proses manufaktur dan continuous improvement.',
                'source' => 'PA, Atasan Langsung',
            ],
            [
                'order_no' => 3,
                'strength' => 'Leadership & Ownership',
                'short_description' => 'Memiliki rasa memiliki tinggi dan mampu memimpin tim mencapai target.',
                'source' => 'PA, 360 Feedback',
            ],
        ];
        foreach ($strengths as $st) {
            $budi->keyStrengths()->create($st);
        }

        // Career Plan
        $budi->careerPlan()->create([
            'interested_area' => 'Engineering / Manufacturing',
            'recommended_career_path' => 'Managerial',
            'next_possible_position' => 'Engineering Manager',
            'next_possible_department' => 'Manufacturing Engineering',
            'career_projection' => 'Engineering Division Head',
            'notes' => 'Karyawan berminat terus berkembang di bidang Engineering dengan fokus pada pengelolaan tim dan strategi operasi.',
        ]);

        // Job Class Plans
        $jobClassPlans = [
            ['order_no' => 1, 'plan_year' => '2026', 'projected_age' => 41, 'job_class' => 'JC4', 'grade' => 'G4-2', 'change_type' => 'Saat Ini', 'target_position' => 'Section Head', 'target_department' => 'Manufacturing Engineering', 'notes' => 'Posisi dan grade saat ini'],
            ['order_no' => 2, 'plan_year' => '2029', 'projected_age' => 44, 'job_class' => 'JC4', 'grade' => 'G4-3', 'change_type' => 'Kenaikan Pangkat Reguler', 'target_position' => 'Section Head', 'target_department' => 'Manufacturing Engineering', 'notes' => 'Kenaikan grade reguler berdasarkan kebijakan perusahaan'],
            ['order_no' => 3, 'plan_year' => '2031', 'projected_age' => 46, 'job_class' => 'JC5', 'grade' => 'G5-1', 'change_type' => 'Promosi', 'target_position' => 'Engineering Manager', 'target_department' => 'Manufacturing Engineering', 'notes' => 'Promosi ke posisi Manager'],
            ['order_no' => 4, 'plan_year' => '2034', 'projected_age' => 49, 'job_class' => 'JC5', 'grade' => 'G5-2', 'change_type' => 'Kenaikan Pangkat Reguler', 'target_position' => 'Engineering Manager', 'target_department' => 'Manufacturing Engineering', 'notes' => 'Kenaikan grade reguler'],
            ['order_no' => 5, 'plan_year' => '2037', 'projected_age' => 52, 'job_class' => 'JC5', 'grade' => 'G5-3', 'change_type' => 'Kenaikan Pangkat Reguler', 'target_position' => 'Engineering Manager', 'target_department' => 'Manufacturing Engineering', 'notes' => 'Kenaikan grade reguler'],
            ['order_no' => 6, 'plan_year' => '2040', 'projected_age' => 55, 'job_class' => '-', 'grade' => '-', 'change_type' => 'Pensiun', 'target_position' => '-', 'target_department' => '-', 'notes' => 'Perkiraan usia pensiun sesuai kebijakan perusahaan'],
        ];
        foreach ($jobClassPlans as $jcp) {
            $budi->jobClassPlans()->create($jcp);
        }

        // Succession Positions (E1)
        $succPositions = [
            [
                'order_no' => 1,
                'target_position' => 'Engineering Manager',
                'department' => 'Manufacturing Engineering',
                'position_level' => 'Manager',
                'reason' => 'Posisi strategis yang dibutuhkan dalam pengelolaan tim engineering dan improvement',
                'needed_at' => 'Jul 2027',
                'is_active' => true,
                'readiness' => '1–2 Tahun',
                'ranking' => 1,
            ],
            [
                'order_no' => 2,
                'target_position' => 'Manufacturing Manager',
                'department' => 'Manufacturing',
                'position_level' => 'Manager',
                'reason' => 'Pengembangan karir dalam area produksi dan operasional pabrik',
                'needed_at' => 'Jul 2030',
                'is_active' => true,
                'readiness' => '3–5 Tahun',
                'ranking' => 2,
            ],
        ];
        foreach ($succPositions as $sp) {
            $budi->successionPositions()->create($sp);
        }

        // Succession Candidates (E2)
        $succCandidates = [
            [
                'order_no' => 1,
                'candidate_name' => 'Andi Pratama',
                'current_department' => 'Manufacturing Engineering',
                'current_position' => 'Supervisor',
                'readiness' => '3–5 Tahun',
                'needed_at' => 'Apr 2044',
                'ranking' => 1,
                'notes' => 'Kandidat internal utama',
            ],
            [
                'order_no' => 2,
                'candidate_name' => 'Rizky Maulana',
                'current_department' => 'Manufacturing Engineering',
                'current_position' => 'Senior Engineer',
                'readiness' => '>5 Tahun',
                'needed_at' => 'Apr 2044',
                'ranking' => 2,
                'notes' => 'Perlu exposure lintas fungsi',
            ],
            [
                'order_no' => 3,
                'candidate_name' => 'Iwan Setiawan',
                'current_department' => 'Process Engineering',
                'current_position' => 'Senior Engineer',
                'readiness' => '>5 Tahun',
                'needed_at' => 'Apr 2044',
                'ranking' => 3,
                'notes' => 'Potensi baik, perlu penguatan kepemimpinan',
            ],
        ];
        foreach ($succCandidates as $sc) {
            $budi->successionCandidates()->create($sc);
        }

        // Competency Gaps
        $gaps = [
            [
                'order_no' => 1,
                'competency' => 'Vision & Business Sense',
                'current_level' => 3,
                'current_desc' => 'Memahami arah dan tujuan unit kerja',
                'standard_level' => 5,
                'standard_desc' => 'Menetapkan visi dan strategi selaras dengan tujuan bisnis',
                'gap' => 2,
                'gap_severity' => 'Tinggi',
                'expected_improvement' => 'Mampu menyusun strategi dan melihat peluang bisnis untuk mendukung pertumbuhan perusahaan',
            ],
            [
                'order_no' => 2,
                'competency' => 'Customer Focus',
                'current_level' => 4,
                'current_desc' => 'Memahami kebutuhan customer',
                'standard_level' => 5,
                'standard_desc' => 'Menciptakan nilai dan solusi terbaik bagi customer',
                'gap' => 1,
                'gap_severity' => 'Sedang',
                'expected_improvement' => 'Lebih proaktif menggali kebutuhan customer dan menghasilkan improvement yang berdampak',
            ],
            [
                'order_no' => 3,
                'competency' => 'Interpersonal Skill',
                'current_level' => 4,
                'current_desc' => 'Membangun hubungan kerja yang baik',
                'standard_level' => 5,
                'standard_desc' => 'Mempengaruhi dan membangun relasi lintas level/fungsi',
                'gap' => 1,
                'gap_severity' => 'Sedang',
                'expected_improvement' => 'Mampu mempengaruhi stakeholder kunci dan membangun kolaborasi yang efektif',
            ],
            [
                'order_no' => 4,
                'competency' => 'Analysis & Judgement',
                'current_level' => 3,
                'current_desc' => 'Menganalisis masalah dengan data',
                'standard_level' => 5,
                'standard_desc' => 'Menganalisis kompleks dan membuat keputusan strategis',
                'gap' => 2,
                'gap_severity' => 'Tinggi',
                'expected_improvement' => 'Mampu menganalisis isu kompleks dan mengambil keputusan strategis yang tepat',
            ],
            [
                'order_no' => 5,
                'competency' => 'Planning & Driving Action',
                'current_level' => 4,
                'current_desc' => 'Merencanakan dan menggerakkan tim',
                'standard_level' => 5,
                'standard_desc' => 'Merencanakan jangka panjang dan mengeksekusi dengan efektif',
                'gap' => 1,
                'gap_severity' => 'Sedang',
                'expected_improvement' => 'Mampu menyusun rencana jangka panjang dan memastikan eksekusi sampai tuntas',
            ],
            [
                'order_no' => 6,
                'competency' => 'Leading & Motivating',
                'current_level' => 3,
                'current_desc' => 'Memimpin dan memotivasi tim',
                'standard_level' => 5,
                'standard_desc' => 'Menginspirasi dan mengembangkan tim untuk mencapai hasil tinggi',
                'gap' => 2,
                'gap_severity' => 'Tinggi',
                'expected_improvement' => 'Mampu menginspirasi tim dan mengembangkan potensi anggota tim secara berkelanjutan',
            ],
            [
                'order_no' => 7,
                'competency' => 'Teamwork',
                'current_level' => 4,
                'current_desc' => 'Bekerja sama dalam tim',
                'standard_level' => 5,
                'standard_desc' => 'Membangun sinergi tim lintas fungsi untuk hasil optimal',
                'gap' => 1,
                'gap_severity' => 'Sedang',
                'expected_improvement' => 'Mampu membangun sinergi lintas fungsi dan mengelola konflik secara konstruktif',
            ],
            [
                'order_no' => 8,
                'competency' => 'Drive & Courage',
                'current_level' => 3,
                'current_desc' => 'Berinisiatif dan berani mengambil risiko',
                'standard_level' => 5,
                'standard_desc' => 'Berani mengambil risiko terukur dan mendorong perubahan',
                'gap' => 2,
                'gap_severity' => 'Tinggi',
                'expected_improvement' => 'Mampu mendorong perubahan dan mengambil keputusan penting di situasi menantang',
            ],
        ];
        foreach ($gaps as $g) {
            $budi->competencyGaps()->create($g);
        }

        // IDP Action Plans
        $idpPlans = [
            [
                'order_no' => 1,
                'competency' => 'Vision & Business Sense',
                'specific_goal' => 'Mampu memahami arah bisnis perusahaan dan mengaitkan strategi divisi dengan tujuan organisasi.',
                'development_methods' => 'Training, On the Job, Exposure',
                'activity_program' => "• Business Acumen Program\n• Project Strategic Initiative\n• Executive Briefing",
                'pic_supporter' => "• Director\n• Mentor",
                'start_date' => 'Jun 2026',
                'end_date' => 'Des 2026',
                'success_indicator' => 'Menyusun proposal inisiatif strategis dan disetujui atasan.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 2,
                'competency' => 'Customer Focus',
                'specific_goal' => 'Mampu memahami kebutuhan customer dan memberikan solusi yang berdampak.',
                'development_methods' => 'Training, On the Job',
                'activity_program' => "• VOC Analysis Workshop\n• Customer Visit\n• Problem Solving Project",
                'pic_supporter' => "• Marketing\n• Mentor",
                'start_date' => 'Jun 2026',
                'end_date' => 'Nov 2026',
                'success_indicator' => 'Peningkatan kepuasan customer dan berkurangnya keluhan.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 3,
                'competency' => 'Interpersonal Skill',
                'specific_goal' => 'Mampu membangun hubungan kerja yang efektif dan komunikasi yang positif.',
                'development_methods' => 'Training, Coaching',
                'activity_program' => "• Effective Communication Training\n• Coaching/Mentoring bulanan",
                'pic_supporter' => "• HRD\n• Mentor",
                'start_date' => 'Jun 2026',
                'end_date' => 'Mei 2027',
                'success_indicator' => 'Feedback 360° meningkat pada aspek komunikasi dan kolaborasi.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 4,
                'competency' => 'Analysis & Judgement',
                'specific_goal' => 'Mampu menganalisis data secara sistematis dan mengambil keputusan yang tepat.',
                'development_methods' => 'Training, On the Job',
                'activity_program' => "• Data Analytics Training\n• Case Study Analysis\n• Decision Making Project",
                'pic_supporter' => "• Atasan Langsung\n• SME",
                'start_date' => 'Jul 2026',
                'end_date' => 'Feb 2027',
                'success_indicator' => 'Keputusan berbasis data yang memberikan dampak terukur.',
                'status' => 'On Progress',
                'progress_percent' => 60,
            ],
            [
                'order_no' => 5,
                'competency' => 'Planning & Driving Action',
                'specific_goal' => 'Mampu merencanakan pekerjaan secara efektif dan mengeksekusi hingga tuntas.',
                'development_methods' => 'Training, On the Job',
                'activity_program' => "• Project Management Training\n• Action Plan Execution",
                'pic_supporter' => "• Atasan Langsung\n• PMO",
                'start_date' => 'Jun 2026',
                'end_date' => 'Mei 2027',
                'success_indicator' => 'Proyek selesai sesuai rencana waktu, biaya, dan kualitas.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 6,
                'competency' => 'Leading & Motivating',
                'specific_goal' => 'Mampu memimpin tim dan memotivasi untuk mencapai hasil terbaik.',
                'development_methods' => 'Training, Coaching, On the Job',
                'activity_program' => "• Leadership Development Program\n• Coaching/Mentoring bulanan\n• Leading Team Project",
                'pic_supporter' => "• HRD\n• Mentor",
                'start_date' => 'Jun 2026',
                'end_date' => 'Mei 2027',
                'success_indicator' => 'Engagement tim meningkat dan target tercapai.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 7,
                'competency' => 'Teamwork',
                'specific_goal' => 'Mampu bekerja sama lintas fungsi untuk mencapai tujuan bersama.',
                'development_methods' => 'On the Job, Exposure',
                'activity_program' => "• Cross Functional Project\n• Team Collaboration Workshop",
                'pic_supporter' => "• Atasan Langsung",
                'start_date' => 'Jul 2026',
                'end_date' => 'Jan 2027',
                'success_indicator' => 'Feedback lintas fungsi positif dan kolaborasi efektif.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
            [
                'order_no' => 8,
                'competency' => 'Drive & Courage',
                'specific_goal' => 'Mampu menunjukkan inisiatif, keberanian mengambil risiko terukur, dan ketangguhan menghadapi tantangan.',
                'development_methods' => 'Training, On the Job, Coaching',
                'activity_program' => "• Courageous Leadership Workshop\n• Stretch Assignment\n• Coaching/Mentoring",
                'pic_supporter' => "• Mentor\n• Atasan Langsung",
                'start_date' => 'Jul 2026',
                'end_date' => 'Mei 2027',
                'success_indicator' => 'Inisiatif baru diimplementasikan dan memberikan hasil.',
                'status' => 'Selesai',
                'progress_percent' => 100,
            ],
        ];
        foreach ($idpPlans as $idp) {
            $budi->idpActionPlans()->create($idp);
        }

        // Development Reviews
        $reviews = [
            ['order_no' => 1, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Vision & Business Sense', 'previous_level' => 3, 'current_level' => 4, 'target_level' => 5, 'growth' => 1, 'status' => 'Meningkat', 'reviewer_notes' => 'Lebih memahami arah bisnis dan mampu mengaitkan strategi dengan tujuan organisasi.'],
            ['order_no' => 2, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Customer Focus', 'previous_level' => 4, 'current_level' => 4, 'target_level' => 5, 'growth' => 0, 'status' => 'Stabil', 'reviewer_notes' => 'Konsisten memahami kebutuhan customer dan memberikan solusi yang berdampak.'],
            ['order_no' => 3, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Interpersonal Skill', 'previous_level' => 3, 'current_level' => 4, 'target_level' => 5, 'growth' => 1, 'status' => 'Meningkat', 'reviewer_notes' => 'Kemampuan komunikasi dan membangun relasi lintas fungsi meningkat signifikan.'],
            ['order_no' => 4, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Analysis & Judgement', 'previous_level' => 3, 'current_level' => 3, 'target_level' => 5, 'growth' => 0, 'status' => 'Belum Meningkat', 'reviewer_notes' => 'Perlu penguatan dalam analisis kompleks dan pengambilan keputusan strategis berbasis data.'],
            ['order_no' => 5, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Planning & Driving Action', 'previous_level' => 4, 'current_level' => 5, 'target_level' => 5, 'growth' => 1, 'status' => 'Meningkat', 'reviewer_notes' => 'Perencanaan lebih matang dan eksekusi program berjalan sesuai target.'],
            ['order_no' => 6, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Leading & Motivating', 'previous_level' => 3, 'current_level' => 4, 'target_level' => 5, 'growth' => 1, 'status' => 'Meningkat', 'reviewer_notes' => 'Mampu memotivasi tim dan mendorong accountability lebih baik.'],
            ['order_no' => 7, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Teamwork', 'previous_level' => 4, 'current_level' => 4, 'target_level' => 5, 'growth' => 0, 'status' => 'Stabil', 'reviewer_notes' => 'Kolaborasi dalam tim dan lintas fungsi berjalan baik.'],
            ['order_no' => 8, 'period' => 'Juni 2026 – Mei 2027', 'competency' => 'Drive & Courage', 'previous_level' => 3, 'current_level' => 4, 'target_level' => 5, 'growth' => 1, 'status' => 'Meningkat', 'reviewer_notes' => 'Lebih proaktif mengambil risiko terukur dan mendorong perubahan positif.'],
        ];
        foreach ($reviews as $rev) {
            $budi->developmentReviews()->create($rev);
        }

        // Training Histories
        $trainings = [
            [
                'order_no' => 1,
                'training_date' => '15 – 17 Mei 2026',
                'training_name' => 'Problem Solving & Decision Making',
                'category' => 'Functional',
                'training_type' => 'Classroom',
                'organizer' => 'Musashi Learning Center',
                'duration_hours' => 16,
                'notes' => 'Meningkatkan kemampuan analisis dan pengambilan keputusan berbasis data.',
            ],
            [
                'order_no' => 2,
                'training_date' => '10 Apr 2026',
                'training_name' => 'Project Management Fundamentals',
                'category' => 'Managerial',
                'training_type' => 'Classroom',
                'organizer' => 'Astra Management Development Institute',
                'duration_hours' => 16,
                'notes' => 'Dasar-dasar manajemen proyek untuk pengelolaan lintas fungsi.',
            ],
            [
                'order_no' => 3,
                'training_date' => '20 Mar 2026',
                'training_name' => 'Lean Manufacturing Awareness',
                'category' => 'Functional',
                'training_type' => 'On the Job',
                'organizer' => 'Internal Training (MAP-IN)',
                'duration_hours' => 12,
                'notes' => 'Penerapan prinsip lean di area kerja.',
            ],
            [
                'order_no' => 4,
                'training_date' => '5 – 6 Feb 2026',
                'training_name' => 'Leadership for Section Head',
                'category' => 'Managerial',
                'training_type' => 'Classroom',
                'organizer' => 'Musashi Learning Center',
                'duration_hours' => 16,
                'notes' => 'Penguatan kepemimpinan dan pengelolaan tim.',
            ],
            [
                'order_no' => 5,
                'training_date' => '15 Jan 2026',
                'training_name' => 'Effective Communication',
                'category' => 'Managerial',
                'training_type' => 'E-Learning',
                'organizer' => 'LinkedIn Learning',
                'duration_hours' => 4,
                'notes' => 'Meningkatkan komunikasi efektif dalam tim.',
            ],
            [
                'order_no' => 6,
                'training_date' => '12 Des 2025',
                'training_name' => 'Data Analysis Using Excel',
                'category' => 'Functional',
                'training_type' => 'Classroom',
                'organizer' => 'DataCamp',
                'duration_hours' => 12,
                'notes' => 'Analisis data untuk mendukung pengambilan keputusan.',
            ],
            [
                'order_no' => 7,
                'training_date' => '10 Nov 2025',
                'training_name' => 'Kaizen & Continuous Improvement',
                'category' => 'Functional',
                'training_type' => 'On the Job',
                'organizer' => 'Internal Training (MAP-IN)',
                'duration_hours' => 8,
                'notes' => 'Penerapan kaizen dalam proses kerja sehari-hari.',
            ],
            [
                'order_no' => 8,
                'training_date' => '1 – 2 Okt 2025',
                'training_name' => 'Coaching & Mentoring Skills',
                'category' => 'Managerial',
                'training_type' => 'Classroom',
                'organizer' => 'Astra Management Development Institute',
                'duration_hours' => 16,
                'notes' => 'Kemampuan coaching dan mentoring pengembangan tim.',
            ],
        ];
        foreach ($trainings as $tr) {
            $budi->trainingHistories()->create($tr);
        }

        // Certifications
        $certifications = [
            [
                'order_no' => 1,
                'name' => 'Lean Six Sigma Green Belt',
                'obtained_date' => '20 Mar 2025',
                'issuer' => 'LSS Institute',
                'valid_until' => '20 Mar 2027',
                'is_active' => true,
            ],
            [
                'order_no' => 2,
                'name' => 'Project Management Professional (PMP®)',
                'obtained_date' => '10 Sep 2024',
                'issuer' => 'PMI',
                'valid_until' => '09 Sep 2027',
                'is_active' => true,
            ],
            [
                'order_no' => 3,
                'name' => 'ISO 9001:2015 Internal Auditor',
                'obtained_date' => '5 Jul 2024',
                'issuer' => 'TUV Nord',
                'valid_until' => '04 Jul 2026',
                'is_active' => true,
            ],
            [
                'order_no' => 4,
                'name' => 'Basic Safety Training (Sertifikat K3 Dasar)',
                'obtained_date' => '15 Jan 2024',
                'issuer' => 'Kemenaker RI',
                'valid_until' => '14 Jan 2027',
                'is_active' => true,
            ],
        ];
        foreach ($certifications as $cert) {
            $budi->certifications()->create($cert);
        }

        // 2. Sample Employee 2: Siti Rahma (NIK: 012346)
        $siti = Employee::create([
            'nik' => '012346',
            'name' => 'Siti Rahma',
            'avatar' => '/images/avatar-siti.png',
            'position' => 'Section Head – Quality Assurance',
            'department' => 'Quality Management',
            'section' => 'Quality Control',
            'age' => 38,
            'tenure_years' => 12,
            'education' => 'S1 Teknik Industri',
            'current_job_class' => 'JC4',
            'current_grade' => 'G4-1',
            'grade_since' => 'Oktober 2025',
            'position_since' => 'Oktober 2024',
            'performance_current' => 'A',
            'potass_current' => '104% (High)',
            'hav_box_current' => 'Box 14',
            'talent_pool_status' => 'YA',
            'flying_risk' => 'LOW',
            'flying_risk_reason' => 'High Company Loyalty',
            'next_possible_position' => 'Quality Assurance Manager',
            'career_projection' => 'Quality Division Head',
            'readiness_level' => '75%',
            'retirement_year' => '2047',
            'profile_notes' => 'Karyawan berprestasi tinggi dalam audit mutu internasional.',
        ]);
        $siti->careerHistories()->create([
            'order_no' => 1,
            'effective_date' => 'Okt-24',
            'department_section' => 'Quality Management / QA',
            'position' => 'Section Head',
            'job_class_grade' => 'JC4 / G4-1',
            'change_type' => 'Promosi',
            'notes' => 'Supervisor → Section Head',
        ]);
        $siti->careerPlan()->create([
            'interested_area' => 'Quality Assurance / Compliance',
            'recommended_career_path' => 'Managerial',
            'next_possible_position' => 'QA Manager',
            'next_possible_department' => 'Quality Management',
            'career_projection' => 'Quality Division Head',
        ]);

        // 3. Sample Employee 3: Ahmad Fauzi (NIK: 012347)
        $ahmad = Employee::create([
            'nik' => '012347',
            'name' => 'Ahmad Fauzi',
            'avatar' => '/images/avatar-ahmad.png',
            'position' => 'Section Head – Production Engineering',
            'department' => 'Manufacturing',
            'section' => 'Machining Section',
            'age' => 43,
            'tenure_years' => 19,
            'education' => 'S1 Teknik Mesin',
            'current_job_class' => 'JC4',
            'current_grade' => 'G4-2',
            'grade_since' => 'Januari 2026',
            'position_since' => 'Februari 2022',
            'performance_current' => 'A',
            'potass_current' => '102% (High)',
            'hav_box_current' => 'Box 15',
            'talent_pool_status' => 'YA',
            'flying_risk' => 'MEDIUM',
            'flying_risk_reason' => 'Looking for Senior Leadership Role',
            'next_possible_position' => 'Production Manager',
            'career_projection' => 'Plant General Manager',
            'readiness_level' => '70%',
            'retirement_year' => '2042',
        ]);
        $ahmad->careerHistories()->create([
            'order_no' => 1,
            'effective_date' => 'Feb-22',
            'department_section' => 'Manufacturing / Machining',
            'position' => 'Section Head',
            'job_class_grade' => 'JC4 / G4-1',
            'change_type' => 'Promosi',
            'notes' => 'Supervisor → Section Head',
        ]);
        $ahmad->careerPlan()->create([
            'interested_area' => 'Production & Assembly',
            'recommended_career_path' => 'Managerial',
            'next_possible_position' => 'Production Manager',
            'next_possible_department' => 'Manufacturing',
            'career_projection' => 'Plant General Manager',
        ]);

        // 4. Sample Employee 4: Dewi Lestari (NIK: 012348)
        $dewi = Employee::create([
            'nik' => '012348',
            'name' => 'Dewi Lestari',
            'avatar' => '/images/avatar-dewi.png',
            'position' => 'Section Head – Supply Chain Planning',
            'department' => 'Supply Chain Management',
            'section' => 'Logistics & Inventory',
            'age' => 36,
            'tenure_years' => 10,
            'education' => 'S1 Teknik Industri',
            'current_job_class' => 'JC4',
            'current_grade' => 'G4-1',
            'grade_since' => 'Mei 2025',
            'position_since' => 'Mei 2024',
            'performance_current' => 'B+',
            'potass_current' => '98% (Average)',
            'hav_box_current' => 'Box 11',
            'talent_pool_status' => 'TIDAK',
            'flying_risk' => 'LOW',
            'flying_risk_reason' => 'Stable Performance',
            'next_possible_position' => 'SCM Manager',
            'career_projection' => 'SCM Director',
            'readiness_level' => '55%',
            'retirement_year' => '2049',
        ]);
        $dewi->careerHistories()->create([
            'order_no' => 1,
            'effective_date' => 'Mei-24',
            'department_section' => 'Supply Chain Management / Logistics',
            'position' => 'Section Head',
            'job_class_grade' => 'JC4 / G4-1',
            'change_type' => 'Promosi',
            'notes' => 'Senior Officer → Section Head',
        ]);
        $dewi->careerPlan()->create([
            'interested_area' => 'Logistics & Supply Chain',
            'recommended_career_path' => 'Managerial',
            'next_possible_position' => 'SCM Manager',
            'next_possible_department' => 'Supply Chain Management',
            'career_projection' => 'SCM Director',
        ]);
        }
    }
}
