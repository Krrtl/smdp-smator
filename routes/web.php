<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\SiswaController;
// //  Redirect ke login kalau akses root
// Route::get('/', function () {
//     return redirect()->route('login.form');
// });

// ==========================
//  PORTAL PUBLIK (TANPA LOGIN)
// ==========================

Route::controller(PortalController::class)->group(function () {
    Route::get('/', 'home')->name('portal.home');
    Route::get('/about', 'about')->name('portal.about');
    Route::get('/data-pelanggaran', 'pelanggaran')->name('portal.pelanggaran');
});


//  Login routes (tanpa middleware)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================  ROUTE YANG PERLU LOGIN ====================
//  Semua route di bawah ini hanya untuk user login
Route::middleware(['web', 'auth.session'])->group(function () {
    // ===== ROUTE ADMIN =====
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detail/{jabatan}', [AdminController::class, 'detail'])->name('dashboard.detail');

    Route::get('/kelola-user', [UserController::class, 'index'])->name('kelola.user');
    Route::get('/kelola-user/create', [UserController::class, 'create'])->name('kelola.user.create');
    Route::post('/kelola-user', [UserController::class, 'store'])->name('kelola.user.store');
    Route::get('/kelola-user/{id}/edit', [UserController::class, 'edit'])->name('kelola.user.edit');
    Route::post('/kelola-user/{id}/update', [UserController::class, 'update'])->name('kelola.user.update');
    Route::delete('/kelola-user/{id}', [UserController::class, 'destroy'])->name('kelola.user.destroy');

    Route::get('/upload', [DokumenController::class, 'index'])->name('upload.index');
    Route::get('/upload/create', [DokumenController::class, 'create'])->name('upload.create');
    Route::post('/upload', [DokumenController::class, 'store'])->name('upload.store');
    Route::get('/upload/{id}/edit', [DokumenController::class, 'edit'])->name('upload.edit');
    Route::post('/upload/{id}/update', [DokumenController::class, 'update'])->name('upload.update');
    Route::delete('/upload/{id}', [DokumenController::class, 'destroy'])->name('upload.destroy');

    Route::get('/dokumen', [DokumenController::class, 'showAll'])->name('dokumen.index');
    Route::get('/dokumen/{id}/download', [DokumenController::class, 'download'])->name('dokumen.download');
    Route::get('/dokumen/{id}/view', [DokumenController::class, 'view'])->name('dokumen.view');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetakPDF'])->name('laporan.cetak');
    
    // ===== ROUTE PELANGGARAN (hanya admin + tatatertib) =====
    Route::middleware(\App\Http\Middleware\AuthorizePelanggaran::class)->group(function () {
        Route::get('/pelanggaran', [PelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::get('/pelanggaran/create', [PelanggaranController::class, 'create'])->name('pelanggaran.create');
        Route::post('/pelanggaran', [PelanggaranController::class, 'store'])->name('pelanggaran.store');
        Route::patch('/pelanggaran/{id}/toggle', [PelanggaranController::class, 'toggle'])->name('pelanggaran.toggle');
        Route::delete('/pelanggaran/{id}', [PelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');
        Route::patch('/pelanggaran/{id}/keterangan', [PelanggaranController::class, 'updateKeterangan'])
                ->name('pelanggaran.updateKeterangan');
});

  // ===== ROUTE ADMIN SISWA =====
Route::prefix('siswa')->group(function () {

    // 📄 Halaman daftar siswa
    Route::get('/', [SiswaController::class, 'index'])
        ->name('siswa.index');

    // 📥 Import Excel/CSV
    Route::get('/import', [SiswaController::class, 'showImportForm'])
        ->name('siswa.import.form');
    Route::post('/import', [SiswaController::class, 'import'])
        ->name('siswa.import');

    // ⬆️ Naik kelas otomatis
    Route::post('/promote', [SiswaController::class, 'promoteClass'])
        ->name('siswa.promote');

    // 🎓 Luluskan siswa XII
    Route::post('/graduate', [SiswaController::class, 'markGraduated'])
        ->name('siswa.graduate');

    // ❌ Drop Out / Keluar
    Route::post('/{id}/dropout', [SiswaController::class, 'markDropout'])
        ->name('siswa.dropout');

    Route::get('/siswa-arsip', [SiswaController::class, 'arsip'])
         ->name('siswa.arsip');


    // === 📌 PROMOTE MANUAL ===

    // 🔍 Halaman pilih siswa untuk dipindah kelas
    Route::get('/promote/manual', [SiswaController::class, 'showManualPromoteForm'])
        ->name('siswa.promote.manual');

    // ✔ Submit pindah kelas manual
    Route::post('/promote/manual/submit', [SiswaController::class, 'submitManualPromote'])
        ->name('siswa.promote.manual.submit');

});


    // Ubah password
    Route::get('/ubah-password', [AuthController::class, 'showPasswordForm'])->name('password.form');
    Route::post('/ubah-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

//  ROUTE USER (TANPA DITARUH DALAM ARRAY MIDDLEWARE)
Route::middleware('auth.session')->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    
  //  ROUTE USER UPLOAD
Route::prefix('user/upload')->name('user.upload.')->group(function () {
    Route::get('/', [DokumenController::class, 'userIndex'])->name('index');
    Route::get('/create', [DokumenController::class, 'userCreate'])->name('create');
    Route::post('/', [DokumenController::class, 'userStore'])->name('store');
    Route::get('/{id}/edit', [DokumenController::class, 'userEdit'])->name('edit');
    Route::post('/{id}/update', [DokumenController::class, 'userUpdate'])->name('update');
    Route::delete('/{id}', [DokumenController::class, 'userDestroy'])->name('destroy');
});

    Route::get('/user/dokumen', [DokumenController::class, 'userDokumen'])->name('user.dokumen');

});