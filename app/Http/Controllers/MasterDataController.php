<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'komoditas');
        
        $komoditas = Komoditas::orderBy('nama_komoditas')->get();
        $spbu = Spbu::with(['kecamatan', 'desa'])->orderBy('nama_spbu')->get();
        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();
        $desa = Desa::orderBy('nama_desa')->get();

        return view('master-data.index', compact('komoditas', 'spbu', 'kecamatan', 'desa', 'tab'));
    }

    public function storeKomoditas(Request $request)
    {
        $validated = $request->validate([
            'nama_komoditas' => 'required|string',
            'kategori' => 'required|string',
            'satuan' => 'required|string',
        ]);

        Komoditas::create($validated);

        return redirect()->route('master-data.index', ['tab' => 'komoditas'])
            ->with('success', 'Komoditas berhasil ditambahkan');
    }

    public function updateKomoditas(Request $request, Komoditas $komoditas)
    {
        $validated = $request->validate([
            'nama_komoditas' => 'required|string',
            'kategori' => 'required|string',
            'satuan' => 'required|string',
        ]);

        $komoditas->update($validated);

        return redirect()->route('master-data.index', ['tab' => 'komoditas'])
            ->with('success', 'Komoditas berhasil diperbarui');
    }

    public function destroyKomoditas(Komoditas $komoditas)
    {
        $komoditas->delete();
        return redirect()->route('master-data.index', ['tab' => 'komoditas'])
            ->with('success', 'Komoditas berhasil dihapus');
    }

    public function storeSpbu(Request $request)
    {
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'nama_spbu' => 'required|string',
        ]);

        Spbu::create($validated);

        return redirect()->route('master-data.index', ['tab' => 'spbu'])
            ->with('success', 'Data SPBU berhasil ditambahkan');
    }

    public function updateSpbu(Request $request, Spbu $spbu)
    {
        $validated = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'nullable|exists:desa,id',
            'nama_spbu' => 'required|string',
        ]);

        $spbu->update($validated);

        return redirect()->route('master-data.index', ['tab' => 'spbu'])
            ->with('success', 'Data SPBU berhasil diperbarui');
    }

    public function destroySpbu(Spbu $spbu)
    {
        $spbu->delete();
        return redirect()->route('master-data.index', ['tab' => 'spbu'])
            ->with('success', 'Data SPBU berhasil dihapus');
    }
}
