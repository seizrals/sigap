<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\EwsAlert;
use App\Models\EwsRekomendasi;
use App\Models\IphBulanan;
use App\Models\IphMingguan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'pangan_kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
            'pangan_komoditas_id' => ['nullable', 'integer', 'exists:komoditas,id'],
            'periode_sebelumnya' => ['nullable', 'date'],
            'periode_saat_ini' => ['nullable', 'date'],
            'lpg_kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
            'lpg_tanggal' => ['nullable', 'date'],
            'bbm_jenis' => ['nullable', 'string', Rule::in(['Pertalite (JBKP)', 'Bio Solar', 'Pertalite', 'all', ''])],
            'bbm_tanggal' => ['nullable', 'date'],
        ]);

        $periodeSaatIni = Carbon::parse($filters['periode_saat_ini'] ?? now()->toDateString())->toDateString();
        $periodeSebelumnya = Carbon::parse(
            $filters['periode_sebelumnya'] ?? Carbon::parse($periodeSaatIni)->subDay()->toDateString()
        )->toDateString();
        $legacyPreviousPeriodFallbackAllowed = Carbon::parse($periodeSaatIni)->subDay()->toDateString() === $periodeSebelumnya;

        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan', 'latitude', 'longitude', 'zona_risiko')
            ->get();

        $komoditasPangan = Komoditas::where('is_active', true)
            ->where('is_strategis', true)
            ->select('id', 'nama_komoditas', 'satuan')
            ->orderBy('nama_komoditas')
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

        $dataPangan = DataPangan::with([
            'komoditas:id,nama_komoditas,satuan',
            'kecamatan:id,nama_kecamatan',
        ])
            ->where(function ($query) use ($periodeSebelumnya, $periodeSaatIni): void {
                $query->whereDate('tanggal_input', $periodeSebelumnya)
                    ->orWhereDate('tanggal_input', $periodeSaatIni)
                    ->orWhereDate('tanggal_input_sebelumnya', $periodeSebelumnya);
            })
            ->when($filters['pangan_kecamatan_id'] ?? null, fn ($query, $id) => $query->where('kecamatan_id', $id))
            ->when($filters['pangan_komoditas_id'] ?? null, fn ($query, $id) => $query->where('komoditas_id', $id))
            ->orderByDesc('created_at')
            ->get();

        $dataPangan = $dataPangan
            ->groupBy(fn (DataPangan $record): string => $record->kecamatan_id.'-'.$record->komoditas_id)
            ->map(function ($records) use ($periodeSebelumnya, $periodeSaatIni, $legacyPreviousPeriodFallbackAllowed): array {
                $recordSebelumnya = $records->first(
                    fn (DataPangan $record): bool => $record->tanggal_input?->toDateString() === $periodeSebelumnya
                );
                $recordSaatIni = $records->first(
                    fn (DataPangan $record): bool => $record->tanggal_input?->toDateString() === $periodeSaatIni
                );
                $recordReferensi = $recordSaatIni ?? $recordSebelumnya;
                $periodeSebelumnyaSesuai = $recordSaatIni?->tanggal_input_sebelumnya?->toDateString() === $periodeSebelumnya
                    || ($recordSaatIni?->tanggal_input_sebelumnya === null && $legacyPreviousPeriodFallbackAllowed);
                $hargaSebelumnya = $recordSebelumnya?->harga_hari_ini
                    ?? ($periodeSebelumnyaSesuai ? $recordSaatIni?->harga_hari_sebelumnya : null);
                $hargaSaatIni = $recordSaatIni?->harga_hari_ini;
                $perubahanPersen = $hargaSebelumnya > 0 && $hargaSaatIni !== null
                    ? round((((float) $hargaSaatIni - (float) $hargaSebelumnya) / (float) $hargaSebelumnya) * 100, 2)
                    : null;

                return [
                    'kecamatan' => $recordReferensi?->kecamatan?->nama_kecamatan ?? '-',
                    'komoditas' => $recordReferensi?->komoditas?->nama_komoditas ?? '-',
                    'satuan' => $recordReferensi?->komoditas?->satuan ?? $recordReferensi?->satuan_stok ?? '-',
                    'stok' => $recordSaatIni?->stok_tersedia ?? $recordSebelumnya?->stok_tersedia,
                    'harga_lalu' => $hargaSebelumnya,
                    'harga_saat_ini' => $hargaSaatIni,
                    'perubahan_persen' => $perubahanPersen,
                    'status_ketersediaan' => $recordSaatIni?->status_ketersediaan ?? $recordSebelumnya?->status_ketersediaan,
                    'status_verifikasi' => $recordSaatIni?->status_verifikasi ?? $recordSebelumnya?->status_verifikasi,
                ];
            })
            ->sortBy(fn (array $row): string => $row['kecamatan'].'-'.$row['komoditas'])
            ->values();

        $lpgTanggal = $filters['lpg_tanggal'] ?? null;
        $lpgKecamatanId = $filters['lpg_kecamatan_id'] ?? null;
        $dataLpg = DataLpg::with('kecamatan')
            ->when($lpgTanggal, fn ($query, $date) => $query->whereDate('tanggal_input', $date))
            ->when($lpgKecamatanId, fn ($query, $id) => $query->where('kecamatan_id', $id))
            ->orderBy('tanggal_input', 'desc')
            ->orderByDesc('created_at')
            ->get();

        $bbmTanggal = $filters['bbm_tanggal'] ?? null;
        $bbmJenis = $filters['bbm_jenis'] ?? null;
        $dataBbm = DataBbm::with(['kecamatan', 'spbu.kecamatan'])
            ->when($bbmTanggal, fn ($query, $date) => $query->whereDate('tanggal_input', $date))
            ->when($bbmJenis, function ($query, $jenis) {
                if (in_array($jenis, ['Pertalite', 'Pertalite (JBKP)'], true)) {
                    $query->whereIn('jenis_bbm', ['Pertalite (JBKP)', 'Pertalite']);
                } elseif ($jenis !== '' && $jenis !== 'all') {
                    $query->where('jenis_bbm', $jenis);
                }
            })
            ->orderBy('tanggal_input', 'desc')
            ->orderByDesc('created_at')
            ->get();

        $iphTerbaru = IphMingguan::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('minggu_ke', 'desc')
            ->first();

        // Data IPH Mingguan untuk Monitoring & Grafik
        $filterTahunMingguan = $request->query('iph_mingguan_tahun', (string) date('Y'));
        $filterBulanMingguan = $request->query('iph_mingguan_bulan');
        $filterDariMinggu = $request->query('iph_mingguan_dari_minggu', $request->query('iph_mingguan_minggu'));
        $filterSampaiMinggu = $request->query('iph_mingguan_sampai_minggu', $request->query('iph_mingguan_minggu'));
        $filterMingguMingguan = $request->query('iph_mingguan_minggu');

        $toWeekInt = function ($w) {
            if ($w === null || $w === '') return null;
            return match (strtoupper(trim((string) $w))) {
                '1', 'I', 'MINGGU I' => 1,
                '2', 'II', 'MINGGU II' => 2,
                '3', 'III', 'MINGGU III' => 3,
                '4', 'IV', 'MINGGU IV' => 4,
                '5', 'V', 'MINGGU V' => 5,
                default => is_numeric($w) ? (int) $w : null,
            };
        };

        $wDari = $toWeekInt($filterDariMinggu);
        $wSampai = $toWeekInt($filterSampaiMinggu);

        if ($wDari !== null && $wSampai !== null && $wDari > $wSampai) {
            [$wDari, $wSampai] = [$wSampai, $wDari];
        }

        $romawiReverse = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V'];
        $validWeeks = [];
        if ($wDari !== null || $wSampai !== null) {
            $minW = $wDari ?? 1;
            $maxW = $wSampai ?? 5;
            for ($i = $minW; $i <= $maxW; $i++) {
                if (isset($romawiReverse[$i])) {
                    $validWeeks[] = $romawiReverse[$i];
                    $validWeeks[] = (string) $i;
                }
            }
        }

        $iphMingguanQuery = IphMingguan::query()
            ->when($filterTahunMingguan, fn ($q) => $q->where('tahun', $filterTahunMingguan))
            ->when($filterBulanMingguan, function ($q) use ($filterBulanMingguan) {
                if (is_numeric($filterBulanMingguan)) {
                    $q->where('bulan', (int) $filterBulanMingguan);
                } else {
                    [$bNum] = InputDataController::parseBulan($filterBulanMingguan);
                    $q->where('bulan', $bNum);
                }
            })
            ->when(!empty($validWeeks), fn ($q) => $q->whereIn('minggu_ke', $validWeeks))
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->orderBy('minggu_ke', 'asc');
        $iphMingguanList = $iphMingguanQuery->get();

        $chartMingguanLabels = [];
        $chartMingguanValues = [];
        foreach ($iphMingguanList as $row) {
            [, $namaBln] = InputDataController::parseBulan($row->bulan);
            $chartMingguanLabels[] = 'M-' . $row->minggu_ke . ' ' . substr($namaBln, 0, 3);
            $chartMingguanValues[] = (float) $row->iph_kabupaten;
        }

        if (empty($chartMingguanLabels)) {
            $chartMingguanLabels = ['Minggu III', 'Minggu IV', 'Minggu I', 'Minggu II'];
            $chartMingguanValues = [1.85, 2.10, 1.95, 2.33];
        }

        $iphMingguanTerbaru = $iphMingguanList->last() ?? $iphTerbaru;

        // Data IPH Bulanan untuk Monitoring & Grafik
        $filterTahunBulanan = $request->query('iph_bulanan_tahun', (string) date('Y'));
        $filterBulanBulanan = $request->query('iph_bulanan_bulan');
        $filterDariBulan = $request->query('iph_bulanan_dari_bulan');
        $filterSampaiBulan = $request->query('iph_bulanan_sampai_bulan');

        $toMonthInt = function ($m) {
            if ($m === null || $m === '') return null;
            if (is_numeric($m)) return max(1, min(12, (int) $m));
            [$num] = InputDataController::parseBulan($m);
            return $num;
        };

        $mDari = $toMonthInt($filterDariBulan);
        $mSampai = $toMonthInt($filterSampaiBulan);

        if ($filterBulanBulanan !== null && $filterBulanBulanan !== '') {
            $mDari = $toMonthInt($filterBulanBulanan);
            $mSampai = $mDari;
        }

        if ($mDari !== null && $mSampai !== null && $mDari > $mSampai) {
            [$mDari, $mSampai] = [$mSampai, $mDari];
        }

        $iphBulananQuery = IphBulanan::query()
            ->when($filterTahunBulanan, fn ($q) => $q->where('tahun', $filterTahunBulanan))
            ->when($mDari !== null && $mSampai !== null, fn ($q) => $q->whereBetween('bulan', [$mDari, $mSampai]))
            ->when($mDari !== null && $mSampai === null, fn ($q) => $q->where('bulan', '>=', $mDari))
            ->when($mDari === null && $mSampai !== null, fn ($q) => $q->where('bulan', '<=', $mSampai))
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc');
        $iphBulananList = $iphBulananQuery->get();

        $chartBulananLabels = [];
        $chartBulananValues = [];
        foreach ($iphBulananList as $row) {
            $chartBulananLabels[] = $row->nama_bulan;
            $chartBulananValues[] = (float) $row->iph_bulanan;
        }

        if (empty($chartBulananLabels)) {
            $chartBulananLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
            $chartBulananValues = [1.20, 1.45, 1.80, 2.10, 1.85, 1.60];
        }

        $iphBulananTerbaru = $iphBulananList->last()
            ?? IphBulanan::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->first();

        $daftarTahunMingguan = IphMingguan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun')->all();
        if (!in_array((int) date('Y'), $daftarTahunMingguan, true)) {
            array_unshift($daftarTahunMingguan, (int) date('Y'));
        }

        $daftarTahunBulanan = IphBulanan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun')->all();
        if (!in_array((int) date('Y'), $daftarTahunBulanan, true)) {
            array_unshift($daftarTahunBulanan, (int) date('Y'));
        }

        $rekomendasiPasar = EwsRekomendasi::with(['ewsAlert.kecamatan'])
            ->where('tipe_rekomendasi', 'operasi_pasar')
            ->latest()
            ->get();

        $rekomendasiPeta = $rekomendasiPasar
            ->filter(fn (EwsRekomendasi $rekomendasi): bool => $rekomendasi->ewsAlert?->latitude !== null
                && $rekomendasi->ewsAlert?->longitude !== null)
            ->map(function (EwsRekomendasi $rekomendasi): array {
                $alert = $rekomendasi->ewsAlert;
                $zona = match ($alert->tingkat_risiko) {
                    'rendah' => 'hijau',
                    'sedang' => 'kuning',
                    default => 'merah',
                };

                return [
                    'latitude' => (float) $alert->latitude,
                    'longitude' => (float) $alert->longitude,
                    'zona' => $zona,
                    'judul' => $rekomendasi->judul_rekomendasi,
                    'alert' => $alert->judul_alert,
                    'kecamatan' => $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara',
                    'detail' => $rekomendasi->detail_rekomendasi,
                ];
            })
            ->values();

        $rekomendasiStrategis = array_merge(
            $rekomendasiPasar->map(function (EwsRekomendasi $rekomendasi): array {
                $alert = $rekomendasi->ewsAlert;
                $zona = match ($alert?->tingkat_risiko) {
                    'rendah' => 'hijau',
                    'sedang' => 'kuning',
                    default => 'merah',
                };

                return [
                    'kecamatan' => $alert?->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara',
                    'zona' => $zona,
                    'judul' => $rekomendasi->judul_rekomendasi,
                    'detail' => $rekomendasi->detail_rekomendasi,
                    'ikon' => 'store',
                    'warna' => $zona === 'hijau' ? 'emerald' : ($zona === 'kuning' ? 'amber' : 'red'),
                ];
            })->all(),
            $this->generateRekomendasiStrategis($kecamatan, $alerts)
        );

        return view('dashboard.index', compact(
            'kecamatan',
            'komoditasDefisit',
            'kecamatanZonaMerah',
            'dataPangan',
            'dataLpg',
            'dataBbm',
            'lpgTanggal',
            'bbmTanggal',
            'bbmJenis',
            'komoditasPangan',
            'iphTerbaru',
            'iphMingguanTerbaru',
            'iphBulananTerbaru',
            'chartMingguanLabels',
            'chartMingguanValues',
            'chartBulananLabels',
            'chartBulananValues',
            'filterTahunMingguan',
            'filterBulanMingguan',
            'filterDariMinggu',
            'filterSampaiMinggu',
            'filterMingguMingguan',
            'filterTahunBulanan',
            'filterDariBulan',
            'filterSampaiBulan',
            'filterBulanBulanan',
            'daftarTahunMingguan',
            'daftarTahunBulanan',
            'rekomendasiStrategis',
            'rekomendasiPeta',
            'periodeSebelumnya',
            'periodeSaatIni',
            'filters'
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
