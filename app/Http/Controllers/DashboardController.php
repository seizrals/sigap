<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\EwsAlert;
use App\Models\IphMingguan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan', 'latitude', 'longitude', 'zona_risiko')
            ->get();

        $alerts = EwsAlert::with('kecamatan')
            ->whereIn('status', ['terbuka', 'diproses'])
            ->orderBy('waktu_terdeteksi', 'desc')
            ->get();

        $komoditasDefisit = DataPangan::where('status_ketersediaan', 'kurang')
            ->orWhere('status_ketersediaan', 'kosong')
            ->distinct('komoditas_id')
            ->count('komoditas_id');

        if ($komoditasDefisit === 0) {
            $komoditasDefisit = DataPangan::where('status_harga', 'harga_tinggi')
                ->distinct('komoditas_id')
                ->count('komoditas_id');
        }

        $kecamatanZonaMerah = Kecamatan::where('zona_risiko', 'merah')->count();

        $dataPangan = DataPangan::with('komoditas', 'kecamatan')
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('tanggal_input', 'desc')
            ->limit(5)
            ->get();

        $dataLpg = DataLpg::with('pangkalanLpg.kecamatan')
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('tanggal_input', 'desc')
            ->limit(5)
            ->get();

        $dataBbm = DataBbm::with('spbu.kecamatan')
            ->where('status_verifikasi', 'disetujui')
            ->orderBy('tanggal_input', 'desc')
            ->limit(5)
            ->get();

        $iphTerbaru = IphMingguan::where('status_verifikasi', 'disetujui')
            ->orderBy('tanggal_publikasi', 'desc')
            ->first();

        $rekomendasiStrategis = $this->generateRekomendasiStrategis($kecamatan, $alerts);

        return view('dashboard.index', compact(
            'kecamatan',
            'komoditasDefisit',
            'kecamatanZonaMerah',
            'dataPangan',
            'dataLpg',
            'dataBbm',
            'iphTerbaru',
            'rekomendasiStrategis'
        ));
    }

    private function generateRekomendasiStrategis($kecamatanList, $alerts)
    {
        $rekomendasi = [];

        foreach ($kecamatanList as $kec) {
            if ($kec->zona_risiko == 'merah') {
                $rekomendasi[] = [
                    'kecamatan' => $kec->nama_kecamatan,
                    'zona' => 'merah',
                    'judul' => 'Operasi Pasar Murah',
                    'detail' => 'Segera koordinasikan operasi pasar murah di Kec. '.$kec->nama_kecamatan.' karena harga pada zona merah. Libatkan Dinas Perdagangan dan pasokan dari wilayah surplus.',
                    'ikon' => 'store',
                    'warna' => 'red',
                ];
                $rekomendasi[] = [
                    'kecamatan' => $kec->nama_kecamatan,
                    'zona' => 'merah',
                    'judul' => 'Intervensi Distribusi Stok',
                    'detail' => 'Alihkan stok dari kecamatan surplus (zona hijau) menuju Kec. '.$kec->nama_kecamatan.' untuk menekan kelangkaan dan harga tinggi.',
                    'ikon' => 'truck',
                    'warna' => 'red',
                ];
            } elseif ($kec->zona_risiko == 'kuning') {
                $rekomendasi[] = [
                    'kecamatan' => $kec->nama_kecamatan,
                    'zona' => 'kuning',
                    'judul' => 'Sidak Lapangan',
                    'detail' => 'Lakukan sidak lapangan di Kec. '.$kec->nama_kecamatan.' untuk memverifikasi kenaikan harga atau penurunan stok yang terindikasi.',
                    'ikon' => 'clipboard',
                    'warna' => 'amber',
                ];
                $rekomendasi[] = [
                    'kecamatan' => $kec->nama_kecamatan,
                    'zona' => 'kuning',
                    'judul' => 'Pantau Pasar Terarah',
                    'detail' => 'Fokuskan pemantauan harga harian di pasar utama Kec. '.$kec->nama_kecamatan.' untuk mencegah masuk zona merah.',
                    'ikon' => 'eye',
                    'warna' => 'amber',
                ];
            }
        }

        foreach ($alerts as $alert) {
            $kec = $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara';
            $rekomendasi[] = [
                'kecamatan' => $kec,
                'zona' => $alert->tingkat_risiko == 'tinggi' ? 'merah' : ($alert->tingkat_risiko == 'sedang' ? 'kuning' : 'hijau'),
                'judul' => 'Tindak Lanjut Alert: '.$alert->judul_alert,
                'detail' => 'Berikan respon cepat terhadap alert di '.$kec.'. '.($alert->analisis_sistem ?? 'Verifikasi data dan koordinasikan dengan pihak terkait.'),
                'ikon' => 'bell',
                'warna' => $alert->tingkat_risiko == 'tinggi' ? 'red' : ($alert->tingkat_risiko == 'sedang' ? 'amber' : 'emerald'),
            ];
        }

        return $rekomendasi;
    }
}
