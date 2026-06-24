<?php

use App\Http\Controllers\Admin\BimbinganController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\LaporanProgresController;
use App\Http\Controllers\MahasiswaBimbinganController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgressReviewController;
use App\Http\Controllers\ProgressSkripsiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dosen-pembimbing', [DosenPembimbingController::class, 'show'])->name('dosen-pembimbing.show');
    Route::post('/dosen-pembimbing', [DosenPembimbingController::class, 'store'])->name('dosen-pembimbing.store');

    Route::middleware('mahasiswa.has-dosen')->group(function () {
        Route::get('/progress-skripsi', [ProgressSkripsiController::class, 'index'])->name('progress-skripsi.index');
        Route::get('/progress-skripsi/create', [ProgressSkripsiController::class, 'create'])->name('progress-skripsi.create');
        Route::post('/progress-skripsi', [ProgressSkripsiController::class, 'store'])->name('progress-skripsi.store');
        Route::get('/progress-skripsi/{progress}/file', [ProgressSkripsiController::class, 'file'])->name('progress-skripsi.file');

        Route::get('/milestones', [MilestoneController::class, 'index'])->name('milestones.index');
        Route::get('/milestones/create', [MilestoneController::class, 'create'])->name('milestones.create');
        Route::post('/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
        Route::get('/milestones/{milestone}/edit', [MilestoneController::class, 'edit'])->name('milestones.edit');
        Route::patch('/milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    });

    Route::get('/mahasiswa-bimbingan/create', [MahasiswaBimbinganController::class, 'create'])->name('mahasiswa-bimbingan.create');
    Route::post('/mahasiswa-bimbingan/{mahasiswaBimbingan}/assign', [MahasiswaBimbinganController::class, 'assign'])->name('mahasiswa-bimbingan.assign');
    Route::get('/mahasiswa-bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('mahasiswa-bimbingan.index');
    Route::get('/mahasiswa-bimbingan/{mahasiswaBimbingan}', [MahasiswaBimbinganController::class, 'show'])->name('mahasiswa-bimbingan.show');

    Route::get('/laporan-progres', [LaporanProgresController::class, 'index'])->name('laporan-progres.index');

    Route::post('/progress-skripsi/{progress}/approve', [ProgressReviewController::class, 'approve'])->name('progress-skripsi.approve');
    Route::post('/progress-skripsi/{progress}/revise', [ProgressReviewController::class, 'revise'])->name('progress-skripsi.revise');

    Route::middleware('can:access-admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('bimbingan', [BimbinganController::class, 'index'])->name('bimbingan.index');
        Route::patch('bimbingan/{bimbingan}', [BimbinganController::class, 'update'])->name('bimbingan.update');
        Route::resource('dosen', DosenController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
