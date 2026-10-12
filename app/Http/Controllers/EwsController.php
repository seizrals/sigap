<?php

namespace App\Http\Controllers;

use App\Models\EwsAlert;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EwsController extends Controller
{
    public function index()
    {
        $alerts = EwsAlert::with('kecamatan')
            ->whereIn('status', ['terbuka', 'diproses'])
            ->orderBy('waktu_terdeteksi', 'desc')
            ->get();

        $kecamatanList = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan', 'zona_risiko')
            ->orderBy('nama_kecamatan')
            ->get();

        $kecamatanOptions = $kecamatanList;

        $rekomendasiStrategis = $this->generateRekomendasiStrategis($kecamatanList, $alerts);

        return view('ews.index', compact('alerts', 'kecamatanList', 'kecamatanOptions', 'rekomendasiStrategis'));
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:pangan,lpg,bbm,multikomoditas',
            'tingkat_risiko' => 'required|in:rendah,sedang,tinggi,kritis',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'judul_alert' => 'required|string|max:200',
            'analisis_sistem' => 'required|string',
        ]);

        EwsAlert::create([
            'kode_alert' => 'EWS-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
            'sumber_deteksi' => 'manual_admin',
            'kategori' => $validated['kategori'],
            'tingkat_risiko' => $validated['tingkat_risiko'],
            'status' => 'terbuka',
            'kecamatan_id' => $validated['kecamatan_id'],
            'judul_alert' => $validated['judul_alert'],
            'analisis_sistem' => $validated['analisis_sistem'],
            'dibuat_oleh' => Auth::id(),
            'waktu_terdeteksi' => now(),
        ]);

        return redirect()->route('ews.index')
            ->with('success', 'Peringatan baru berhasil dibuat.');
    }

    public function monitorKabupaten()
    {
        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan', 'latitude', 'longitude', 'zona_risiko')
            ->orderBy('nama_kecamatan')
            ->get();

        return view('ews.monitor-kabupaten', compact('kecamatan'));
    }
}
