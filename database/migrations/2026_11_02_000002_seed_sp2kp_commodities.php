<?php

use App\Models\Komoditas;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('komoditas')) {
            return;
        }

        $sp2kpItems = [
            ['SP2KP-122004', 'Bawang Bombai', 'Kg', 30000, 25000, 35000],
            ['SP2KP-122001', 'Bawang Merah', 'Kg', 35000, 28000, 42000],
            ['SP2KP-122002', 'Bawang Putih Honan', 'Kg', 36000, 30000, 42000],
            ['SP2KP-122003', 'Bawang Putih Kating', 'Kg', 42000, 35000, 50000],
            ['SP2KP-111003', 'Beras Khusus', 'Kg', 18000, 16000, 22000],
            ['SP2KP-111005', 'Beras Medium', 'Kg', 14000, 12500, 15500],
            ['SP2KP-111001', 'Beras Premium', 'Kg', 15500, 14000, 17500],
            ['SP2KP-111004', 'Beras SPHP Bulog', 'Kg', 12500, 11500, 13500],
            ['SP2KP-137', 'Buncis', 'Kg', 18000, 14000, 22000],
            ['SP2KP-121001', 'Cabai Merah Besar', 'Kg', 45000, 35000, 55000],
            ['SP2KP-121002', 'Cabai Merah Keriting', 'Kg', 48000, 38000, 60000],
            ['SP2KP-121004', 'Cabai Rawit Hijau', 'Kg', 42000, 32000, 52000],
            ['SP2KP-121003', 'Cabai Rawit Merah', 'Kg', 65000, 50000, 80000],
            ['SP2KP-131002', 'Caisim', 'Kg', 10000, 8000, 14000],
            ['SP2KP-115002', 'Daging Ayam Kampung', 'Ekor', 65000, 55000, 75000],
            ['SP2KP-115001', 'Daging Ayam Ras', 'Kg', 38000, 34000, 42000],
            ['SP2KP-138', 'Daging Babi', 'Kg', 85000, 75000, 95000],
            ['SP2KP-114006', 'Daging Kerbau Impor Beku', 'Kg', 85000, 80000, 95000],
            ['SP2KP-114005', 'Daging Sapi Impor Beku', 'Kg', 95000, 85000, 110000],
            ['SP2KP-114001', 'Daging Sapi Paha Belakang', 'Kg', 135000, 125000, 145000],
            ['SP2KP-114002', 'Daging Sapi Paha Depan', 'Kg', 130000, 120000, 140000],
            ['SP2KP-114003', 'Daging Sapi Sandung Lamur', 'Kg', 115000, 105000, 125000],
            ['SP2KP-114004', 'Daging Sapi Tetelan', 'Kg', 90000, 80000, 100000],
            ['SP2KP-125001', 'Garam Halus', 'Kg', 10000, 8000, 12000],
            ['SP2KP-112001', 'Gula Pasir Curah', 'Kg', 17000, 15000, 18500],
            ['SP2KP-112002', 'Gula Pasir Kemasan', 'Kg', 18000, 16000, 20000],
            ['SP2KP-123003', 'Ikan Bandeng', 'Kg', 32000, 28000, 38000],
            ['SP2KP-123001', 'Ikan Kembung', 'Kg', 38000, 32000, 45000],
            ['SP2KP-123002', 'Ikan Teri', 'Kg', 65000, 55000, 75000],
            ['SP2KP-123004', 'Ikan Tongkol', 'Kg', 30000, 25000, 38000],
            ['SP2KP-118001', 'Jagung Lokal Pipilan', 'Kg', 8500, 7000, 10000],
            ['SP2KP-130001', 'Jeruk Lokal', 'Kg', 22000, 18000, 28000],
            ['SP2KP-127002', 'Kacang Hijau', 'Kg', 24000, 20000, 28000],
            ['SP2KP-135001', 'Kacang Panjang', 'Kg', 12000, 9000, 15000],
            ['SP2KP-127001', 'Kacang Tanah', 'Kg', 28000, 24000, 32000],
            ['SP2KP-134001', 'Kangkung', 'Kg', 8000, 6000, 12000],
            ['SP2KP-120001', 'Kedelai Impor', 'Kg', 13000, 11500, 14500],
            ['SP2KP-120002', 'Kedelai Lokal', 'Kg', 12500, 11000, 14000],
            ['SP2KP-133001', 'Kentang Besar', 'Kg', 22000, 18000, 26000],
            ['SP2KP-133002', 'Kentang Sedang', 'Kg', 19000, 16000, 23000],
            ['SP2KP-128001', 'Ketela Pohon', 'Kg', 6000, 4500, 8000],
            ['SP2KP-136001', 'Ketimun Besar', 'Kg', 10000, 8000, 13000],
            ['SP2KP-136002', 'Ketimun Sedang', 'Kg', 8500, 7000, 11000],
            ['SP2KP-126001', 'Mie Instan', 'Bungkus', 3500, 3000, 4000],
            ['SP2KP-113001', 'Minyak Goreng Sawit Curah', 'Liter', 16000, 14500, 17500],
            ['SP2KP-113002', 'Minyak Goreng Sawit Kemasan Premium', 'Liter', 21000, 19000, 23500],
            ['SP2KP-113003', 'Minyakita', 'Liter', 15700, 14500, 16500],
            ['SP2KP-131003', 'Pakchoy', 'Kg', 12000, 9000, 16000],
            ['SP2KP-129001', 'Pisang Lokal', 'Kg', 15000, 12000, 20000],
            ['SP2KP-131001', 'Sawi Hijau', 'Kg', 11000, 8000, 15000],
            ['SP2KP-117001', 'Susu Bubuk', '400gr', 45000, 40000, 52000],
            ['SP2KP-117002', 'Susu Bubuk Balita', '400gr', 48000, 42000, 56000],
            ['SP2KP-117003', 'Susu Kental Manis', '370gr', 13000, 11500, 15000],
            ['SP2KP-120003', 'Tahu Putih', 'Kg', 12000, 10000, 15000],
            ['SP2KP-116002', 'Telur Ayam Kampung', 'Kg', 42000, 36000, 48000],
            ['SP2KP-116001', 'Telur Ayam Ras', 'Kg', 29000, 26000, 32000],
            ['SP2KP-120004', 'Tempe Bungkus', 'Kg', 14000, 12000, 16500],
            ['SP2KP-119001', 'Tepung Terigu', 'Kg', 12500, 10500, 14000],
            ['SP2KP-132001', 'Tomat', 'Kg', 16000, 12000, 22000],
            ['SP2KP-124001', 'Udang Basah', 'Kg', 65000, 55000, 80000],
        ];

        foreach ($sp2kpItems as $item) {
            [$kode, $nama, $satuan, $het, $bawah, $atas] = $item;

            // Cari berdasarkan nama terlebih dahulu, lalu kode
            $existing = Komoditas::where('nama_komoditas', $nama)
                ->orWhere('kode_komoditas', $kode)
                ->first();

            if ($existing) {
                $existing->update([
                    'nama_komoditas' => $nama,
                    'satuan' => $satuan,
                    'is_strategis' => true,
                    'is_active' => true,
                ]);
            } else {
                Komoditas::create([
                    'kode_komoditas' => $kode,
                    'nama_komoditas' => $nama,
                    'kategori' => 'pangan',
                    'satuan' => $satuan,
                    'harga_het' => $het,
                    'batas_bawah_harga_normal' => $bawah,
                    'batas_atas_harga_normal' => $atas,
                    'stok_aman_minimum' => 100,
                    'persentase_kenaikan_waspada' => 10,
                    'persentase_kenaikan_bahaya' => 25,
                    'is_strategis' => true,
                    'is_active' => true,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Tetap simpan komoditas agar relasi data tidak rusak
    }
};

