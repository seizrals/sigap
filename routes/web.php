<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EwsController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\SaranPublikController;
use App\Http\Controllers\ValidasiController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'portalPublik'])->name('portal-publik');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/saran-publik', [SaranPublikController::class, 'store'])->name('saran-publik.store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('input')->name('input.')->group(function () {
        Route::get('/pangan-strategis', [InputDataController::class, 'pangan'])->name('pangan');
        Route::post('/pangan-strategis', [InputDataController::class, 'storePangan'])->name('pangan.store');
        Route::get('/lpg', [InputDataController::class, 'lpg'])->name('lpg');
        Route::post('/lpg', [InputDataController::class, 'storeLpg'])->name('lpg.store');
        Route::get('/bbm', [InputDataController::class, 'bbm'])->name('bbm');
        Route::post('/bbm', [InputDataController::class, 'storeBbm'])->name('bbm.store');
        Route::get('/iph-mingguan', [InputDataController::class, 'iph'])->name('iph.index');
        Route::post('/iph-mingguan', [InputDataController::class, 'storeIph'])->name('iph.store');
        Route::get('/iph/export-spreadsheet', [InputDataController::class, 'exportToGoogleSheet'])->name('iph.export-spreadsheet');
        Route::get('/iph-bulanan', [InputDataController::class, 'iph_bulanan'])->name('iph_bulanan');
    });

    Route::prefix('ews')->name('ews.')->group(function () {
        Route::get('/', [EwsController::class, 'index'])->name('index');
        Route::post('/store', [EwsController::class, 'store'])->name('store');
        Route::get('/monitor-kabupaten', [EwsController::class, 'monitorKabupaten'])->name('monitor-kabupaten');
    });

    Route::resource('saran-publik', SaranPublikController::class)->names('saran-publik')->except(['store']);
    Route::resource('validasi', ValidasiController::class)->names('validasi');

    Route::get('/pdf/laporan-monitor', [LaporanController::class, 'cetakLaporan'])
        ->name('pdf.laporan-monitor');
});
