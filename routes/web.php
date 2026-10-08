<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Protected Routes - Wajib Login untuk mengakses sistem
Route::middleware(['auth'])->group(function () {
    // Redirect root to Beranda (/beranda)
    Route::get('/', function () {
        return redirect()->route('beranda');
    });

    // Beranda Karyawan (Dashboard Utama Super Admin & HR Admin)
    Route::get('/beranda', [EmployeeController::class, 'index'])->name('beranda');

    // Data Karyawan (Master Data, Filter, Tabel, Tambah & Impor)
    Route::get('/data-karyawan', [EmployeeController::class, 'dataKaryawan'])->name('data-karyawan');
    Route::get('/karyawan', function () {
        return redirect()->route('data-karyawan', request()->query());
    })->name('karyawan.index');

    // Super Admin Employee Management Routes
    Route::post('/karyawan', [EmployeeController::class, 'storeEmployee'])->name('karyawan.store');
    Route::put('/karyawan/{nik}', [EmployeeController::class, 'updateEmployee'])->name('karyawan.update');
    Route::delete('/karyawan/{nik}', [EmployeeController::class, 'destroyEmployee'])->name('karyawan.destroy');

    // Excel Integration Routes (Template, Impor, Ekspor)
    Route::get('/karyawan/excel/template', [EmployeeController::class, 'downloadExcelTemplate'])->name('karyawan.excel.template');
    Route::post('/karyawan/excel/import', [EmployeeController::class, 'importExcel'])->name('karyawan.excel.import');
    Route::get('/karyawan/excel/export-all', [EmployeeController::class, 'exportAllExcel'])->name('karyawan.excel.export-all');

    // Search NIK (Form submission)
    Route::get('/karyawan/search', [EmployeeController::class, 'search'])->name('karyawan.search');

    // Live Autocomplete API for NIK search
    Route::get('/api/karyawan/search', [EmployeeController::class, 'apiSearch'])->name('api.karyawan.search');

    // Export CSV
    Route::get('/karyawan/{nik}/export/{type}', [EmployeeController::class, 'export'])->name('karyawan.export');

    // CRUD Operations: Riwayat Karir
    Route::post('/karyawan/{nik}/career-history', [EmployeeController::class, 'storeCareerHistory'])->name('karyawan.career-history.store');
    Route::put('/karyawan/{nik}/career-history/{id}', [EmployeeController::class, 'updateCareerHistory'])->name('karyawan.career-history.update');
    Route::delete('/karyawan/{nik}/career-history/{id}', [EmployeeController::class, 'destroyCareerHistory'])->name('karyawan.career-history.destroy');

    // CRUD Operations: Talent Snapshot (6 cards) & Performance Appraisals
    Route::post('/karyawan/{nik}/talent-snapshot', [EmployeeController::class, 'updateTalentSnapshot'])->name('karyawan.talent-snapshot.update');
    Route::post('/karyawan/{nik}/talent-snapshot/performance', [EmployeeController::class, 'updateTalentPerformance'])->name('karyawan.talent-snapshot.performance.update');
    Route::post('/karyawan/{nik}/performance-appraisal', [EmployeeController::class, 'storeOrUpdatePerformance'])->name('karyawan.performance-appraisal.store');
    Route::delete('/karyawan/{nik}/performance-appraisal/{id}', [EmployeeController::class, 'destroyPerformance'])->name('karyawan.performance-appraisal.destroy');
    Route::post('/karyawan/{nik}/talent-snapshot/potass', [EmployeeController::class, 'updateTalentPotass'])->name('karyawan.talent-snapshot.potass.update');
    Route::post('/karyawan/{nik}/talent-snapshot/hav-box', [EmployeeController::class, 'updateTalentHavBox'])->name('karyawan.talent-snapshot.hav-box.update');
    Route::post('/karyawan/{nik}/talent-snapshot/flying-risk', [EmployeeController::class, 'updateTalentFlyingRisk'])->name('karyawan.talent-snapshot.flying-risk.update');

    Route::post('/karyawan/{nik}/key-strength', [EmployeeController::class, 'storeKeyStrength'])->name('karyawan.key-strength.store');
    Route::put('/karyawan/{nik}/key-strength/{id}', [EmployeeController::class, 'updateKeyStrength'])->name('karyawan.key-strength.update');
    Route::delete('/karyawan/{nik}/key-strength/{id}', [EmployeeController::class, 'destroyKeyStrength'])->name('karyawan.key-strength.destroy');

    Route::post('/karyawan/{nik}/talent-assessment', [EmployeeController::class, 'storeTalentAssessment'])->name('karyawan.talent-assessment.store');
    Route::put('/karyawan/{nik}/talent-assessment/{id}', [EmployeeController::class, 'updateTalentAssessment'])->name('karyawan.talent-assessment.update');
    Route::delete('/karyawan/{nik}/talent-assessment/{id}', [EmployeeController::class, 'destroyTalentAssessment'])->name('karyawan.talent-assessment.destroy');

    // CRUD Operations: Individual Career Plan (ICP)
    Route::post('/karyawan/{nik}/career-plan', [EmployeeController::class, 'updateCareerPlan'])->name('karyawan.career-plan.update');
    Route::post('/karyawan/{nik}/career-path', [EmployeeController::class, 'updateCareerPlan'])->name('karyawan.career-path.update');
    Route::post('/karyawan/{nik}/job-class-plan', [EmployeeController::class, 'storeJobClassPlan'])->name('karyawan.job-class-plan.store');
    Route::put('/karyawan/{nik}/job-class-plan/{id}', [EmployeeController::class, 'updateJobClassPlan'])->name('karyawan.job-class-plan.update');
    Route::delete('/karyawan/{nik}/job-class-plan/{id}', [EmployeeController::class, 'destroyJobClassPlan'])->name('karyawan.job-class-plan.destroy');

    // CRUD Operations: Status Suksesi
    Route::post('/karyawan/{nik}/succession-position', [EmployeeController::class, 'storeSuccessionPosition'])->name('karyawan.succession-position.store');
    Route::put('/karyawan/{nik}/succession-position/{id}', [EmployeeController::class, 'updateSuccessionPosition'])->name('karyawan.succession-position.update');
    Route::delete('/karyawan/{nik}/succession-position/{id}', [EmployeeController::class, 'destroySuccessionPosition'])->name('karyawan.succession-position.destroy');

    Route::post('/karyawan/{nik}/succession-candidate', [EmployeeController::class, 'storeSuccessionCandidate'])->name('karyawan.succession-candidate.store');
    Route::put('/karyawan/{nik}/succession-candidate/{id}', [EmployeeController::class, 'updateSuccessionCandidate'])->name('karyawan.succession-candidate.update');
    Route::delete('/karyawan/{nik}/succession-candidate/{id}', [EmployeeController::class, 'destroySuccessionCandidate'])->name('karyawan.succession-candidate.destroy');

    // CRUD Operations: Development Gap
    Route::post('/karyawan/{nik}/development-gap/target', [EmployeeController::class, 'updateDevelopmentGapTarget'])->name('karyawan.development-gap.target.update');
    Route::post('/karyawan/{nik}/competency-gap', [EmployeeController::class, 'storeCompetencyGap'])->name('karyawan.competency-gap.store');
    Route::put('/karyawan/{nik}/competency-gap/{id}', [EmployeeController::class, 'updateCompetencyGap'])->name('karyawan.competency-gap.update');
    Route::delete('/karyawan/{nik}/competency-gap/{id}', [EmployeeController::class, 'destroyCompetencyGap'])->name('karyawan.competency-gap.destroy');

    // CRUD Operations: Individual Development Plan (IDP)
    Route::post('/karyawan/{nik}/idp/summary', [EmployeeController::class, 'updateIdpSummary'])->name('karyawan.idp.summary.update');
    Route::post('/karyawan/{nik}/idp-action-plan', [EmployeeController::class, 'storeIdpActionPlan'])->name('karyawan.idp-action-plan.store');
    Route::put('/karyawan/{nik}/idp-action-plan/{id}', [EmployeeController::class, 'updateIdpActionPlan'])->name('karyawan.idp-action-plan.update');
    Route::delete('/karyawan/{nik}/idp-action-plan/{id}', [EmployeeController::class, 'destroyIdpActionPlan'])->name('karyawan.idp-action-plan.destroy');

    // CRUD Operations: Riwayat Pelatihan
    Route::post('/karyawan/{nik}/training-history', [EmployeeController::class, 'storeTrainingHistory'])->name('karyawan.training-history.store');
    Route::put('/karyawan/{nik}/training-history/{id}', [EmployeeController::class, 'updateTrainingHistory'])->name('karyawan.training-history.update');
    Route::delete('/karyawan/{nik}/training-history/{id}', [EmployeeController::class, 'destroyTrainingHistory'])->name('karyawan.training-history.destroy');

    // CRUD Operations: Sertifikasi (Bagian C Riwayat Pelatihan)
    Route::post('/karyawan/{nik}/certification', [EmployeeController::class, 'storeCertification'])->name('karyawan.certification.store');
    Route::put('/karyawan/{nik}/certification/{id}', [EmployeeController::class, 'updateCertification'])->name('karyawan.certification.update');
    Route::delete('/karyawan/{nik}/certification/{id}', [EmployeeController::class, 'destroyCertification'])->name('karyawan.certification.destroy');

    // CRUD Operations: Review Hasil Pengembangan
    Route::post('/karyawan/{nik}/development-review', [EmployeeController::class, 'storeDevelopmentReview'])->name('karyawan.development-review.store');
    Route::put('/karyawan/{nik}/development-review/{id}', [EmployeeController::class, 'updateDevelopmentReview'])->name('karyawan.development-review.update');
    Route::delete('/karyawan/{nik}/development-review/{id}', [EmployeeController::class, 'destroyDevelopmentReview'])->name('karyawan.development-review.destroy');
    Route::post('/karyawan/{nik}/development-review/feedback', [EmployeeController::class, 'updateReviewFeedback'])->name('karyawan.development-review.feedback.update');

    // Update Employee Photo / Avatar (Super Admin & HR Admin)
    Route::post('/karyawan/{nik}/avatar', [EmployeeController::class, 'updateAvatar'])->name('karyawan.avatar.update');

    // Employee Profile Views with 9 Tabs
    Route::get('/karyawan/{nik}/{tab?}', [EmployeeController::class, 'show'])->name('karyawan.show');
});


