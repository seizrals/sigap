<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\SaranPublik;
use Illuminate\Http\Request;

class SaranPublikController extends Controller
{
    public function index()
    {
        $saranList = SaranPublik::with('kecamatan')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->get();

        return view('saran-publik.index', compact('saranList', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string',
            'nama_pelapor' => 'nullable|string|max:100',
            'kontak_pelapor' => 'nullable|string|max:50',
            'anonim' => 'nullable|boolean',
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'judul_laporan' => 'required|string|max:200',
            'isi_laporan' => 'required|string',
            'nama_komoditas_dilaporkan' => 'nullable|string|max:100',
            'harga_dilaporkan' => 'nullable|numeric',
        ]);

        $validated['kode_laporan'] = 'SRN-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'masuk';
        $validated['anonim'] = $request->boolean('anonim');

        SaranPublik::create($validated);

        return redirect()->route('portal-publik')
            ->with('success', 'Laporan/saran berhasil dikirim. Terima kasih atas kontribusi Anda.');
    }
}
