<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EwsController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\SaranPublikController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/portal-publik', [LoginController::class, 'portalPublik'])->name('portal-publik');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('input')->name('input.')->group(function () {
    Route::get('/pangan-strategis', [InputDataController::class, 'pangan'])->name('pangan');
    Route::get('/lpg', [InputDataController::class, 'lpg'])->name('lpg');
    Route::get('/bbm', [InputDataController::class, 'bbm'])->name('bbm');
    Route::get('/iph-mingguan', [InputDataController::class, 'iph'])->name('iph');
});

Route::prefix('ews')->name('ews.')->group(function () {
    Route::get('/', [EwsController::class, 'index'])->name('index');
    Route::get('/monitor-kabupaten', [EwsController::class, 'monitorKabupaten'])->name('monitor-kabupaten');
});

Route::resource('saran-publik', SaranPublikController::class)->names('saran-publik');
Route::resource('validasi', ValidasiController::class)->names('validasi');

Route::get('/pdf/laporan-monitor', [LaporanController::class, 'cetakLaporan'])
    ->name('pdf.laporan-monitor');
