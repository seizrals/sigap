<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function cetakLaporan(Request $request)
    {
        // 1. Ambil/siapkan data monitor
        $data = [
            'title'            => 'Laporan Monitor TPID Kabupaten',
            'kabupaten'        => 'Gorontalo Utara',
            'periode'          => 'MEI 2026, MINGGU II',
            'tanggal_cetak'    => Carbon::now()->isoFormat('D MMMM YYYY, HH:mm') . ' WITA',
            'status_pangan'    => 'WASPADA',
            'status_lpg'       => 'BAHAYA',
            'status_bbm'       => 'AMAN',
            'is_terverifikasi' => true, // Status Validasi Digital OPD & BPS
        ];

        // 2. Load View khusus PDF dengan Orientasi Landscape/Portrait
        $pdf = Pdf::loadView('pdf.laporan-monitor', $data)
                  ->setPaper('a4', 'landscape');

        // 3. Tampilkan PDF langsung di browser (Stream) atau Download
        return $pdf->stream('Laporan_Monitor_TPID_' . date('Ymd_His') . '.pdf');
    }
}