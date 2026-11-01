<?php

use App\Http\Controllers\FireReportController;
use App\Http\Controllers\FireReportEvidenceController;
use App\Http\Controllers\FireReportStatusController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
});

Route::get('/health', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::resource('reports', FireReportController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    Route::get('/reports/{report}/evidence', FireReportEvidenceController::class)
        ->name('reports.evidence');

    Route::patch('/reports/{report}/status', [
        FireReportStatusController::class, 'update',
    ])->name('reports.status');
});

/*
|--------------------------------------------------------------------------
| Contoh route dengan role middleware
|--------------------------------------------------------------------------
| Route di bawah siap dipakai saat halaman review/rekap dibuat pada
| bagian berikutnya. Hapus atau sesuaikan jika belum dibutuhkan.
*/
Route::middleware(['auth', 'role:monitor,coordinator'])
    ->get('/review', function () {
        return 'Halaman ini hanya untuk monitor dan koordinator.';
    })->name('review');
