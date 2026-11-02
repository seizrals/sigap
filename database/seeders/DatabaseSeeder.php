<?php

namespace Database\Seeders;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\Desa;
use App\Models\EwsAlert;
use App\Models\IphBulanan;
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
            ['kode' => '7505090', 'nama' => 'Monano', 'lat' => 0.8500, 'lng' => 122.8200, 'zona' => 'kuning'],
            ['kode' => '7505100', 'nama' => 'Sumalata Timur', 'lat' => 0.9500, 'lng' => 122.5800, 'zona' => 'hijau'],
            ['kode' => '7505110', 'nama' => 'Tomilito', 'lat' => 0.8800, 'lng' => 122.9500, 'zona' => 'hijau'],
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
            ['Kwandang', 'Pontolo', 0.7230, 123.0530],
            ['Atinggola', 'Kota Jin', 0.7450, 123.1200],
            ['Anggrek', 'Ilangata', 0.6200, 123.1000],
            ['Tolinggula', 'Tolite Jaya', 0.5500, 123.2000],
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
            ['BRG-PRM', 'Beras Premium', 'pangan', 'Kg', 15500, 14000, 17500],
            ['CBI-RWT', 'Cabai Rawit', 'pangan', 'Kg', 60000, 45000, 75000],
            ['BWG-MRH', 'Bawang Merah', 'pangan', 'Kg', 35000, 28000, 42000],
            ['TDK-PUT', 'Telur Ayam', 'pangan', 'Kg', 28000, 25000, 31000],
            ['MNY-GRM', 'Minyak Goreng', 'pangan', 'Liter', 16000, 14000, 18000],
            ['GPL-PSH', 'Gula Pasir', 'pangan', 'Kg', 15000, 13000, 17000],
            ['TPG-KNG', 'Tepung Terigu', 'pangan', 'Kg', 12000, 10000, 14000],
            ['DJA-KNG', 'Daging Ayam', 'pangan', 'Kg', 38000, 35000, 42000],
            // Komoditas SP2KP Kemendag (https://sp2kp.kemendag.go.id/statistik/variant)
            ['SP2KP-122004', 'Bawang Bombai', 'pangan', 'Kg', 30000, 25000, 35000],
            ['SP2KP-122002', 'Bawang Putih Honan', 'pangan', 'Kg', 36000, 30000, 42000],
            ['SP2KP-122003', 'Bawang Putih Kating', 'pangan', 'Kg', 42000, 35000, 50000],
            ['SP2KP-111003', 'Beras Khusus', 'pangan', 'Kg', 18000, 16000, 22000],
            ['SP2KP-111005', 'Beras Medium', 'pangan', 'Kg', 14000, 12500, 15500],
            ['SP2KP-111004', 'Beras SPHP Bulog', 'pangan', 'Kg', 12500, 11500, 13500],
            ['SP2KP-137', 'Buncis', 'pangan', 'Kg', 18000, 14000, 22000],
            ['SP2KP-121001', 'Cabai Merah Besar', 'pangan', 'Kg', 45000, 35000, 55000],
            ['SP2KP-121002', 'Cabai Merah Keriting', 'pangan', 'Kg', 48000, 38000, 60000],
            ['SP2KP-121004', 'Cabai Rawit Hijau', 'pangan', 'Kg', 42000, 32000, 52000],
            ['SP2KP-121003', 'Cabai Rawit Merah', 'pangan', 'Kg', 65000, 50000, 80000],
            ['SP2KP-131002', 'Caisim', 'pangan', 'Kg', 10000, 8000, 14000],
            ['SP2KP-115002', 'Daging Ayam Kampung', 'pangan', 'Ekor', 65000, 55000, 75000],
            ['SP2KP-115001', 'Daging Ayam Ras', 'pangan', 'Kg', 38000, 34000, 42000],
            ['SP2KP-138', 'Daging Babi', 'pangan', 'Kg', 85000, 75000, 95000],
            ['SP2KP-114006', 'Daging Kerbau Impor Beku', 'pangan', 'Kg', 85000, 80000, 95000],
            ['SP2KP-114005', 'Daging Sapi Impor Beku', 'pangan', 'Kg', 95000, 85000, 110000],
            ['SP2KP-114001', 'Daging Sapi Paha Belakang', 'pangan', 'Kg', 135000, 125000, 145000],
            ['SP2KP-114002', 'Daging Sapi Paha Depan', 'pangan', 'Kg', 130000, 120000, 140000],
            ['SP2KP-114003', 'Daging Sapi Sandung Lamur', 'pangan', 'Kg', 115000, 105000, 125000],
            ['SP2KP-114004', 'Daging Sapi Tetelan', 'pangan', 'Kg', 90000, 80000, 100000],
            ['SP2KP-125001', 'Garam Halus', 'pangan', 'Kg', 10000, 8000, 12000],
            ['SP2KP-112001', 'Gula Pasir Curah', 'pangan', 'Kg', 17000, 15000, 18500],
            ['SP2KP-112002', 'Gula Pasir Kemasan', 'pangan', 'Kg', 18000, 16000, 20000],
            ['SP2KP-123003', 'Ikan Bandeng', 'pangan', 'Kg', 32000, 28000, 38000],
            ['SP2KP-123001', 'Ikan Kembung', 'pangan', 'Kg', 38000, 32000, 45000],
            ['SP2KP-123002', 'Ikan Teri', 'pangan', 'Kg', 65000, 55000, 75000],
            ['SP2KP-123004', 'Ikan Tongkol', 'pangan', 'Kg', 30000, 25000, 38000],
            ['SP2KP-118001', 'Jagung Lokal Pipilan', 'pangan', 'Kg', 8500, 7000, 10000],
            ['SP2KP-130001', 'Jeruk Lokal', 'pangan', 'Kg', 22000, 18000, 28000],
            ['SP2KP-127002', 'Kacang Hijau', 'pangan', 'Kg', 24000, 20000, 28000],
            ['SP2KP-135001', 'Kacang Panjang', 'pangan', 'Kg', 12000, 9000, 15000],
            ['SP2KP-127001', 'Kacang Tanah', 'pangan', 'Kg', 28000, 24000, 32000],
            ['SP2KP-134001', 'Kangkung', 'pangan', 'Kg', 8000, 6000, 12000],
            ['SP2KP-120001', 'Kedelai Impor', 'pangan', 'Kg', 13000, 11500, 14500],
            ['SP2KP-120002', 'Kedelai Lokal', 'pangan', 'Kg', 12500, 11000, 14000],
            ['SP2KP-133001', 'Kentang Besar', 'pangan', 'Kg', 22000, 18000, 26000],
            ['SP2KP-133002', 'Kentang Sedang', 'pangan', 'Kg', 19000, 16000, 23000],
            ['SP2KP-128001', 'Ketela Pohon', 'pangan', 'Kg', 6000, 4500, 8000],
            ['SP2KP-136001', 'Ketimun Besar', 'pangan', 'Kg', 10000, 8000, 13000],
            ['SP2KP-136002', 'Ketimun Sedang', 'pangan', 'Kg', 8500, 7000, 11000],
            ['SP2KP-126001', 'Mie Instan', 'pangan', 'Bungkus', 3500, 3000, 4000],
            ['SP2KP-113001', 'Minyak Goreng Sawit Curah', 'pangan', 'Liter', 16000, 14500, 17500],
            ['SP2KP-113002', 'Minyak Goreng Sawit Kemasan Premium', 'pangan', 'Liter', 21000, 19000, 23500],
            ['SP2KP-113003', 'Minyakita', 'pangan', 'Liter', 15700, 14500, 16500],
            ['SP2KP-131003', 'Pakchoy', 'pangan', 'Kg', 12000, 9000, 16000],
            ['SP2KP-129001', 'Pisang Lokal', 'pangan', 'Kg', 15000, 12000, 20000],
            ['SP2KP-131001', 'Sawi Hijau', 'pangan', 'Kg', 11000, 8000, 15000],
            ['SP2KP-117001', 'Susu Bubuk', 'pangan', '400gr', 45000, 40000, 52000],
            ['SP2KP-117002', 'Susu Bubuk Balita', 'pangan', '400gr', 48000, 42000, 56000],
            ['SP2KP-117003', 'Susu Kental Manis', 'pangan', '370gr', 13000, 11500, 15000],
            ['SP2KP-120003', 'Tahu Putih', 'pangan', 'Kg', 12000, 10000, 15000],
            ['SP2KP-116002', 'Telur Ayam Kampung', 'pangan', 'Kg', 42000, 36000, 48000],
            ['SP2KP-116001', 'Telur Ayam Ras', 'pangan', 'Kg', 29000, 26000, 32000],
            ['SP2KP-120004', 'Tempe Bungkus', 'pangan', 'Kg', 14000, 12000, 16500],
            ['SP2KP-132001', 'Tomat', 'pangan', 'Kg', 16000, 12000, 22000],
            ['SP2KP-124001', 'Udang Basah', 'pangan', 'Kg', 65000, 55000, 80000],
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

        $upsertSpbu = function ($kode, $attributes) {
            $spbu = Spbu::withTrashed()->firstOrNew(['kode_spbu' => $kode]);
            $spbu->fill($attributes);
            $spbu->deleted_at = null;
            $spbu->save();
            return $spbu;
        };

        $spbu1 = $upsertSpbu('SPBU-001', [
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'desa_id' => $desaIds['Pontolo'] ?? ($desaIds['Titidu'] ?? null),
            'nama_spbu' => 'SPBU 74.962.01 Pontolo Kwandang',
            'tipe_spbu' => 'SPBU Umum',
            'alamat' => 'Jl. Trans Sulawesi, Desa Pontolo, Kec. Kwandang',
            'latitude' => 0.7250,
            'longitude' => 123.0550,
            'melayani_nelayan' => true,
            'melayani_pertanian' => true,
            'is_active' => true,
        ]);

        $spbu2 = $upsertSpbu('SPBU-002', [
            'kecamatan_id' => $kecamatanIds['Anggrek'],
            'desa_id' => $desaIds['Ilangata'] ?? null,
            'nama_spbu' => 'SPBU 74.962.02 Ilangata Anggrek',
            'tipe_spbu' => 'SPBU Umum',
            'alamat' => 'Jl. Trans Sulawesi, Desa Ilangata, Kec. Anggrek',
            'latitude' => 0.6200,
            'longitude' => 123.1000,
            'melayani_nelayan' => true,
            'melayani_pertanian' => true,
            'is_active' => true,
        ]);

        $spbu3 = $upsertSpbu('SPBU-003', [
            'kecamatan_id' => $kecamatanIds['Atinggola'],
            'desa_id' => $desaIds['Kota Jin'] ?? null,
            'nama_spbu' => 'SPBU 74.962.04 Kota Jin Atinggola',
            'tipe_spbu' => 'SPBU Umum',
            'alamat' => 'Jl. Trans Sulawesi, Desa Kota Jin, Kec. Atinggola',
            'latitude' => 0.7450,
            'longitude' => 123.1200,
            'melayani_nelayan' => true,
            'melayani_pertanian' => true,
            'is_active' => true,
        ]);

        $spbu4 = $upsertSpbu('SPBU-004', [
            'kecamatan_id' => $kecamatanIds['Tolinggula'],
            'desa_id' => $desaIds['Tolite Jaya'] ?? null,
            'nama_spbu' => 'SPBU 76.965.01 Tolite Jaya Tolinggula',
            'tipe_spbu' => 'SPBU Kompak',
            'alamat' => 'Jl. Trans Sulawesi Gorontalo-Buol, Desa Tolite Jaya, Kec. Tolinggula',
            'latitude' => 0.5500,
            'longitude' => 123.2000,
            'melayani_nelayan' => true,
            'melayani_pertanian' => true,
            'is_active' => true,
        ]);

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

        $upsertDataBbm = function ($attributes) {
            $record = DataBbm::withTrashed()
                ->where('tanggal_input', $attributes['tanggal_input'])
                ->where('spbu_id', $attributes['spbu_id'])
                ->where('jenis_bbm', $attributes['jenis_bbm'])
                ->first() ?? new DataBbm();
            $record->fill($attributes);
            $record->deleted_at = null;
            $record->save();
            return $record;
        };

        // 1. SPBU Kwandang (Pertalite & Bio Solar)
        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'spbu_id' => $spbu1->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 12.0,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Kwandang'],
            'spbu_id' => $spbu1->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 8.0,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 6800,
            'status_verifikasi' => 'disetujui',
        ]);

        // 2. SPBU Anggrek (Pertalite & Bio Solar)
        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Anggrek'],
            'spbu_id' => $spbu2->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 10.5,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Anggrek'],
            'spbu_id' => $spbu2->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 0.8,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'panjang',
            'status_ketersediaan' => 'menipis',
            'harga_jual_per_liter' => 6800,
            'status_verifikasi' => 'disetujui',
            'keterangan_kendala' => 'Stok menipis, antrean panjang',
        ]);

        // 3. SPBU Atinggola (Pertalite & Bio Solar)
        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Atinggola'],
            'spbu_id' => $spbu3->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 14.0,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => false,
            'status_antrean' => 'tidak_ada',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Atinggola'],
            'spbu_id' => $spbu3->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 6.0,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 6800,
            'status_verifikasi' => 'disetujui',
        ]);

        // 4. SPBU Tolinggula (Pertalite & Bio Solar)
        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Tolinggula'],
            'spbu_id' => $spbu4->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 9.0,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $upsertDataBbm([
            'tanggal_input' => $tanggal,
            'kecamatan_id' => $kecamatanIds['Tolinggula'],
            'spbu_id' => $spbu4->id,
            'user_id' => $admin->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 5.5,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => true,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 6800,
            'status_verifikasi' => 'disetujui',
        ]);

        $mingguanSeeds = [
            ['tahun' => 2026, 'bulan' => 4, 'minggu_ke' => 'III', 'iph' => 1.85, 'ranking' => 18, 'komoditas' => 'Beras(0.12), Cabai Rawit(0.45)'],
            ['tahun' => 2026, 'bulan' => 4, 'minggu_ke' => 'IV', 'iph' => 2.10, 'ranking' => 15, 'komoditas' => 'Bawang Merah(0.31), Minyak Goreng(0.14)'],
            ['tahun' => 2026, 'bulan' => 5, 'minggu_ke' => 'I', 'iph' => 1.95, 'ranking' => 16, 'komoditas' => 'Cabai Rawit(0.22), Daging Ayam(0.18)'],
            ['tahun' => 2026, 'bulan' => 5, 'minggu_ke' => 'II', 'iph' => 2.33, 'ranking' => 14, 'komoditas' => 'Cabai Rawit(0.38), Bawang Merah(0.25)'],
        ];

        foreach ($mingguanSeeds as $seed) {
            IphMingguan::withTrashed()->firstOrNew(
                ['tahun' => $seed['tahun'], 'bulan' => $seed['bulan'], 'minggu_ke' => $seed['minggu_ke']]
            )->fill([
                'periode_label' => 'Minggu ' . $seed['minggu_ke'] . ' Bulan ' . $seed['bulan'] . ' ' . $seed['tahun'],
                'tanggal_awal_periode' => sprintf('%04d-%02d-01', $seed['tahun'], $seed['bulan']),
                'tanggal_akhir_periode' => sprintf('%04d-%02d-07', $seed['tahun'], $seed['bulan']),
                'tanggal_publikasi' => sprintf('%04d-%02d-10', $seed['tahun'], $seed['bulan']),
                'user_id' => $admin->id,
                'sumber_data' => 'BPS Kabupaten Gorontalo Utara',
                'iph_kabupaten' => $seed['iph'],
                'ranking' => $seed['ranking'],
                'komoditas_andil' => $seed['komoditas'],
                'komoditas_penyumbang_inflasi' => $seed['komoditas'],
                'status_verifikasi' => 'disetujui',
                'deleted_at' => null,
            ])->save();
        }

        $bulananSeeds = [
            ['tahun' => 2026, 'bulan' => 1, 'nama_bulan' => 'Januari', 'iph' => 1.20, 'ranking' => 22, 'komoditas' => 'Beras(0.25), Ikan(0.15)'],
            ['tahun' => 2026, 'bulan' => 2, 'nama_bulan' => 'Februari', 'iph' => 1.45, 'ranking' => 20, 'komoditas' => 'Cabai Merah(0.30), Telur(0.12)'],
            ['tahun' => 2026, 'bulan' => 3, 'nama_bulan' => 'Maret', 'iph' => 1.80, 'ranking' => 17, 'komoditas' => 'Daging Sapi(0.40), Bawang Putih(0.18)'],
            ['tahun' => 2026, 'bulan' => 4, 'nama_bulan' => 'April', 'iph' => 2.10, 'ranking' => 15, 'komoditas' => 'Minyak Goreng(0.35), Gula Pasir(0.20)'],
            ['tahun' => 2026, 'bulan' => 5, 'nama_bulan' => 'Mei', 'iph' => 1.85, 'ranking' => 16, 'komoditas' => 'Cabai Rawit(0.28), Beras(0.19)'],
            ['tahun' => 2026, 'bulan' => 6, 'nama_bulan' => 'Juni', 'iph' => 1.60, 'ranking' => 19, 'komoditas' => 'Bawang Merah(0.22), Cabai Merah(0.15)'],
        ];

        foreach ($bulananSeeds as $b) {
            IphBulanan::withTrashed()->firstOrNew(
                ['tahun' => $b['tahun'], 'bulan' => $b['bulan']]
            )->fill([
                'nama_bulan' => $b['nama_bulan'],
                'iph_bulanan' => $b['iph'],
                'ranking' => $b['ranking'],
                'komoditas_andil' => $b['komoditas'],
                'status_kategori' => 'terkendali',
                'user_id' => $admin->id,
                'deleted_at' => null,
            ])->save();
        }

        EwsAlert::firstOrCreate(
            ['kode_alert' => 'EWS-2026-001'],
            [
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
            ]
        );

        EwsAlert::firstOrCreate(
            ['kode_alert' => 'EWS-2026-002'],
            [
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
            ]
        );
    }
}
