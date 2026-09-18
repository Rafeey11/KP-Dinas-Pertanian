<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;

// === PUBLIC ROUTES ===
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');

Route::get('/bpp', [PublicController::class, 'bppIndex'])->name('bpp.index');
Route::get('/bpp/{slug}', [PublicController::class, 'bppShow'])->name('bpp.show');

Route::get('/penyuluh/{slug}', [PublicController::class, 'penyuluhShow'])->name('penyuluh.show');

Route::get('/berita', [PublicController::class, 'newsIndex'])->name('news.index');
Route::get('/berita/{slug}', [PublicController::class, 'newsShow'])->name('news.show');

Route::get('/video', [PublicController::class, 'videoIndex'])->name('video.index');

// === AUTH ROUTES ===
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === ADMIN ROUTES ===
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BppController as AdminBppController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PenyuluhController as AdminPenyuluhController;
use App\Http\Controllers\Admin\ReportTypeController;
use App\Http\Controllers\Admin\ReportRequestController;
use App\Http\Controllers\Admin\ReportValidationController;
use App\Http\Controllers\Admin\NewsApprovalController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\BankSopController as AdminBankSopController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\NotificationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

Route::middleware(['auth', 'role:admin,admin_tim_kerja'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data (Shared with Admin Tim Kerja, but filtered in controller)
    Route::get('/bpp/export', [AdminBppController::class, 'export'])->name('bpp.export');
    Route::resource('bpp', AdminBppController::class);
    
    Route::get('/penyuluh/export', [AdminPenyuluhController::class, 'export'])->name('penyuluh.export');
    Route::resource('penyuluh', AdminPenyuluhController::class);
    
    // Validasi Laporan (Shared with Admin Tim Kerja, but filtered in controller)
    Route::get('/report-validation', [ReportValidationController::class, 'index'])->name('report-validation.index');
    Route::get('/report-validation/{submission}', [ReportValidationController::class, 'show'])->name('report-validation.show');
    Route::post('/report-validation/{submission}/validate', [ReportValidationController::class, 'validate_report'])->name('report-validation.validate');
    Route::get('/report-validation/{submission}/download/{versionId}', [ReportValidationController::class, 'download'])->name('report-validation.download');

    // Berita (Approval) (Shared with Admin Tim Kerja, but filtered in controller)
    Route::get('/news', [NewsApprovalController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsApprovalController::class, 'show'])->name('news.show');
    Route::delete('/news/{news}', [NewsApprovalController::class, 'destroy'])->name('news.destroy');
    Route::post('/news/{news}/approve', [NewsApprovalController::class, 'approve'])->name('news.approve');
    Route::post('/news/{news}/reject', [NewsApprovalController::class, 'reject'])->name('news.reject');

    // Bank SOP (Shared with Admin Tim Kerja, filtered in controller)
    Route::get('/bank-sops', [AdminBankSopController::class, 'index'])->name('bank-sops.index');

    // ADMIN ONLY ROUTES
    Route::middleware(['role:admin'])->group(function () {
        // Bank SOP Create/Edit/Delete
        Route::get('/bank-sops/create', [AdminBankSopController::class, 'create'])->name('bank-sops.create');
        Route::post('/bank-sops', [AdminBankSopController::class, 'store'])->name('bank-sops.store');
        Route::get('/bank-sops/{bankSop}/edit', [AdminBankSopController::class, 'edit'])->name('bank-sops.edit');
        Route::put('/bank-sops/{bankSop}', [AdminBankSopController::class, 'update'])->name('bank-sops.update');
        Route::delete('/bank-sops/{bankSop}', [AdminBankSopController::class, 'destroy'])->name('bank-sops.destroy');

        // Form Fields Builder
        Route::post('/bank-sops/{bankSop}/fields', [AdminBankSopController::class, 'storeField'])->name('bank-sops.fields.store');
        Route::delete('/bank-sops/fields/{field}', [AdminBankSopController::class, 'destroyField'])->name('bank-sops.fields.destroy');

        Route::resource('users', AdminUserController::class);
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        // Laporan
        Route::resource('report-types', ReportTypeController::class);
        Route::resource('report-requests', ReportRequestController::class);

        // Video
        Route::resource('videos', AdminVideoController::class);
        Route::post('/videos/{video}/approve', [AdminVideoController::class, 'approve'])->name('videos.approve');
        Route::post('/videos/{video}/reject', [AdminVideoController::class, 'reject'])->name('videos.reject');

        // Bank Data (E-Library)
        Route::resource('documents', App\Http\Controllers\Admin\DocumentController::class)->except('show');
    });

    // These dynamic routes must be placed AFTER /bank-sops/create to prevent matching 'create' as an ID
    Route::get('/bank-sops/{bankSop}', [AdminBankSopController::class, 'show'])->name('bank-sops.show');
    Route::get('/bank-sops/{bankSop}/download', [AdminBankSopController::class, 'download'])->name('bank-sops.download');
    Route::get('/bank-sops/{bankSop}/monitor', [AdminBankSopController::class, 'monitor'])->name('bank-sops.monitor');
    Route::get('/bank-sops/{bankSop}/export', [AdminBankSopController::class, 'export'])->name('bank-sops.export');
});

// === BPP ROUTES ===
use App\Http\Controllers\Bpp\DashboardController as BppDashboardController;
use App\Http\Controllers\Bpp\PenyuluhController as BppPenyuluhController;
use App\Http\Controllers\Bpp\NewsController as BppNewsController;
use App\Http\Controllers\Bpp\ProfileController as BppProfileController;
use App\Http\Controllers\Bpp\ReportController as BppReportController;
use App\Http\Controllers\Bpp\VideoController as BppVideoController;
use App\Http\Controllers\Bpp\BankSopController as BppBankSopController;

Route::middleware(['auth', 'role:bpp'])->prefix('dashboard')->name('bpp.')->group(function () {
    Route::get('/', [BppDashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [BppProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [BppProfileController::class, 'update'])->name('profil.update');

    // Penyuluh
    Route::resource('penyuluh', BppPenyuluhController::class);

    // Laporan
    Route::get('/laporan', [BppReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{reportRequest}', [BppReportController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{reportRequest}/upload/{penyuluh}', [BppReportController::class, 'upload'])->name('laporan.upload');
    Route::post('/laporan/{reportRequest}/upload/{penyuluh}', [BppReportController::class, 'storeUpload'])->name('laporan.store-upload');
    Route::get('/riwayat-laporan', [BppReportController::class, 'history'])->name('laporan.history');

    // Berita
    Route::resource('berita', BppNewsController::class);

    // Video
    Route::resource('video', BppVideoController::class);

    // Bank Data (E-Library)
    Route::get('/documents', [App\Http\Controllers\Bpp\DocumentController::class, 'index'])->name('documents.index');

    // Bank SOP
    Route::get('/bank-sops', [BppBankSopController::class, 'index'])->name('bank-sops.index');
    Route::get('/bank-sops/{bankSop}', [BppBankSopController::class, 'show'])->name('bank-sops.show');
    Route::get('/bank-sops/{bankSop}/download', [BppBankSopController::class, 'download'])->name('bank-sops.download');
    Route::post('/bank-sops/{bankSop}/submit', [BppBankSopController::class, 'submit'])->name('bank-sops.submit');
});
