<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\IphBulanan;
use App\Models\IphMingguan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'map_filter_type' => ['nullable', 'string', Rule::in(['pangan', 'lpg', 'bbm'])],
            'map_komoditas_id' => ['nullable', 'integer', 'exists:komoditas,id'],
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

        $kecamatan = Kecamatan::select('id', 'nama_kecamatan', 'latitude', 'longitude', 'zona_risiko')
            ->get();

        $komoditasPangan = Komoditas::select('id', 'nama_komoditas', 'satuan')
            ->orderBy('nama_komoditas')
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

        $mapFilterType = $filters['map_filter_type'] ?? 'pangan';
        $mapKomoditasId = $mapFilterType === 'pangan' ? ($filters['map_komoditas_id'] ?? null) : null;
        $mapFilterKomoditas = $mapKomoditasId ? $komoditasPangan->firstWhere('id', $mapKomoditasId) : null;
        $mapPoints = match ($mapFilterType) {
            'lpg' => $this->buildLpgMapPoints(),
            'bbm' => $this->buildBbmMapPoints(),
            default => $this->buildPanganMapPoints($mapKomoditasId),
        };

        $activePointsCount = $mapPoints->filter(fn ($p) => $p['status_ketersediaan'] !== 'tidak_ada')->count();

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
            'mapFilterType',
            'mapKomoditasId',
            'mapFilterKomoditas',
            'mapPoints',
            'activePointsCount',
            'periodeSebelumnya',
            'periodeSaatIni',
            'filters'
        ));
    }

    private function buildPanganMapPoints(?int $komoditasId = null): Collection
    {
        $allKecamatan = Kecamatan::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $records = DataPangan::with(['komoditas:id,nama_komoditas,satuan'])
            ->when($komoditasId, fn ($query, $id) => $query->where('komoditas_id', $id))
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('kecamatan_id');

        return $allKecamatan->map(function ($kecamatan) use ($records) {
            $kecamatanRecords = $records->get($kecamatan->id, collect());

            $latestRecords = $kecamatanRecords
                ->groupBy('komoditas_id')
                ->map(fn (Collection $commodityRecords) => $commodityRecords->first())
                ->values();

            $status = $this->resolveMapStatus($latestRecords->pluck('status_ketersediaan')->filter()->all());

            return [
                'latitude' => (float) $kecamatan->latitude,
                'longitude' => (float) $kecamatan->longitude,
                'kecamatan' => $kecamatan->nama_kecamatan,
                'kategori' => 'Pangan Strategis',
                'status_ketersediaan' => $status,
                'detail_items' => $latestRecords->isEmpty() ? [[
                    'judul' => 'Informasi',
                    'baris' => ['Belum ada data pangan terbaru untuk wilayah ini.'],
                ]] : $latestRecords->map(function (DataPangan $record): array {
                    $satuan = $record->komoditas?->satuan ?? $record->satuan_stok ?? '';
                    $stok = $record->stok_tersedia !== null
                        ? number_format((float) $record->stok_tersedia, 0, ',', '.').' '.trim($satuan)
                        : '-';
                    $harga = $record->harga_hari_ini !== null
                        ? 'Rp '.number_format((float) $record->harga_hari_ini, 0, ',', '.')
                        : '-';

                    return [
                        'judul' => $record->komoditas?->nama_komoditas ?? 'Komoditas',
                        'baris' => [
                            'Tanggal input: '.($record->tanggal_input?->format('d/m/Y') ?? '-'),
                            'Stok tersedia: '.$stok,
                            'Harga hari ini: '.$harga,
                            'Status ketersediaan: '.$this->formatStatusLabel($record->status_ketersediaan),
                        ],
                    ];
                })->all(),
            ];
        })->values();
    }

    private function buildLpgMapPoints(): Collection
    {
        $allKecamatan = Kecamatan::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $records = DataLpg::orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('kecamatan_id');

        return $allKecamatan->map(function ($kecamatan) use ($records) {
            $kecamatanRecords = $records->get($kecamatan->id, collect());

            $latestRecords = $kecamatanRecords
                ->groupBy('jenis_lpg')
                ->map(fn (Collection $lpgRecords) => $lpgRecords->first())
                ->values();

            $status = $this->resolveMapStatus($latestRecords->pluck('status_ketersediaan')->filter()->all());

            return [
                'latitude' => (float) $kecamatan->latitude,
                'longitude' => (float) $kecamatan->longitude,
                'kecamatan' => $kecamatan->nama_kecamatan,
                'kategori' => 'LPG 3 KG',
                'status_ketersediaan' => $status,
                'detail_items' => $latestRecords->isEmpty() ? [[
                    'judul' => 'Informasi',
                    'baris' => ['Belum ada data LPG terbaru untuk wilayah ini.'],
                ]] : $latestRecords->map(function (DataLpg $record): array {
                    $stok = $record->stok_tersedia_tabung !== null
                        ? number_format((float) $record->stok_tersedia_tabung, 0, ',', '.').' tabung'
                        : '-';
                    $harga = $record->harga_eceran_lapangan !== null
                        ? 'Rp '.number_format((float) $record->harga_eceran_lapangan, 0, ',', '.')
                        : '-';

                    return [
                        'judul' => $record->jenis_lpg ?? 'LPG',
                        'baris' => [
                            'Tanggal input: '.($record->tanggal_input?->format('d/m/Y') ?? '-'),
                            'Stok tersedia: '.$stok,
                            'Distribusi masuk: '.number_format((float) ($record->distribusi_masuk_tabung ?? 0), 0, ',', '.').' tabung',
                            'Distribusi keluar: '.number_format((float) ($record->distribusi_keluar_tabung ?? 0), 0, ',', '.').' tabung',
                            'Harga eceran: '.$harga,
                            'Status ketersediaan: '.$this->formatStatusLabel($record->status_ketersediaan),
                        ],
                    ];
                })->all(),
            ];
        })->values();
    }

    private function buildBbmMapPoints(): Collection
    {
        $allKecamatan = Kecamatan::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $records = DataBbm::with(['spbu:id,nama_spbu'])
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('kecamatan_id');

        return $allKecamatan->map(function ($kecamatan) use ($records) {
            $kecamatanRecords = $records->get($kecamatan->id, collect());

            $latestRecords = $kecamatanRecords
                ->groupBy(fn (DataBbm $record): string => ($record->spbu_id ?? '0').'-'.$record->jenis_bbm)
                ->map(fn (Collection $bbmRecords) => $bbmRecords->first())
                ->values();

            $status = $this->resolveMapStatus($latestRecords->pluck('status_ketersediaan')->filter()->all());

            return [
                'latitude' => (float) $kecamatan->latitude,
                'longitude' => (float) $kecamatan->longitude,
                'kecamatan' => $kecamatan->nama_kecamatan,
                'kategori' => 'BBM Subsidi',
                'status_ketersediaan' => $status,
                'detail_items' => $latestRecords->isEmpty() ? [[
                    'judul' => 'Informasi',
                    'baris' => ['Belum ada data BBM terbaru untuk wilayah ini.'],
                ]] : $latestRecords->map(function (DataBbm $record): array {
                    $stok = $record->stok_tersedia_kl !== null
                        ? number_format((float) $record->stok_tersedia_kl, 3, ',', '.').' KL'
                        : '-';
                    $harga = $record->harga_jual_per_liter !== null
                        ? 'Rp '.number_format((float) $record->harga_jual_per_liter, 0, ',', '.')
                        : '-';

                    return [
                        'judul' => trim(($record->jenis_bbm ?? 'BBM').' - '.($record->spbu?->nama_spbu ?? 'SPBU')),
                        'baris' => [
                            'Tanggal input: '.($record->tanggal_input?->format('d/m/Y') ?? '-'),
                            'Stok tersedia: '.$stok,
                            'Status antrean: '.$this->formatStatusLabel($record->status_antrean),
                            'Harga jual: '.$harga,
                            'Status ketersediaan: '.$this->formatStatusLabel($record->status_ketersediaan),
                        ],
                    ];
                })->all(),
            ];
        })->values();
    }

    private function resolveMapStatus(array $statuses): string
    {
        $statuses = array_filter($statuses);

        if ($statuses === []) {
            return 'tidak_ada';
        }

        usort($statuses, fn (string $left, string $right): int => $this->mapStatusRank($right) <=> $this->mapStatusRank($left));

        return $statuses[0];
    }

    private function mapStatusRank(?string $status): int
    {
        return match (strtolower((string) $status)) {
            'tidak_ada' => 0,
            'aman' => 1,
            'waspada' => 2,
            'kurang', 'menipis' => 3,
            'kosong', 'kritis' => 4,
            default => 0,
        };
    }

    private function formatStatusLabel(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'aman' => 'Aman',
            'waspada' => 'Waspada',
            'kurang' => 'Kurang',
            'menipis' => 'Menipis',
            'kosong' => 'Kosong',
            'kritis' => 'Kritis',
            'tidak_ada' => 'Tidak Ada',
            '' => '-',
            default => ucwords(str_replace('_', ' ', (string) $status)),
        };
    }
}
