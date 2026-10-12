<?php

namespace App\Http\Controllers;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use Illuminate\Http\Request;

class ValidasiController extends Controller
{
    public function index()
    {
        $dataPanganPending = DataPangan::with('komoditas', 'kecamatan', 'pasarLokasi')
            ->where('status_verifikasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $dataLpgPending = DataLpg::with('pangkalanLpg.kecamatan')
            ->where('status_verifikasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $dataBbmPending = DataBbm::with('spbu.kecamatan')
            ->where('status_verifikasi', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('validasi.index', compact(
            'dataPanganPending',
            'dataLpgPending',
            'dataBbmPending'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:pangan,lpg,bbm',
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $model = match($validated['type']) {
            'pangan' => DataPangan::class,
            'lpg' => DataLpg::class,
            'bbm' => DataBbm::class,
        };

        $data = $model::findOrFail($id);
        $data->status_verifikasi = $validated['status'];
        $data->catatan_validasi = $validated['catatan'] ?? null;
        $data->divalidasi_oleh = auth()->id() ?? 1;
        $data->waktu_validasi = now();
        $data->save();

        return redirect()->route('validasi.index')
            ->with('success', 'Data berhasil ' . ($validated['status'] == 'disetujui' ? 'disetujui' : 'ditolak') . '.');
    }
}
