<?php

namespace App\Http\Controllers;

use App\Models\EwsAlert;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EwsController extends Controller
{
    public function index()
    {
        $manualAlerts = EwsAlert::with(['kecamatan'])
            ->whereIn('status', ['terbuka', 'diproses'])
            ->orderBy('waktu_terdeteksi', 'desc')
            ->get()
            ->map(function ($alert) {
                return (object) [
                    'id' => $alert->id,
                    'is_manual' => true,
                    'judul' => $alert->judul_alert,
                    'wilayah' => $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara',
                    'kategori' => ucfirst($alert->kategori),
                    'risiko' => $alert->tingkat_risiko,
                    'waktu' => $alert->waktu_terdeteksi,
                    'analisis' => $alert->analisis_sistem,
                    'fotos' => $alert->foto_laporan_path,
                ];
            });

        $automaticAlerts = $this->getAutomaticAlerts();
        
        $alerts = $manualAlerts->concat($automaticAlerts)->sortByDesc('waktu');

        $kecamatanOptions = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->orderBy('nama_kecamatan')
            ->get();

        return view('ews.index', compact('alerts', 'kecamatanOptions'));
    }

    private function getAutomaticAlerts()
    {
        $alerts = collect();

        // 1. Data Pangan
        $pangan = \App\Models\DataPangan::with(['kecamatan', 'komoditas'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($item) => $item->kecamatan_id . '-' . $item->komoditas_id)
            ->map(fn ($group) => $group->first());

        foreach ($pangan as $p) {
            $alerts->push((object) [
                'id' => 'pangan-' . $p->id,
                'is_manual' => false,
                'judul' => 'Anomali Stok: ' . ($p->komoditas?->nama_komoditas ?? 'Komoditas'),
                'wilayah' => $p->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'Pangan Strategis',
                'risiko' => $p->status_ketersediaan == 'kosong' ? 'kritis' : ($p->status_ketersediaan == 'kurang' ? 'tinggi' : 'sedang'),
                'waktu' => $p->tanggal_input,
                'analisis' => "Terdeteksi status ketersediaan '{$p->status_ketersediaan}' untuk komoditas {$p->komoditas?->nama_komoditas} di wilayah {$p->kecamatan?->nama_kecamatan}. Stok saat ini: " . number_format($p->stok_tersedia, 0, ',', '.') . " " . ($p->komoditas?->satuan ?? ''),
                'fotos' => null,
            ]);
        }

        // 2. Data LPG
        $lpg = \App\Models\DataLpg::with(['kecamatan'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($item) => $item->kecamatan_id . '-' . $item->jenis_lpg)
            ->map(fn ($group) => $group->first());

        foreach ($lpg as $l) {
            $alerts->push((object) [
                'id' => 'lpg-' . $l->id,
                'is_manual' => false,
                'judul' => 'Anomali Stok: ' . $l->jenis_lpg,
                'wilayah' => $l->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'LPG',
                'risiko' => $l->status_ketersediaan == 'kosong' ? 'kritis' : ($l->status_ketersediaan == 'menipis' ? 'tinggi' : 'sedang'),
                'waktu' => $l->tanggal_input,
                'analisis' => "Terdeteksi status ketersediaan '{$l->status_ketersediaan}' untuk {$l->jenis_lpg} di wilayah {$l->kecamatan?->nama_kecamatan}. Stok saat ini: " . number_format($l->stok_tersedia_tabung, 0, ',', '.') . " tabung.",
                'fotos' => null,
            ]);
        }

        // 3. Data BBM
        $bbm = \App\Models\DataBbm::with(['kecamatan', 'spbu'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($item) => $item->spbu_id . '-' . $item->jenis_bbm)
            ->map(fn ($group) => $group->first());

        foreach ($bbm as $b) {
            $alerts->push((object) [
                'id' => 'bbm-' . $b->id,
                'is_manual' => false,
                'judul' => 'Anomali Stok: ' . $b->jenis_bbm,
                'wilayah' => $b->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'BBM',
                'risiko' => $b->status_ketersediaan == 'kosong' ? 'kritis' : ($b->status_ketersediaan == 'menipis' ? 'tinggi' : 'sedang'),
                'waktu' => $b->tanggal_input,
                'analisis' => "Terdeteksi status ketersediaan '{$b->status_ketersediaan}' untuk {$b->jenis_bbm} di SPBU " . ($b->spbu?->nama_spbu ?? '-') . " ({$b->kecamatan?->nama_kecamatan}). Stok saat ini: " . number_format($b->stok_tersedia_kl, 3, ',', '.') . " KL.",
                'fotos' => null,
            ]);
        }

        return $alerts;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:pangan,lpg,bbm,multikomoditas',
            'tingkat_risiko' => 'required|in:rendah,sedang,tinggi,kritis',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'judul_alert' => 'required|string|max:200',
            'analisis_sistem' => 'required|string',
            'fotos' => 'nullable|array|max:3',
            'fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $fotoPaths = collect($request->file('fotos', []))
            ->map(fn ($foto): string => $foto->store('ews-alerts', 'public'))
            ->all();

        EwsAlert::create([
            'kode_alert' => 'EWS-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
            'sumber_deteksi' => 'manual_admin',
            'kategori' => $validated['kategori'],
            'tingkat_risiko' => $validated['tingkat_risiko'],
            'status' => 'terbuka',
            'kecamatan_id' => $validated['kecamatan_id'],
            'judul_alert' => $validated['judul_alert'],
            'analisis_sistem' => $validated['analisis_sistem'],
            'foto_laporan_path' => $fotoPaths,
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
