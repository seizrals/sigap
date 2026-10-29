<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\SaranPublik;
use Illuminate\Http\Request;

class SaranPublikController extends Controller
{
    public function index(Request $request)
    {
        $filterKategori = $request->query('kategori');
        $filterKecamatan = $request->query('kecamatan_id');
        $search = $request->query('q');

        $query = SaranPublik::with(['kecamatan'])
            ->when($filterKategori, fn ($q) => $q->where('kategori', $filterKategori))
            ->when($filterKecamatan, fn ($q) => $q->where('kecamatan_id', $filterKecamatan))
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('judul_laporan', 'like', "%{$search}%")
                        ->orWhere('isi_laporan', 'like', "%{$search}%")
                        ->orWhere('nama_komoditas_dilaporkan', 'like', "%{$search}%")
                        ->orWhere('lokasi_spesifik', 'like', "%{$search}%")
                        ->orWhere('nama_pelapor', 'like', "%{$search}%")
                        ->orWhere('kontak_pelapor', 'like', "%{$search}%")
                        ->orWhere('kode_laporan', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc');

        $saranList = $query->paginate(20)->withQueryString();

        $kecamatan = Kecamatan::where('is_active', true)
            ->select('id', 'nama_kecamatan')
            ->orderBy('nama_kecamatan')
            ->get();

        $kategoriCounts = [
            'semua' => SaranPublik::count(),
            'lonjakan_harga' => SaranPublik::where('kategori', 'lonjakan_harga')->count(),
            'kelangkaan_stok' => SaranPublik::where('kategori', 'kelangkaan_stok')->count(),
            'pelanggaran_het' => SaranPublik::where('kategori', 'pelanggaran_het')->count(),
            'saran_kebijakan' => SaranPublik::where('kategori', 'saran_kebijakan')->count(),
            'lainnya' => SaranPublik::where('kategori', 'lainnya')->count(),
        ];

        return view('saran-publik.index', compact(
            'saranList',
            'kecamatan',
            'filterKategori',
            'filterKecamatan',
            'search',
            'kategoriCounts'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|in:lonjakan_harga,kelangkaan_stok,saran_kebijakan,pelanggaran_het,lainnya',
            'nama_pelapor' => 'nullable|string|max:100',
            'kontak_pelapor' => 'required|string|max:50',
            'anonim' => 'nullable|boolean',
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'lokasi_spesifik' => 'nullable|string|max:200',
            'judul_laporan' => 'required|string|max:200',
            'isi_laporan' => 'required|string',
            'nama_komoditas_dilaporkan' => 'exclude_unless:kategori,lonjakan_harga,kelangkaan_stok,pelanggaran_het|required|string|max:150',
            'harga_dilaporkan' => 'exclude_unless:kategori,lonjakan_harga,kelangkaan_stok,pelanggaran_het|required|numeric|min:0',
        ], [
            'kontak_pelapor.required' => 'Kontak wajib diisi.',
            'nama_komoditas_dilaporkan.required' => 'Nama komoditas wajib diisi untuk kategori ini.',
            'harga_dilaporkan.required' => 'Harga yang dilaporkan wajib diisi untuk kategori ini.',
        ]);

        $validated['kode_laporan'] = 'SRN-'.date('Ymd').'-'.str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'masuk';
        $validated['anonim'] = $request->boolean('anonim');

        SaranPublik::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Laporan berhasil dikirim.']);
        }

        if ($request->has('from_admin') || ($request->header('referer') && str_contains($request->header('referer'), 'saran-publik'))) {
            return redirect()->route('saran-publik.index')
                ->with('success', 'Saran / Laporan publik berhasil ditambahkan.');
        }

        return redirect()->route('portal-publik')
            ->with('success', 'Laporan/saran berhasil dikirim. Terima kasih atas kontribusi Anda.');
    }
}
