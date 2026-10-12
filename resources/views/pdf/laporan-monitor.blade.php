<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitor TPID Real-Time</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #334155;
            margin: 20px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .sub-title {
            font-size: 11px;
            color: #64748b;
        }
        .validation-badge {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 8px 14px;
            text-align: center;
        }
        .validation-title {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .validation-status {
            font-size: 11px;
            font-weight: bold;
            color: #2563eb;
            font-style: italic;
        }
        .status-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .status-card {
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
            background: #f8fafc;
        }
        .status-title {
            font-size: 10px;
            font-weight: bold;
            color: #475569;
        }
        .status-val-waspada { color: #d97706; font-weight: bold; }
        .status-val-bahaya  { color: #dc2626; font-weight: bold; }
        .status-val-aman    { color: #16a34a; font-weight: bold; }
        
        .footer {
            margin-top: 30px;
            width: 100%;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- Header Laporan -->
    <table class="header-table">
        <tr>
            <td>
                <div class="title">SIAGA PANGAN - {{ strtoupper($kabupaten) }}</div>
                <div class="sub-title">Monitor Kabupaten Real-Time | Periode: {{ $periode }}</div>
                <div class="sub-title">Dicetak pada: {{ $tanggal_cetak }}</div>
            </td>
            <td align="right">
                @if($is_terverifikasi)
                <div class="validation-badge">
                    <div class="validation-title">Sistem Validasi</div>
                    <div class="validation-status">Data Terverifikasi OPD & BPS</div>
                </div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Ringkasan Status -->
    <table class="status-box">
        <tr>
            <td class="status-card" width="33%">
                <div class="status-title">STATUS PANGAN</div>
                <div class="status-val-waspada">{{ $status_pangan }}</div>
            </td>
            <td class="status-card" width="33%">
                <div class="status-title">STATUS LPG</div>
                <div class="status-val-bahaya">{{ $status_lpg }}</div>
            </td>
            <td class="status-card" width="33%">
                <div class="status-title">STATUS BBM</div>
                <div class="status-val-aman">{{ $status_bbm }}</div>
            </td>
        </tr>
    </table>

    <!-- Isi Laporan / Data Tabel -->
    <h3>Detail Komoditas & Monitoring Harga</h3>
    <p>Dokumen ini disiapkan resmi untuk bahan presentasi dalam Rapat Koordinasi Pimpinan TPID.</p>

    <!-- Footer -->
    <table class="footer">
        <tr>
            <td>Dokumen Resmi EWS TPID {{ $kabupaten }}</td>
            <td align="right">Halaman 1 dari 1</td>
        </tr>
    </table>

</body>
</html>