<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EwsController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SaranPublikController;
use App\Http\Controllers\ValidasiController;
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
        Route::put('/pangan-strategis/{dataPangan}', [InputDataController::class, 'updatePangan'])->name('pangan.update');
        Route::get('/lpg', [InputDataController::class, 'lpg'])->name('lpg');
        Route::post('/lpg', [InputDataController::class, 'storeLpg'])->name('lpg.store');
        Route::get('/bbm', [InputDataController::class, 'bbm'])->name('bbm');
        Route::post('/bbm', [InputDataController::class, 'storeBbm'])->name('bbm.store');
        Route::get('/iph-mingguan', [InputDataController::class, 'iph'])->name('iph.index');
        Route::post('/iph-mingguan', [InputDataController::class, 'storeIph'])->name('iph.store');
        Route::delete('/iph-mingguan/{iphMingguan}', [InputDataController::class, 'destroyIphMingguan'])->name('iph.destroy');
        Route::get('/iph-mingguan/export-excel', [InputDataController::class, 'exportIphMingguanExcel'])->name('iph.export-excel');
        Route::get('/iph-bulanan', [InputDataController::class, 'iph_bulanan'])->name('iph_bulanan');
        Route::post('/iph-bulanan', [InputDataController::class, 'storeIphBulanan'])->name('iph_bulanan.store');
        Route::delete('/iph-bulanan/{iphBulanan}', [InputDataController::class, 'destroyIphBulanan'])->name('iph_bulanan.destroy');
        Route::get('/iph-bulanan/export-excel', [InputDataController::class, 'exportIphBulananExcel'])->name('iph_bulanan.export-excel');
    });

    Route::prefix('ews')->name('ews.')->group(function () {
        Route::get('/', [EwsController::class, 'index'])->name('index');
        Route::post('/store', [EwsController::class, 'store'])->name('store');
        Route::post('/{alert}/rekomendasi', [EwsController::class, 'storeRekomendasi'])->name('rekomendasi.store');
        Route::get('/monitor-kabupaten', [EwsController::class, 'monitorKabupaten'])->name('monitor-kabupaten');
    });

    Route::resource('saran-publik', SaranPublikController::class)->names('saran-publik')->except(['store']);
    Route::resource('validasi', ValidasiController::class)->names('validasi');

    Route::get('/pdf/laporan-monitor', [LaporanController::class, 'cetakLaporan'])
        ->name('pdf.laporan-monitor');
});
