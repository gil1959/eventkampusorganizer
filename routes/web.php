<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Panitia;
use App\Http\Controllers\Peserta;

/*
|--------------------------------------------------------------------------
| Public / Landing Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/events', [LandingController::class, 'events'])->name('landing.events');
Route::get('/events/{id}', [LandingController::class, 'eventDetail'])->name('landing.event.detail');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:aktor')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:aktor');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth:aktor', 'role.admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // User management
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [Admin\UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/verifikasi', [Admin\UserController::class, 'verifikasi'])->name('users.verifikasi');
    Route::patch('/users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Kategori
    Route::get('/kategoris', [Admin\KategoriController::class, 'index'])->name('kategoris.index');
    Route::post('/kategoris', [Admin\KategoriController::class, 'store'])->name('kategoris.store');
    Route::put('/kategoris/{kategori}', [Admin\KategoriController::class, 'update'])->name('kategoris.update');
    Route::delete('/kategoris/{kategori}', [Admin\KategoriController::class, 'destroy'])->name('kategoris.destroy');

    // Validasi Event + Hapus
    Route::get('/validasi', [Admin\EventValidasiController::class, 'index'])->name('validasi.index');
    Route::get('/validasi/{event}', [Admin\EventValidasiController::class, 'show'])->name('validasi.show');
    Route::post('/validasi/{event}/approve', [Admin\EventValidasiController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi/{event}/reject', [Admin\EventValidasiController::class, 'reject'])->name('validasi.reject');
    Route::delete('/events/{event}', [Admin\EventController::class, 'destroy'])->name('events.destroy');

    // Monitor Pembayaran + Approve/Decline + Kelola Rekening
    Route::get('/pembayaran', [Admin\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{pembayaran}/approve', [Admin\PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('/pembayaran/{pembayaran}/decline', [Admin\PembayaranController::class, 'decline'])->name('pembayaran.decline');
    Route::get('/pembayaran/events/{eventId}/rekening', [Admin\PembayaranController::class, 'rekeningIndex'])->name('pembayaran.rekening.index');
    Route::post('/pembayaran/events/{eventId}/rekening', [Admin\PembayaranController::class, 'rekeningStore'])->name('pembayaran.rekening.store');
    Route::post('/pembayaran/events/{eventId}/rekening/{rekening}/toggle', [Admin\PembayaranController::class, 'rekeningToggle'])->name('pembayaran.rekening.toggle');
    Route::delete('/pembayaran/events/{eventId}/rekening/{rekening}', [Admin\PembayaranController::class, 'rekeningDestroy'])->name('pembayaran.rekening.destroy');
});

/*
|--------------------------------------------------------------------------
| Panitia Routes
|--------------------------------------------------------------------------
*/

Route::prefix('panitia')->name('panitia.')->middleware(['auth:aktor', 'role.panitia'])->group(function () {
    Route::get('/dashboard', [Panitia\DashboardController::class, 'index'])->name('dashboard');

    // Events
    Route::get('/events', [Panitia\EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [Panitia\EventController::class, 'create'])->name('events.create');
    Route::post('/events', [Panitia\EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [Panitia\EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [Panitia\EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [Panitia\EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [Panitia\EventController::class, 'destroy'])->name('events.destroy');

    // Peserta
    Route::get('/events/{eventId}/peserta', [Panitia\PesertaController::class, 'index'])->name('peserta.index');
    Route::patch('/peserta/{pendaftaran}/status', [Panitia\PesertaController::class, 'updateStatus'])->name('peserta.update-status');
    Route::get('/events/{eventId}/absensi', [Panitia\PesertaController::class, 'exportAbsensi'])->name('peserta.absensi');

    // Sertifikat
    Route::get('/events/{eventId}/sertifikat', [Panitia\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::post('/events/{eventId}/sertifikat/generate', [Panitia\SertifikatController::class, 'generate'])->name('sertifikat.generate');
    Route::post('/events/{eventId}/sertifikat/{pendaftaranId}/generate-satu', [Panitia\SertifikatController::class, 'generateSatu'])->name('sertifikat.generate-satu');
    Route::get('/sertifikat/{sertifikatId}/download', [Panitia\SertifikatController::class, 'preview'])->name('sertifikat.preview');

    // Pembayaran
    Route::get('/pembayaran', [Panitia\PembayaranController::class, 'monitoring'])->name('pembayaran.monitoring');
    Route::get('/events/{eventId}/pembayaran', [Panitia\PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/{pembayaran}/approve', [Panitia\PembayaranController::class, 'approve'])->name('pembayaran.approve');
    Route::post('/pembayaran/{pembayaran}/decline', [Panitia\PembayaranController::class, 'decline'])->name('pembayaran.decline');

    // Rekening / E-Wallet
    Route::get('/events/{eventId}/rekening', [Panitia\PembayaranController::class, 'rekeningIndex'])->name('rekening.index');
    Route::post('/events/{eventId}/rekening', [Panitia\PembayaranController::class, 'rekeningStore'])->name('rekening.store');
    Route::post('/events/{eventId}/rekening/{rekening}/toggle', [Panitia\PembayaranController::class, 'rekeningToggle'])->name('rekening.toggle');
    Route::delete('/events/{eventId}/rekening/{rekening}', [Panitia\PembayaranController::class, 'rekeningDestroy'])->name('rekening.destroy');

    // Profile
    Route::get('/profile', [Panitia\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [Panitia\ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Peserta Routes
|--------------------------------------------------------------------------
*/

Route::prefix('peserta')->name('peserta.')->middleware(['auth:aktor', 'role.peserta'])->group(function () {
    Route::get('/home', [Peserta\HomeController::class, 'index'])->name('home');
    Route::get('/events/{id}', [Peserta\EventController::class, 'show'])->name('event.show');

    // Pendaftaran (form + submit — 1 langkah untuk paid/free)
    Route::get('/events/{eventId}/daftar', [Peserta\PendaftaranController::class, 'formDaftar'])->name('daftar.form');
    Route::post('/events/{eventId}/daftar', [Peserta\PendaftaranController::class, 'daftar'])->name('daftar');
    Route::get('/riwayat', [Peserta\PendaftaranController::class, 'riwayat'])->name('riwayat');
    Route::patch('/pendaftaran/{pendaftaran}/batal', [Peserta\PendaftaranController::class, 'batalkan'])->name('pendaftaran.batal');

    // Route lama pembayaran (keep untuk backward-compat, redirect ke riwayat)
    Route::get('/events/{eventId}/bayar', fn($eventId) => redirect()->route('peserta.daftar.form', $eventId))->name('pembayaran.pilih');

    // Rekening (Peserta bisa tambah/kelola rekening di event yang mereka ikut)
    Route::get('/events/{eventId}/rekening', [Peserta\RekeningController::class, 'index'])->name('rekening.index');
    Route::post('/events/{eventId}/rekening', [Peserta\RekeningController::class, 'store'])->name('rekening.store');
    Route::post('/events/{eventId}/rekening/{rekening}/toggle', [Peserta\RekeningController::class, 'toggle'])->name('rekening.toggle');
    Route::delete('/events/{eventId}/rekening/{rekening}', [Peserta\RekeningController::class, 'destroy'])->name('rekening.destroy');

    // Sertifikat
    Route::get('/sertifikat', [Peserta\SertifikatController::class, 'index'])->name('sertifikat');
    Route::get('/sertifikat/{id}/download', [Peserta\SertifikatController::class, 'download'])->name('sertifikat.download');

    // Profile
    Route::get('/profile', [Peserta\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [Peserta\ProfileController::class, 'update'])->name('profile.update');
});
