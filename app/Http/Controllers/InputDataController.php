<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\IphBulanan;
use App\Models\IphMingguan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Services\SimpleExcelExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InputDataController extends Controller
{
    public function pangan()
    {
        $kecamatan = Kecamatan::where('is_active', true)->orderBy('nama_kecamatan')->get();
        $komoditas = Komoditas::where('is_active', true)
            ->where('is_strategis', true)
            ->orderBy('nama_komoditas')
            ->get();
        $dataPangan = DataPangan::with(['kecamatan', 'komoditas'])
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('input.pangan', compact('kecamatan', 'komoditas', 'dataPangan'));
    }

    public function storePangan(Request $request)
    {
        $entries = $this->validatePanganEntries($request);
        $periodeSebelumnya = $request->input('periode_sebelumnya');
        $periodeSaatIni = $request->input('periode_saat_ini');

        DB::transaction(function () use ($entries, $periodeSebelumnya, $periodeSaatIni): void {
            foreach ($entries as $entry) {
                DataPangan::create([
                    ...$entry,
                    'tanggal_input' => $periodeSaatIni,
                    'tanggal_input_sebelumnya' => $periodeSebelumnya,
                    ...$this->calculatePanganChanges($entry),
                    'user_id' => Auth::id() ?? 1,
                    'status_verifikasi' => 'pending',
                    'keterangan_lapangan' => null,
                ]);
            }
        });

        return redirect()->route('input.pangan')->with('success', count($entries).' data pangan berhasil disimpan dan menunggu validasi.');
    }

    public function updatePangan(Request $request, DataPangan $dataPangan)
    {
        $entries = $this->validatePanganEntries($request);
        if (count($entries) !== 1) {
            throw ValidationException::withMessages([
                'entries' => 'Edit hanya dapat mengubah satu komoditas.',
            ]);
        }

        $entry = reset($entries);
        if ((int) $entry['komoditas_id'] !== (int) $dataPangan->komoditas_id) {
            throw ValidationException::withMessages([
                'entries' => 'Komoditas pada data edit tidak sesuai.',
            ]);
        }

        $entry['tanggal_input'] = $request->input('periode_saat_ini');
        $entry['tanggal_input_sebelumnya'] = $request->input('periode_sebelumnya');

        $dataPangan->update([
            ...$entry,
            ...$this->calculatePanganChanges($entry),
            'status_verifikasi' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'catatan_verifikasi' => null,
            'keterangan_lapangan' => null,
        ]);

        return redirect()->route('input.pangan')->with('success', 'Data pangan berhasil diperbarui dan menunggu validasi ulang.');
    }

    private function validatePanganEntries(Request $request): array
    {
        $entries = $request->input('entries');
        if (! is_array($entries)) {
            $entries = [];
        }

        $entries = array_filter($entries, function (mixed $entry): bool {
            if (! is_array($entry)) {
                return false;
            }

            foreach (['kecamatan_id', 'harga_hari_sebelumnya', 'harga_hari_ini', 'stok_tersedia', 'sumber_distribusi_masuk', 'status_ketersediaan'] as $field) {
                if (filled($entry[$field] ?? null)) {
                    return true;
                }
            }

            return false;
        });

        if ($entries === []) {
            throw ValidationException::withMessages([
                'entries' => 'Isi minimal satu baris komoditas sebelum menyimpan.',
            ]);
        }

        $request->merge(['entries' => $entries]);

        $validated = $request->validate([
            'periode_sebelumnya' => ['required', 'date'],
            'periode_saat_ini' => ['required', 'date', 'after_or_equal:periode_sebelumnya'],
            'entries' => ['required', 'array', 'min:1'],
            'entries.*' => ['required', 'array:komoditas_id,kecamatan_id,harga_hari_sebelumnya,harga_hari_ini,stok_tersedia,satuan_stok,sumber_distribusi_masuk,status_ketersediaan'],
            'entries.*.komoditas_id' => [
                'required',
                'distinct',
                Rule::exists('komoditas', 'id')->where('is_active', true)->where('is_strategis', true),
            ],
            'entries.*.kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'entries.*.harga_hari_sebelumnya' => ['nullable', 'numeric', 'min:0'],
            'entries.*.harga_hari_ini' => ['required', 'numeric', 'min:0'],
            'entries.*.stok_tersedia' => ['nullable', 'numeric', 'min:0'],
            'entries.*.satuan_stok' => ['nullable', 'string', 'max:30'],
            'entries.*.sumber_distribusi_masuk' => ['nullable', 'string', 'max:255'],
            'entries.*.status_ketersediaan' => ['required', 'in:aman,waspada,kurang,kosong'],
        ]);

        return $validated['entries'];
    }

    private function calculatePanganChanges(array $validated): array
    {
        $hargaSebelumnya = (float) ($validated['harga_hari_sebelumnya'] ?? $validated['harga_hari_ini']);
        $perubahanNominal = (float) $validated['harga_hari_ini'] - $hargaSebelumnya;
        $perubahanPersen = $hargaSebelumnya > 0 ? ($perubahanNominal / $hargaSebelumnya) * 100 : 0;

        $statusHarga = match (true) {
            $perubahanPersen > 25 => 'harga_tinggi',
            $perubahanPersen > 10 => 'naik_tinggi',
            $perubahanPersen > 0 => 'naik_wajar',
            $perubahanPersen < 0 => 'turun',
            default => 'stabil',
        };

        return [
            'harga_hari_sebelumnya' => $hargaSebelumnya,
            'perubahan_harga_nominal' => $perubahanNominal,
            'perubahan_harga_persen' => round($perubahanPersen, 2),
            'status_harga' => $statusHarga,
        ];
    }

    public function lpg()
    {
        $kecamatan = Kecamatan::where('is_active', true)
            ->orderBy('nama_kecamatan')
            ->get();

        return view('input.lpg', compact('kecamatan'));
    }

    public function storeLpg(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'jenis_lpg' => 'required|in:LPG 3 Kg (Subsidi),LPG 5.5 Kg (Non Subsidi),LPG 12 Kg,LPG 50 Kg,Bright Gas',
            'stok_tersedia_tabung' => 'required|integer|min:0',
            'distribusi_masuk_tabung' => 'required|integer|min:0',
            'distribusi_keluar_tabung' => 'required|integer|min:0',
            'permintaan_harian_tabung' => 'nullable|integer|min:0',
            'harga_pangkalan' => 'nullable|numeric',
            'harga_het_pemerintah' => 'nullable|numeric',
            'harga_eceran_lapangan' => 'nullable|numeric',
            'status_ketersediaan' => 'required|in:aman,waspada,menipis,kosong',
            'status_distribusi' => 'required|in:lancar,terhambat,macet,panic_buying',
        ]);

        $hargaPangkalan = $validated['harga_pangkalan'] ?? 0;
        $hargaHet = $validated['harga_het_pemerintah'] ?? $hargaPangkalan;
        $hargaEceran = $validated['harga_eceran_lapangan'] ?? $hargaPangkalan;
        $selisih = $hargaEceran - $hargaHet;
        $persenKenaikan = $hargaHet > 0 ? ($selisih / $hargaHet) * 100 : 0;

        $statusHarga = 'sesuai_het';
        if ($persenKenaikan > 20) {
            $statusHarga = 'melampaui_het_tinggi';
        } elseif ($persenKenaikan > 0) {
            $statusHarga = 'melampaui_het_ringan';
        }

        DataLpg::create([
            'tanggal_input' => $validated['tanggal_input'],
            'kecamatan_id' => $validated['kecamatan_id'],
            'pangkalan_lpg_id' => null,
            'desa_id' => $validated['desa_id'] ?? null,
            'user_id' => Auth::id() ?? 1,
            'jenis_lpg' => $validated['jenis_lpg'],
            'stok_tersedia_tabung' => $validated['stok_tersedia_tabung'],
            'distribusi_masuk_tabung' => $validated['distribusi_masuk_tabung'],
            'distribusi_keluar_tabung' => $validated['distribusi_keluar_tabung'],
            'permintaan_harian_tabung' => $validated['permintaan_harian_tabung'] ?? null,
            'harga_pangkalan' => $hargaPangkalan,
            'harga_het_pemerintah' => $hargaHet,
            'harga_eceran_lapangan' => $hargaEceran,
            'selisih_harga_eceran_het' => $selisih,
            'persentase_kenaikan_harga' => round($persenKenaikan, 2),
            'status_harga' => $statusHarga,
            'status_ketersediaan' => $validated['status_ketersediaan'],
            'status_distribusi' => $validated['status_distribusi'],
            'status_verifikasi' => 'pending',
            'keterangan_kendala' => null,
        ]);

        return redirect()->route('input.lpg')->with('success', 'Data LPG berhasil disimpan dan menunggu validasi.');
    }

    public function bbm()
    {
        $spbu = Spbu::with('kecamatan')
            ->where('is_active', true)
            ->orderBy('nama_spbu')
            ->get();
        $kecamatan = $spbu->pluck('kecamatan')->filter()->unique('id')->sortBy('nama_kecamatan')->values();

        return view('input.bbm', compact('kecamatan', 'spbu'));
    }

    public function storeBbm(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'spbu_id' => [
                'required',
                Rule::exists('spbu', 'id')
                    ->where('is_active', true)
                    ->where('kecamatan_id', $request->input('kecamatan_id')),
            ],
            'jenis_bbm' => 'required|in:Pertalite (JBKP),Pertamax,Pertamax Turbo,Solar (JBT),Dexlite,Pertamina Dex,Bio Solar',
            'stok_tersedia_kl' => 'nullable|numeric',
            'stok_masuk_hari_ini_kl' => 'nullable|numeric',
            'penjualan_harian_rata_kl' => 'nullable|numeric',
            'estimasi_hari_ketersediaan' => 'nullable|numeric',
            'distribusi_nelayan' => 'nullable|boolean',
            'kuota_nelayan_harian_kl' => 'nullable|numeric',
            'realisasi_nelayan_kl' => 'nullable|numeric',
            'distribusi_pertanian' => 'nullable|boolean',
            'kuota_pertanian_harian_kl' => 'nullable|numeric',
            'realisasi_pertanian_kl' => 'nullable|numeric',
            'status_antrean' => 'required|in:tidak_ada,pendek,panjang,sangat_panjang',
            'estimasi_waktu_tunggu_menit' => 'nullable|integer',
            'status_ketersediaan' => 'required|in:aman,waspada,menipis,kosong',
            'harga_jual_per_liter' => 'nullable|numeric',
        ]);

        DataBbm::create([
            'tanggal_input' => $validated['tanggal_input'],
            'kecamatan_id' => $validated['kecamatan_id'],
            'spbu_id' => $validated['spbu_id'],
            'user_id' => Auth::id() ?? 1,
            'jenis_bbm' => $validated['jenis_bbm'],
            'stok_tersedia_kl' => $validated['stok_tersedia_kl'] ?? null,
            'stok_masuk_hari_ini_kl' => $validated['stok_masuk_hari_ini_kl'] ?? null,
            'penjualan_harian_rata_kl' => $validated['penjualan_harian_rata_kl'] ?? null,
            'estimasi_hari_ketersediaan' => $validated['estimasi_hari_ketersediaan'] ?? null,
            'distribusi_nelayan' => $validated['distribusi_nelayan'] ?? false,
            'kuota_nelayan_harian_kl' => $validated['kuota_nelayan_harian_kl'] ?? null,
            'realisasi_nelayan_kl' => $validated['realisasi_nelayan_kl'] ?? null,
            'distribusi_pertanian' => $validated['distribusi_pertanian'] ?? false,
            'kuota_pertanian_harian_kl' => $validated['kuota_pertanian_harian_kl'] ?? null,
            'realisasi_pertanian_kl' => $validated['realisasi_pertanian_kl'] ?? null,
            'status_antrean' => $validated['status_antrean'],
            'estimasi_waktu_tunggu_menit' => $validated['estimasi_waktu_tunggu_menit'] ?? null,
            'status_ketersediaan' => $validated['status_ketersediaan'],
            'harga_jual_per_liter' => $validated['harga_jual_per_liter'] ?? null,
            'status_verifikasi' => 'pending',
            'keterangan_kendala' => null,
        ]);

        return redirect()->route('input.bbm')->with('success', 'Data BBM berhasil disimpan dan menunggu validasi.');
    }

    public static function parseBulan(string|int $bulan): array
    {
        $map = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        if (is_numeric($bulan)) {
            $num = (int) $bulan;
            $num = max(1, min(12, $num));
            return [$num, $map[$num]];
        }

        $clean = strtolower(trim((string) $bulan));
        foreach ($map as $num => $name) {
            $short = strtolower(substr($name, 0, 3));
            if (str_starts_with($clean, $short) || $clean === strtolower($name)) {
                return [$num, $name];
            }
        }

        return [(int) date('n'), $map[(int) date('n')]];
    }

    public static function parseMingguKe(string|int $minggu): string
    {
        $clean = strtoupper(trim((string) $minggu));
        $clean = str_replace('MINGGU', '', $clean);
        $clean = trim($clean);

        return match ($clean) {
            '1', 'I' => 'I',
            '2', 'II' => 'II',
            '3', 'III' => 'III',
            '4', 'IV' => 'IV',
            '5', 'V' => 'V',
            default => $clean !== '' ? $clean : 'I',
        };
    }

    public function iph(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $records = IphMingguan::query()
            ->when($tahun, fn ($q) => $q->where('tahun', $tahun))
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('minggu_ke', 'desc')
            ->get();

        $daftarTahun = IphMingguan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun')->all();
        if (!in_array((int) date('Y'), $daftarTahun, true)) {
            array_unshift($daftarTahun, (int) date('Y'));
        }

        return view('input.iph-mingguan', compact('records', 'tahun', 'daftarTahun'));
    }

    public function storeIph(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'nullable|integer',
            'bulan' => 'required',
            'minggu' => 'required',
            'iph' => 'required|numeric',
            'ranking' => 'nullable|integer',
            'komoditas_andil' => 'nullable|string',
        ]);

        $tahun = (int) ($validated['tahun'] ?? date('Y'));
        [$bulanNum, $namaBulan] = self::parseBulan($validated['bulan']);
        $mingguKe = self::parseMingguKe($validated['minggu']);
        $periodeLabel = "Minggu {$mingguKe} {$namaBulan} {$tahun}";

        $tglAwal = sprintf('%04d-%02d-01', $tahun, $bulanNum);
        $tglAkhir = sprintf('%04d-%02d-07', $tahun, $bulanNum);
        $iphVal = (float) $validated['iph'];

        // Sertakan baris yang sudah di-soft-delete agar periode yang pernah dihapus dapat diisi ulang
        // tanpa melanggar unique index (tahun, bulan, minggu_ke).
        $iphMingguan = IphMingguan::withTrashed()->firstOrNew([
            'tahun' => $tahun,
            'bulan' => $bulanNum,
            'minggu_ke' => $mingguKe,
        ]);

        $iphMingguan->fill([
            'periode_label' => $periodeLabel,
            'tanggal_awal_periode' => $tglAwal,
            'tanggal_akhir_periode' => $tglAkhir,
            'tanggal_publikasi' => now()->toDateString(),
            'user_id' => Auth::id() ?? 1,
            'sumber_data' => 'BPS Kabupaten Gorontalo Utara',
            'iph_kabupaten' => $iphVal,
            'ranking' => isset($validated['ranking']) && $validated['ranking'] !== '' ? (int) $validated['ranking'] : null,
            'komoditas_andil' => $validated['komoditas_andil'] ?? null,
            'komoditas_penyumbang_inflasi' => $validated['komoditas_andil'] ?? null,
            'status_verifikasi' => 'disetujui',
        ]);
        $iphMingguan->deleted_at = null;
        $iphMingguan->save();

        return redirect()->route('input.iph.index')->with('success', 'Data IPH Mingguan berhasil disimpan ke spreadsheet dan monitoring!');
    }

    public function destroyIphMingguan(IphMingguan $iphMingguan)
    {
        $iphMingguan->delete();
        return redirect()->back()->with('success', 'Baris data IPH Mingguan berhasil dihapus.');
    }

    public function exportIphMingguanExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $records = IphMingguan::query()
            ->when($tahun, fn ($q) => $q->where('tahun', $tahun))
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('minggu_ke', 'desc')
            ->get();

        $headers = [
            'No',
            'Kabupaten',
            'Tahun',
            'Bulan',
            'Minggu Ke',
            'Periode',
            'Nilai IPH (%)',
            'Ranking Nasional',
            'Komoditas Andil',
            'Status',
        ];

        $rows = [];
        foreach ($records as $idx => $r) {
            [, $namaBulan] = self::parseBulan($r->bulan);
            $val = (float) $r->iph_kabupaten;
            $status = $val > 3.5 || $val < -2.5 ? 'Tinggi' : ($val >= 2.5 ? 'Waspada' : 'Terkendali');

            $rows[] = [
                $idx + 1,
                'Kab. Gorontalo Utara',
                $r->tahun,
                $namaBulan,
                'Minggu ' . $r->minggu_ke,
                $r->periode_label,
                $val,
                $r->ranking ?? '-',
                $r->komoditas_andil ?? $r->komoditas_penyumbang_inflasi ?? '-',
                $status,
            ];
        }

        return SimpleExcelExporter::download(
            'IPH Mingguan',
            $headers,
            $rows,
            'Data_IPH_Mingguan_Gorut_' . ($tahun ? $tahun . '_' : '') . date('Ymd_His') . '.xlsx'
        );
    }

    public function iph_bulanan(Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));
        $records = IphBulanan::query()
            ->when($tahun, fn ($q) => $q->where('tahun', $tahun))
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $daftarTahun = IphBulanan::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun')->all();
        if (!in_array((int) date('Y'), $daftarTahun, true)) {
            array_unshift($daftarTahun, (int) date('Y'));
        }

        return view('input.iph-bulanan', compact('records', 'tahun', 'daftarTahun'));
    }

    public function storeIphBulanan(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required',
            'iph_bulanan' => 'required|numeric',
            'ranking' => 'nullable|integer',
            'komoditas_andil' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $tahun = (int) $validated['tahun'];
        [$bulanNum, $namaBulan] = self::parseBulan($validated['bulan']);
        $iphVal = (float) $validated['iph_bulanan'];

        $kategori = match (true) {
            $iphVal > 3.5 || $iphVal < -2.5 => 'tinggi',
            $iphVal >= 2.5 => 'waspada',
            default => 'terkendali',
        };

        // Pulihkan baris yang pernah dihapus untuk periode yang sama agar tidak terjadi duplikasi.
        $iphBulanan = IphBulanan::withTrashed()->firstOrNew([
            'tahun' => $tahun,
            'bulan' => $bulanNum,
        ]);

        $iphBulanan->fill([
            'nama_bulan' => $namaBulan,
            'iph_bulanan' => $iphVal,
            'ranking' => isset($validated['ranking']) && $validated['ranking'] !== '' ? (int) $validated['ranking'] : null,
            'komoditas_andil' => $validated['komoditas_andil'] ?? null,
            'status_kategori' => $kategori,
            'catatan' => $validated['catatan'] ?? null,
            'user_id' => Auth::id() ?? 1,
        ]);
        $iphBulanan->deleted_at = null;
        $iphBulanan->save();

        return redirect()->route('input.iph_bulanan')->with('success', 'Data IPH Bulanan berhasil disimpan ke spreadsheet dan monitoring!');
    }

    public function destroyIphBulanan(IphBulanan $iphBulanan)
    {
        $iphBulanan->delete();
        return redirect()->back()->with('success', 'Baris data IPH Bulanan berhasil dihapus.');
    }

    public function exportIphBulananExcel(Request $request)
    {
        $tahun = $request->query('tahun');
        $records = IphBulanan::query()
            ->when($tahun, fn ($q) => $q->where('tahun', $tahun))
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        $headers = [
            'No',
            'Kabupaten',
            'Tahun',
            'Bulan',
            'Nilai IPH Bulanan (%)',
            'Ranking Nasional',
            'Komoditas Andil',
            'Status Kategori',
            'Catatan BPS',
        ];

        $rows = [];
        foreach ($records as $idx => $r) {
            $rows[] = [
                $idx + 1,
                'Kab. Gorontalo Utara',
                $r->tahun,
                $r->nama_bulan,
                (float) $r->iph_bulanan,
                $r->ranking ?? '-',
                $r->komoditas_andil ?? '-',
                ucfirst($r->status_kategori ?? 'Terkendali'),
                $r->catatan ?? '-',
            ];
        }

        return SimpleExcelExporter::download(
            'IPH Bulanan',
            $headers,
            $rows,
            'Data_IPH_Bulanan_Gorut_' . ($tahun ? $tahun . '_' : '') . date('Ymd_His') . '.xlsx'
        );
    }
}
