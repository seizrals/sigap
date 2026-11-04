<?php

namespace App\Http\Controllers;

use App\Models\EwsAlert;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EwsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $manualAlerts = EwsAlert::with(['kecamatan'])
            ->whereIn('status', ['terbuka', 'diproses'])
            ->orderBy('waktu_terdeteksi', 'desc')
            ->get()
            ->map(function ($alert) {
                return (object) [
                    'id' => $alert->id,
                    'is_manual' => true,
                    'judul_alert' => $alert->judul_alert,
                    'wilayah' => $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara',
                    'kategori' => ucfirst($alert->kategori),
                    'status_ketersediaan' => $alert->status_ketersediaan,
                    'waktu_terdeteksi' => $alert->waktu_terdeteksi,
                    'analisis_sistem' => $alert->analisis_sistem,
                    'foto_laporan_path' => $alert->foto_laporan_path,
                ];
            });

        $automaticAlerts = $this->getAutomaticAlerts();
        
        $alerts = $manualAlerts->concat($automaticAlerts)->sortByDesc('waktu_terdeteksi')->values();

        $resolvedAlerts = EwsAlert::with(['kecamatan', 'handler'])
            ->where('status', 'selesai')
            ->orderByDesc('waktu_penanganan')
            ->get()
            ->map(function ($alert) {
                return (object) [
                    'id' => $alert->id,
                    'kode_alert' => $alert->kode_alert,
                    'is_manual' => $alert->sumber_deteksi !== 'otomatis_sistem',
                    'judul_alert' => $alert->judul_alert,
                    'wilayah' => $alert->kecamatan?->nama_kecamatan ?? 'Gorontalo Utara',
                    'kategori' => ucfirst($alert->kategori),
                    'status_ketersediaan' => $alert->status_ketersediaan,
                    'waktu_terdeteksi' => $alert->waktu_terdeteksi,
                    'waktu_penanganan' => $alert->waktu_penanganan,
                    'analisis_sistem' => $alert->analisis_sistem,
                    'tindak_lanjut' => $alert->tindak_lanjut,
                    'bukti_penanganan_path' => $alert->bukti_penanganan_path,
                    'foto_laporan_path' => $alert->foto_laporan_path,
                    'handler_name' => $alert->handler?->name ?? 'Petugas Lapangan',
                ];
            });

        if ($search) {
            $searchLower = strtolower(trim($search));
            $filterFn = function ($item) use ($searchLower) {
                $haystack = strtolower(implode(' ', [
                    $item->judul_alert ?? '',
                    $item->wilayah ?? '',
                    $item->kategori ?? '',
                    $item->status_ketersediaan ?? '',
                    $item->tindak_lanjut ?? '',
                    $item->handler_name ?? '',
                ]));
                return str_contains($haystack, $searchLower);
            };

            $alerts = $alerts->filter($filterFn)->values();
            $resolvedAlerts = $resolvedAlerts->filter($filterFn)->values();
        }

        $kecamatanOptions = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->orderBy('nama_kecamatan')
            ->get();

        return view('ews.index', compact('alerts', 'resolvedAlerts', 'kecamatanOptions', 'search'));
    }

    private function getAutomaticAlerts()
    {
        $alerts = collect();

        // Ambil ID yang sudah diselesaikan untuk menghindari duplikasi
        $resolvedIds = EwsAlert::where('sumber_deteksi', 'otomatis_sistem')
            ->where('status', 'selesai')
            ->get()
            ->map(function ($alert) {
                return ($alert->data_pemicu['source_type'] ?? '') . '-' . ($alert->data_pemicu['source_id'] ?? '');
            })
            ->toArray();

        // 1. Data Pangan
        $pangan = \App\Models\DataPangan::with(['kecamatan', 'komoditas'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($p) => !in_array('pangan-' . $p->id, $resolvedIds))
            ->groupBy(fn ($item) => $item->kecamatan_id . '-' . $item->komoditas_id)
            ->map(fn ($group) => $group->first());

        foreach ($pangan as $p) {
            $alerts->push((object) [
                'id' => 'pangan-' . $p->id,
                'is_manual' => false,
                'judul_alert' => 'Anomali Stok: ' . ($p->komoditas?->nama_komoditas ?? 'Komoditas'),
                'wilayah' => $p->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'Pangan Strategis',
                'status_ketersediaan' => $p->status_ketersediaan,
                'waktu_terdeteksi' => $p->tanggal_input,
                'analisis_sistem' => "Terdeteksi status ketersediaan '{$p->status_ketersediaan}' untuk komoditas {$p->komoditas?->nama_komoditas} di wilayah {$p->kecamatan?->nama_kecamatan}. Stok saat ini: " . number_format($p->stok_tersedia, 0, ',', '.') . " " . ($p->komoditas?->satuan ?? ''),
                'foto_laporan_path' => null,
            ]);
        }

        // 2. Data LPG
        $lpg = \App\Models\DataLpg::with(['kecamatan'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($l) => !in_array('lpg-' . $l->id, $resolvedIds))
            ->groupBy(fn ($item) => $item->kecamatan_id . '-' . $item->jenis_lpg)
            ->map(fn ($group) => $group->first());

        foreach ($lpg as $l) {
            $alerts->push((object) [
                'id' => 'lpg-' . $l->id,
                'is_manual' => false,
                'judul_alert' => 'Anomali Stok: ' . $l->jenis_lpg,
                'wilayah' => $l->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'LPG',
                'status_ketersediaan' => $l->status_ketersediaan,
                'waktu_terdeteksi' => $l->tanggal_input,
                'analisis_sistem' => "Terdeteksi status ketersediaan '{$l->status_ketersediaan}' untuk {$l->jenis_lpg} di wilayah {$l->kecamatan?->nama_kecamatan}. Stok saat ini: " . number_format($l->stok_tersedia_tabung, 0, ',', '.') . " tabung.",
                'foto_laporan_path' => null,
            ]);
        }

        // 3. Data BBM
        $bbm = \App\Models\DataBbm::with(['kecamatan', 'spbu'])
            ->where('status_ketersediaan', '!=', 'aman')
            ->orderByDesc('tanggal_input')
            ->orderByDesc('created_at')
            ->get()
            ->filter(fn ($b) => !in_array('bbm-' . $b->id, $resolvedIds))
            ->groupBy(fn ($item) => $item->spbu_id . '-' . $item->jenis_bbm)
            ->map(fn ($group) => $group->first());

        foreach ($bbm as $b) {
            $alerts->push((object) [
                'id' => 'bbm-' . $b->id,
                'is_manual' => false,
                'judul_alert' => 'Anomali Stok: ' . $b->jenis_bbm,
                'wilayah' => $b->kecamatan?->nama_kecamatan ?? '-',
                'kategori' => 'BBM',
                'status_ketersediaan' => $b->status_ketersediaan,
                'waktu_terdeteksi' => $b->tanggal_input,
                'analisis_sistem' => "Terdeteksi status ketersediaan '{$b->status_ketersediaan}' untuk {$b->jenis_bbm} di SPBU " . ($b->spbu?->nama_spbu ?? '-') . " ({$b->kecamatan?->nama_kecamatan}). Stok saat ini: " . number_format($b->stok_tersedia_kl, 3, ',', '.') . " KL.",
                'foto_laporan_path' => null,
            ]);
        }

        return $alerts;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:pangan,lpg,bbm,multikomoditas',
            'status_ketersediaan' => 'required|in:aman,waspada,kritis',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'judul_alert' => 'required|string|max:200',
            'analisis_sistem' => 'nullable|string',
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
            'status_ketersediaan' => $validated['status_ketersediaan'],
            'status' => 'terbuka',
            'kecamatan_id' => $validated['kecamatan_id'],
            'judul_alert' => $validated['judul_alert'],
            'analisis_sistem' => $validated['analisis_sistem'] ?? '-',
            'foto_laporan_path' => $fotoPaths,
            'dibuat_oleh' => Auth::id(),
            'waktu_terdeteksi' => now(),
        ]);

        return redirect()->route('ews.index')
            ->with('success', 'Peringatan baru berhasil dibuat.');
    }

    public function resolve(Request $request, $id)
    {
        $validated = $request->validate([
            'tindak_lanjut' => 'required|string',
            'bukti_penanganan' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $fotoPath = $request->file('bukti_penanganan')->store('ews-resolutions', 'public');

        if (is_numeric($id)) {
            // Manual Alert
            $alert = EwsAlert::findOrFail($id);
            $alert->update([
                'status' => 'selesai',
                'tindak_lanjut' => $validated['tindak_lanjut'],
                'bukti_penanganan_path' => $fotoPath,
                'ditangani_oleh' => Auth::id(),
                'waktu_penanganan' => now(),
            ]);
        } else {
            // Automatic Alert (pangan-ID, lpg-ID, bbm-ID)
            [$type, $sourceId] = explode('-', $id);
            
            $sourceData = null;
            $kategori = '';
            $kecamatanId = null;
            $judulAlert = '';
            $analisisSistem = '';

            if ($type === 'pangan') {
                $sourceData = \App\Models\DataPangan::with(['kecamatan', 'komoditas'])->findOrFail($sourceId);
                $kategori = 'pangan';
                $kecamatanId = $sourceData->kecamatan_id;
                $judulAlert = 'Anomali Stok: ' . ($sourceData->komoditas?->nama_komoditas ?? 'Komoditas');
                $analisisSistem = "Terdeteksi status ketersediaan '{$sourceData->status_ketersediaan}' untuk komoditas {$sourceData->komoditas?->nama_komoditas} di wilayah {$sourceData->kecamatan?->nama_kecamatan}.";
            } elseif ($type === 'lpg') {
                $sourceData = \App\Models\DataLpg::with(['kecamatan'])->findOrFail($sourceId);
                $kategori = 'lpg';
                $kecamatanId = $sourceData->kecamatan_id;
                $judulAlert = 'Anomali Stok: ' . $sourceData->jenis_lpg;
                $analisisSistem = "Terdeteksi status ketersediaan '{$sourceData->status_ketersediaan}' untuk {$sourceData->jenis_lpg} di wilayah {$sourceData->kecamatan?->nama_kecamatan}.";
            } elseif ($type === 'bbm') {
                $sourceData = \App\Models\DataBbm::with(['kecamatan', 'spbu'])->findOrFail($sourceId);
                $kategori = 'bbm';
                $kecamatanId = $sourceData->kecamatan_id;
                $judulAlert = 'Anomali Stok: ' . $sourceData->jenis_bbm;
                $analisisSistem = "Terdeteksi status ketersediaan '{$sourceData->status_ketersediaan}' untuk {$sourceData->jenis_bbm} di SPBU " . ($sourceData->spbu?->nama_spbu ?? '-') . ".";
            }

            EwsAlert::create([
                'kode_alert' => 'EWS-AUTO-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT),
                'sumber_deteksi' => 'otomatis_sistem',
                'kategori' => $kategori,
                'status_ketersediaan' => $sourceData->status_ketersediaan,
                'status' => 'selesai',
                'kecamatan_id' => $kecamatanId,
                'komoditas_id' => $type === 'pangan' ? $sourceData->komoditas_id : null,
                'pangkalan_lpg_id' => $type === 'lpg' ? $sourceData->pangkalan_lpg_id : null,
                'spbu_id' => $type === 'bbm' ? $sourceData->spbu_id : null,
                'judul_alert' => $judulAlert,
                'analisis_sistem' => $analisisSistem,
                'data_pemicu' => ['source_type' => $type, 'source_id' => (int)$sourceId],
                'tindak_lanjut' => $validated['tindak_lanjut'],
                'bukti_penanganan_path' => $fotoPath,
                'ditangani_oleh' => Auth::id(),
                'waktu_penanganan' => now(),
                'waktu_terdeteksi' => $sourceData->tanggal_input ?? now(),
            ]);
        }

        return redirect()->route('ews.index')
            ->with('success', 'Peringatan telah berhasil ditindaklanjuti.');
    }
}
