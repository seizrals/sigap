<?php

namespace Database\Seeders;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\Desa;
use App\Models\EwsAlert;
use App\Models\IphMingguan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\PangkalanLpg;
use App\Models\PasarLokasi;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Kabupaten',
                'email' => 'admin@sigap-gorut.id',
                'password' => 'password',
                'role' => 'admin_kabupaten',
                'opd_name' => 'TPID Gorontalo Utara',
                'is_active' => true,
            ]
        );

        $kecamatans = [
            ['kode' => '7505010', 'nama' => 'Atinggola', 'lat' => 0.7450, 'lng' => 123.1200, 'zona' => 'merah'],
            ['kode' => '7505020', 'nama' => 'Kwandang', 'lat' => 0.7210, 'lng' => 123.0510, 'zona' => 'merah'],
            ['kode' => '7505030', 'nama' => 'Sumalata', 'lat' => 0.6800, 'lng' => 123.1800, 'zona' => 'kuning'],
            ['kode' => '7505040', 'nama' => 'Gentuma Raya', 'lat' => 0.6500, 'lng' => 123.2200, 'zona' => 'kuning'],
            ['kode' => '7505050', 'nama' => 'Anggrek', 'lat' => 0.6200, 'lng' => 123.1000, 'zona' => 'hijau'],
            ['kode' => '7505060', 'nama' => 'Ponelo Kepulauan', 'lat' => 0.7000, 'lng' => 123.3000, 'zona' => 'merah'],
            ['kode' => '7505070', 'nama' => 'Biau', 'lat' => 0.5800, 'lng' => 123.1500, 'zona' => 'hijau'],
            ['kode' => '7505080', 'nama' => 'Tolinggula', 'lat' => 0.5500, 'lng' => 123.2000, 'zona' => 'hijau'],
            ['kode' => '7505090', 'nama' => 'Dengilo', 'lat' => 0.7600, 'lng' => 123.0800, 'zona' => 'kuning'],
            ['kode' => '7505100', 'nama' => 'Buntulia', 'lat' => 0.8000, 'lng' => 123.1500, 'zona' => 'hijau'],
            ['kode' => '7505110', 'nama' => 'Jomilito', 'lat' => 0.8300, 'lng' => 123.1000, 'zona' => 'hijau'],
        ];

        $kecamatanIds = [];
        foreach ($kecamatans as $kec) {
            $kecamatan = Kecamatan::firstOrCreate(
                ['kode_kecamatan' => $kec['kode']],
                [
                    'nama_kecamatan' => $kec['nama'],
                    'latitude' => $kec['lat'],
                    'longitude' => $kec['lng'],
                    'jumlah_desa' => 10,
                    'zona_risiko' => $kec['zona'],
                    'is_active' => true,
                ]
            );
            $kecamatanIds[$kec['nama']] = $kecamatan->id;
        }

        $desaData = [
            ['Kwandang', 'Molingkapoto', 0.7210, 123.0510],
            ['Kwandang', 'Titidu', 0.7250, 123.0550],
            ['Atinggola', 'Kota Jin', 0.7450, 123.1200],
            ['Anggrek', 'Ilangata', 0.6200, 123.1000],
        ];

        $desaIds = [];
        foreach ($desaData as $d) {
            $desa = Desa::firstOrCreate(
                ['kode_desa' => 'DESA-' . str_replace(' ', '', $d[1])],
                [
                    'kecamatan_id' => $kecamatanIds[$d[0]],
                    'nama_desa' => $d[1],
                    'latitude' => $d[2],
                    'longitude' => $d[3],
                    'is_active' => true,
                ]
            );
            $desaIds[$d[1]] = $desa->id;
        }

        $komoditasData = [
            ['BRG-PRM', 'Beras Premium', 'pangan', 'Kg', 15000, 13000, 17000],
            ['CBI-RWT', 'Cabai Rawit', 'pangan', 'Kg', 60000, 45000, 75000],
            ['BWG-MRH', 'Bawang Merah', 'pangan', 'Kg', 35000, 28000, 42000],
            ['TDK-PUT', 'Telur Ayam', 'pangan', 'Kg', 28000, 25000, 31000],
            ['MNY-GRM', 'Minyak Goreng', 'pangan', 'Liter', 16000, 14000, 18000],
            ['GPL-PSH', 'Gula Pasir', 'pangan', 'Kg', 15000, 13000, 17000],
            ['TPG-KNG', 'Tepung Terigu', 'pangan', 'Kg', 12000, 10000, 14000],
            ['DJA-KNG', 'Daging Ayam', 'pangan', 'Kg', 38000, 35000, 42000],
        ];

        $komoditasIds = [];
        foreach ($komoditasData as $k) {
            $kom = Komoditas::firstOrCreate(
                ['kode_komoditas' => $k[0]],
                [
                    'nama_komoditas' => $k[1],
                    'kategori' => $k[2],
                    'satuan' => $k[3],
                    'harga_het' => $k[4],
                    'batas_bawah_harga_normal' => $k[5],
                    'batas_atas_harga_normal' => $k[6],
                    'stok_aman_minimum' => 100,
                    'persentase_kenaikan_waspada' => 10,
                    'persentase_kenaikan_bahaya' => 25,
                    'is_strategis' => true,
                    'is_active' => true,
                ]
            );
            $komoditasIds[$k[1]] = $kom->id;
        }

        $pasar = PasarLokasi::firstOrCreate(
            ['nama_lokasi' => 'Pasar Sentral Kwandang'],
            [
                'kecamatan_id' => $kecamatanIds['Kwandang'],
                'desa_id' => $desaIds['Molingkapoto'],
                'tipe_lokasi' => 'Pasar Tradisional',
                'alamat' => 'Jl. Pasar Kwandang',
                'latitude' => 0.7210,
                'longitude' => 123.0510,
                'is_active' => true,
            ]
        );

        $pangkalan1 = PangkalanLpg::firstOrCreate(
            ['kode_pangkalan' => 'PKL-001'],
            [
                'kecamatan_id' => $kecamatanIds['Kwandang'],
                'desa_id' => $desaIds['Molingkapoto'],
                'nama_pangkalan' => 'Pkl. Berkah Abadi',
                'nama_pemilik' => 'H. Ahmad',
                'no_hp' => '081234567890',
                'alamat' => 'Desa Molingkapoto, Kwandang',
                'latitude' => 0.7210,
                'longitude' => 123.0510,
                'status_izin' => 'aktif',
                'kapasitas_stok_maks' => 1000,
                'is_active' => true,
            ]
        );

        $pangkalan2 = PangkalanLpg::firstOrCreate(
            ['kode_pangkalan' => 'PKL-002'],
            [
                'kecamatan_id' => $kecamatanIds['Atinggola'],
                'desa_id' => $desaIds['Kota Jin'],
                'nama_pangkalan' => 'Pkl. Utama Jaya',
                'nama_pemilik' => 'B. Jaya',
                'no_hp' => '081234567891',
                'alamat' => 'Desa Kota Jin, Atinggola',
                'latitude' => 0.7450,
                'longitude' => 123.1200,
                'status_izin' => 'aktif',
                'kapasitas_stok_maks' => 800,
                'is_active' => true,
            ]
        );

        $spbu1 = Spbu::firstOrCreate(
            ['kode_spbu' => 'SPBU-001'],
            [
                'kecamatan_id' => $kecamatanIds['Kwandang'],
                'desa_id' => $desaIds['Titidu'],
                'nama_spbu' => 'SPBU Kwandang 74.123',
                'tipe_spbu' => 'SPBU Umum',
                'alamat' => 'Desa Titidu, Kwandang',
                'latitude' => 0.7250,
                'longitude' => 123.0550,
                'melayani_nelayan' => true,
                'melayani_pertanian' => false,
                'is_active' => true,
            ]
        );

        $spbu2 = Spbu::firstOrCreate(
            ['kode_spbu' => 'SPBU-002'],
            [
                'kecamatan_id' => $kecamatanIds['Anggrek'],
                'desa_id' => $desaIds['Ilangata'],
                'nama_spbu' => 'SPBU Anggrek 74.456',
                'tipe_spbu' => 'SPBU Umum',
                'alamat' => 'Desa Ilangata, Anggrek',
                'latitude' => 0.6200,
                'longitude' => 123.1000,
                'melayani_nelayan' => true,
                'melayani_pertanian' => true,
                'is_active' => true,
            ]
        );

        $tanggal = now()->toDateString();

        DataPangan::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'desa_id' => $desaIds['Molingkapoto'],
            'pasar_lokasi_id' => $pasar->id,
            'komoditas_id' => $komoditasIds['Cabai Rawit'],
            'user_id' => $admin->id,
            'harga_hari_ini' => 85000,
            'harga_hari_sebelumnya' => 75000,
            'perubahan_harga_nominal' => 10000,
            'perubahan_harga_persen' => 13.33,
            'stok_tersedia' => 50,
            'satuan_stok' => 'Kg',
            'status_harga' => 'harga_tinggi',
            'status_ketersediaan' => 'kurang',
            'status_verifikasi' => 'disetujui',
            'keterangan_lapangan' => 'Pasokan menurun akibat cuaca buruk',
        ]);

        DataPangan::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'desa_id' => $desaIds['Molingkapoto'],
            'pasar_lokasi_id' => $pasar->id,
            'komoditas_id' => $komoditasIds['Beras Premium'],
            'user_id' => $admin->id,
            'harga_hari_ini' => 15000,
            'harga_hari_sebelumnya' => 15000,
            'perubahan_harga_nominal' => 0,
            'perubahan_harga_persen' => 0,
            'stok_tersedia' => 500,
            'satuan_stok' => 'Kg',
            'status_harga' => 'stabil',
            'status_ketersediaan' => 'aman',
            'status_verifikasi' => 'disetujui',
        ]);

        DataLpg::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'desa_id' => $desaIds['Molingkapoto'],
            'pangkalan_lpg_id' => $pangkalan1->id,
            'user_id' => $admin->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 850,
            'distribusi_masuk_tabung' => 1200,
            'distribusi_keluar_tabung' => 400,
            'harga_pangkalan' => 18500,
            'harga_het_pemerintah' => 18500,
            'harga_eceran_lapangan' => 18500,
            'selisih_harga_eceran_het' => 0,
            'status_harga' => 'sesuai_het',
            'status_ketersediaan' => 'aman',
            'status_distribusi' => 'lancar',
            'status_verifikasi' => 'disetujui',
        ]);

        DataLpg::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Atinggola'],
            'desa_id' => $desaIds['Kota Jin'],
            'pangkalan_lpg_id' => $pangkalan2->id,
            'user_id' => $admin->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 2,
            'distribusi_masuk_tabung' => 0,
            'distribusi_keluar_tabung' => 398,
            'harga_pangkalan' => 18500,
            'harga_het_pemerintah' => 18500,
            'harga_eceran_lapangan' => 25000,
            'selisih_harga_eceran_het' => 6500,
            'persentase_kenaikan_harga' => 35.14,
            'status_harga' => 'melampaui_het_tinggi',
            'status_ketersediaan' => 'menipis',
            'status_distribusi' => 'panic_buying',
            'status_verifikasi' => 'disetujui',
            'keterangan_kendala' => 'Stok menipis, harga eceran melonjak',
        ]);

        DataBbm::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'spbu_id' => $spbu1->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 12.0,
            'penjualan_harian_rata_kl' => 5.0,
            'estimasi_hari_ketersediaan' => 2.4,
            'distribusi_nelayan' => true,
            'kuota_nelayan_harian_kl' => 2.0,
            'realisasi_nelayan_kl' => 1.8,
            'status_antrean' => 'pendek',
            'estimasi_waktu_tunggu_menit' => 10,
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        DataBbm::create([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Anggrek'],
            'spbu_id' => $spbu2->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 0.8,
            'penjualan_harian_rata_kl' => 3.0,
            'estimasi_hari_ketersediaan' => 0.27,
            'distribusi_pertanian' => true,
            'kuota_pertanian_harian_kl' => 1.5,
            'realisasi_pertanian_kl' => 1.2,
            'status_antrean' => 'panjang',
            'estimasi_waktu_tunggu_menit' => 45,
            'status_ketersediaan' => 'menipis',
            'harga_jual_per_liter' => 6800,
            'status_verifikasi' => 'disetujui',
            'keterangan_kendala' => 'Stok menipis, antrean panjang',
        ]);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'II',
            'periode_label' => 'Mei Minggu II 2026',
            'tanggal_awal_periode' => '2026-05-05',
            'tanggal_akhir_periode' => '2026-05-11',
            'tanggal_publikasi' => '2026-05-12',
            'user_id' => $admin->id,
            'sumber_data' => 'BPS Kabupaten Gorontalo Utara',
            'iph_kabupaten' => 2.33,
            'iph_sebelumnya' => 1.95,
            'perubahan_iph_nominal' => 0.38,
            'perubahan_iph_persen' => 19.49,
            'inflasi_minggu_ini_persen' => 0.38,
            'inflasi_ytd_persen' => 2.33,
            'status_verifikasi' => 'disetujui',
            'analisis_ringkas' => 'IPH minggu ini mengalami kenaikan didorong oleh kenaikan harga cabai rawit dan bawang merah',
        ]);

        EwsAlert::create([
            'kode_alert' => 'EWS-2026-001',
            'sumber_deteksi' => 'otomatis_sistem',
            'kategori' => 'pangan',
            'tingkat_risiko' => 'kritis',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'komoditas_id' => $komoditasIds['Cabai Rawit'],
            'judul_alert' => 'Lonjakan Harga Cabai Rawit Merah (+18.5%)',
            'analisis_sistem' => 'Terjadi penurunan pasokan cabai lokal akibat curah hujan tinggi di sentra produksi. Diproyeksikan stok eceran hanya bertahan hingga 3 hari kedepan jika tidak ada pasokan substitusi.',
            'nilai_indikasi_harga_persen' => 18.5,
            'lama_zonasi_berurutan_hari' => 3,
            'waktu_terdeteksi' => now()->subHours(2),
            'dibuat_oleh' => $admin->id,
        ]);

        EwsAlert::create([
            'kode_alert' => 'EWS-2026-002',
            'sumber_deteksi' => 'otomatis_sistem',
            'kategori' => 'lpg',
            'tingkat_risiko' => 'tinggi',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatanIds['Ponelo Kepulauan'],
            'judul_alert' => 'Indikasi Penumpukan Pembelian LPG 3Kg',
            'analisis_sistem' => 'Laporan sisa stok pangkalan menunjukkan tingkat keluar 210% melebihi rata-rata mingguan. Disarankan monitoring lapangan pencegahan panic buying.',
            'nilai_indikasi_stok_persen' => 210,
            'lama_zonasi_berurutan_hari' => 2,
            'waktu_terdeteksi' => now()->subHours(5),
            'dibuat_oleh' => $admin->id,
        ]);
    }
}
