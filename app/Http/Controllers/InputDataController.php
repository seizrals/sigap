<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\PangkalanLpg;
use App\Models\PasarLokasi;
use App\Models\Spbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputDataController extends Controller
{
    public function pangan()
    {
        $kecamatan = Kecamatan::where('is_active', true)->get();
        $komoditas = Komoditas::where('is_active', true)->where('is_strategis', true)->get();
        $pasar = PasarLokasi::where('is_active', true)->get();

        return view('input.pangan', compact('kecamatan', 'komoditas', 'pasar'));
    }

    public function storePangan(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'pasar_lokasi_id' => 'nullable|exists:pasar_lokasi,id',
            'komoditas_id' => 'required|exists:komoditas,id',
            'harga_hari_ini' => 'required|numeric',
            'harga_hari_sebelumnya' => 'nullable|numeric',
            'stok_tersedia' => 'nullable|numeric',
            'satuan_stok' => 'nullable|string|max:30',
            'stok_masuk_hari_ini' => 'nullable|numeric',
            'sumber_distribusi_masuk' => 'nullable|string|max:255',
            'status_ketersediaan' => 'required|in:aman,waspada,kurang,kosong',
            'keterangan_lapangan' => 'nullable|string',
        ]);

        $hargaSebelumnya = $validated['harga_hari_sebelumnya'] ?? $validated['harga_hari_ini'];
        $perubahanNominal = $validated['harga_hari_ini'] - $hargaSebelumnya;
        $perubahanPersen = $hargaSebelumnya > 0 ? ($perubahanNominal / $hargaSebelumnya) * 100 : 0;

        $statusHarga = 'stabil';
        if ($perubahanPersen > 25) {
            $statusHarga = 'harga_tinggi';
        } elseif ($perubahanPersen > 10) {
            $statusHarga = 'naik_tinggi';
        } elseif ($perubahanPersen > 0) {
            $statusHarga = 'naik_wajar';
        } elseif ($perubahanPersen < 0) {
            $statusHarga = 'turun';
        }

        DataPangan::create([
            'tanggal_input' => $validated['tanggal_input'],
            'kecamatan_id' => $validated['kecamatan_id'],
            'desa_id' => $validated['desa_id'] ?? null,
            'pasar_lokasi_id' => $validated['pasar_lokasi_id'] ?? null,
            'komoditas_id' => $validated['komoditas_id'],
            'user_id' => Auth::id() ?? 1,
            'harga_hari_ini' => $validated['harga_hari_ini'],
            'harga_hari_sebelumnya' => $hargaSebelumnya,
            'perubahan_harga_nominal' => $perubahanNominal,
            'perubahan_harga_persen' => round($perubahanPersen, 2),
            'stok_tersedia' => $validated['stok_tersedia'] ?? null,
            'satuan_stok' => $validated['satuan_stok'] ?? null,
            'stok_masuk_hari_ini' => $validated['stok_masuk_hari_ini'] ?? null,
            'sumber_distribusi_masuk' => $validated['sumber_distribusi_masuk'] ?? null,
            'status_harga' => $statusHarga,
            'status_ketersediaan' => $validated['status_ketersediaan'],
            'status_verifikasi' => 'pending',
            'keterangan_lapangan' => $validated['keterangan_lapangan'] ?? null,
        ]);

        return redirect()->route('input.pangan')->with('success', 'Data pangan berhasil disimpan dan menunggu validasi.');
    }

    public function lpg()
    {
        $kecamatan = Kecamatan::where('is_active', true)->get();
        $pangkalan = PangkalanLpg::where('is_active', true)->get();

        return view('input.lpg', compact('kecamatan', 'pangkalan'));
    }

    public function storeLpg(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'pangkalan_lpg_id' => 'required|exists:pangkalan_lpg,id',
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
            'keterangan_kendala' => 'nullable|string',
        ]);

        $hargaPangkalan = $validated['harga_pangkalan'] ?? 0;
        $hargaEceran = $validated['harga_eceran_lapangan'] ?? $hargaPangkalan;
        $selisih = $hargaEceran - ($validated['harga_het_pemerintah'] ?? $hargaPangkalan);
        $persenKenaikan = $validated['harga_het_pemerintah'] > 0 ? ($selisih / $validated['harga_het_pemerintah']) * 100 : 0;

        $statusHarga = 'sesuai_het';
        if ($persenKenaikan > 20) {
            $statusHarga = 'melampaui_het_tinggi';
        } elseif ($persenKenaikan > 0) {
            $statusHarga = 'melampaui_het_ringan';
        }

        DataLpg::create([
            'tanggal_input' => $validated['tanggal_input'],
            'kecamatan_id' => $validated['kecamatan_id'],
            'desa_id' => $validated['desa_id'] ?? null,
            'pangkalan_lpg_id' => $validated['pangkalan_lpg_id'],
            'user_id' => Auth::id() ?? 1,
            'jenis_lpg' => $validated['jenis_lpg'],
            'stok_tersedia_tabung' => $validated['stok_tersedia_tabung'],
            'distribusi_masuk_tabung' => $validated['distribusi_masuk_tabung'],
            'distribusi_keluar_tabung' => $validated['distribusi_keluar_tabung'],
            'permintaan_harian_tabung' => $validated['permintaan_harian_tabung'] ?? null,
            'harga_pangkalan' => $hargaPangkalan,
            'harga_het_pemerintah' => $validated['harga_het_pemerintah'] ?? $hargaPangkalan,
            'harga_eceran_lapangan' => $hargaEceran,
            'selisih_harga_eceran_het' => $selisih,
            'persentase_kenaikan_harga' => round($persenKenaikan, 2),
            'status_harga' => $statusHarga,
            'status_ketersediaan' => $validated['status_ketersediaan'],
            'status_distribusi' => $validated['status_distribusi'],
            'status_verifikasi' => 'pending',
            'keterangan_kendala' => $validated['keterangan_kendala'] ?? null,
        ]);

        return redirect()->route('input.lpg')->with('success', 'Data LPG berhasil disimpan dan menunggu validasi.');
    }

    public function bbm()
    {
        $kecamatan = Kecamatan::where('is_active', true)->get();
        $spbu = Spbu::where('is_active', true)->get();

        return view('input.bbm', compact('kecamatan', 'spbu'));
    }

    public function storeBbm(Request $request)
    {
        $validated = $request->validate([
            'tanggal_input' => 'required|date',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'spbu_id' => 'required|exists:spbu,id',
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
            'keterangan_kendala' => 'nullable|string',
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
            'keterangan_kendala' => $validated['keterangan_kendala'] ?? null,
        ]);

        return redirect()->route('input.bbm')->with('success', 'Data BBM berhasil disimpan dan menunggu validasi.');
    }

    public function iph()
    {
        return view('input.iph-mingguan');
    }

    public function iph_bulanan()
    {
        return view('input.iph-bulanan');
    }
}
