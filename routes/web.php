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

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('/bpp/export', [AdminBppController::class, 'export'])->name('bpp.export');
    Route::resource('bpp', AdminBppController::class);
    
    Route::get('/penyuluh/export', [AdminPenyuluhController::class, 'export'])->name('penyuluh.export');
    Route::resource('penyuluh', AdminPenyuluhController::class);
    
    Route::resource('users', AdminUserController::class);

    // Laporan
    Route::resource('report-types', ReportTypeController::class);
    Route::resource('report-requests', ReportRequestController::class);

    // Validasi Laporan
    Route::get('/report-validation', [ReportValidationController::class, 'index'])->name('report-validation.index');
    Route::get('/report-validation/{submission}', [ReportValidationController::class, 'show'])->name('report-validation.show');
    Route::post('/report-validation/{submission}/validate', [ReportValidationController::class, 'validate_report'])->name('report-validation.validate');
    Route::get('/report-validation/{submission}/download/{versionId}', [ReportValidationController::class, 'download'])->name('report-validation.download');

    // Berita (Approval)
    Route::get('/news', [NewsApprovalController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsApprovalController::class, 'show'])->name('news.show');
    Route::delete('/news/{news}', [NewsApprovalController::class, 'destroy'])->name('news.destroy');
    Route::post('/news/{news}/approve', [NewsApprovalController::class, 'approve'])->name('news.approve');
    Route::post('/news/{news}/reject', [NewsApprovalController::class, 'reject'])->name('news.reject');

    // Video
    Route::resource('videos', AdminVideoController::class);
    Route::post('/videos/{video}/approve', [AdminVideoController::class, 'approve'])->name('videos.approve');
    Route::post('/videos/{video}/reject', [AdminVideoController::class, 'reject'])->name('videos.reject');

    // Bank Data (E-Library)
    Route::resource('documents', App\Http\Controllers\Admin\DocumentController::class)->except('show');
});

// === BPP ROUTES ===
use App\Http\Controllers\Bpp\DashboardController as BppDashboardController;
use App\Http\Controllers\Bpp\PenyuluhController as BppPenyuluhController;
use App\Http\Controllers\Bpp\NewsController as BppNewsController;
use App\Http\Controllers\Bpp\ProfileController as BppProfileController;
use App\Http\Controllers\Bpp\ReportController as BppReportController;
use App\Http\Controllers\Bpp\VideoController as BppVideoController;

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
});
