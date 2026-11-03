<?php

namespace Tests\Feature;

use App\Models\IphBulanan;
use App\Models\IphMingguan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IphSpreadsheetTest extends TestCase
{
    use RefreshDatabase;

    public function test_iph_mingguan_page_renders_excel_view(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_1', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('input.iph.index'));

        $response->assertOk();
        $response->assertSee('IPH_Mingguan_Gorut.xlsx');
        $response->assertSee('Download Excel (.xlsx)');
        $response->assertSee('Input Data Baru ke Spreadsheet');
        $response->assertSee('Simpan ke Sheet');
        $response->assertSee('Sheet1 (IPH Mingguan)');
        $response->assertDontSee('Google Sheet');
    }

    public function test_can_input_iph_mingguan_and_persists_to_db_and_dashboard(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_2', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->post(route('input.iph.store'), [
            'tahun' => 2026,
            'bulan' => 'Mei',
            'minggu' => 'II',
            'iph' => 2.33,
            'ranking' => 14,
            'komoditas_andil' => 'Cabai Rawit (0.38), Bawang Merah (0.25)',
        ]);

        $response->assertRedirect(route('input.iph.index'));
        $this->assertDatabaseHas('iph_mingguan', [
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'II',
            'iph_kabupaten' => 2.33,
            'ranking' => 14,
        ]);

        // Check it appears on Excel table
        $indexResponse = $this->actingAs($user)->get(route('input.iph.index'));
        $indexResponse->assertSee('2,33%');
        $indexResponse->assertSee('#14');
        $indexResponse->assertSee('Cabai Rawit (0.38)');

        // Check it appears on dashboard monitoring tab iph-mingguan
        $dashboardResponse = $this->actingAs($user)->get(route('dashboard', ['tab' => 'iph-mingguan']));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('2,33%');
        $dashboardResponse->assertViewHas('chartMingguanLabels', fn ($labels) => in_array('M-II Mei', $labels, true));
        $dashboardResponse->assertViewHas('chartMingguanValues', fn ($vals) => in_array(2.33, $vals, true));
    }

    public function test_can_reinput_same_iph_mingguan_period_after_deleting_it(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_reinput', 'role' => 'admin_kabupaten']);
        $payload = [
            'tahun' => 2026,
            'bulan' => 'Januari',
            'minggu' => 'II',
            'iph' => 1.25,
        ];

        $this->actingAs($user)->post(route('input.iph.store'), $payload)
            ->assertRedirect(route('input.iph.index'));
        $record = IphMingguan::where(['tahun' => 2026, 'bulan' => 1, 'minggu_ke' => 'II'])->firstOrFail();

        $this->actingAs($user)->delete(route('input.iph.destroy', $record))
            ->assertSessionHas('success');
        $this->assertSoftDeleted('iph_mingguan', ['id' => $record->id]);

        $this->actingAs($user)->post(route('input.iph.store'), [...$payload, 'iph' => 3.10])
            ->assertRedirect(route('input.iph.index'))
            ->assertSessionHas('success');

        $this->assertSame(1, IphMingguan::withTrashed()->where(['tahun' => 2026, 'bulan' => 1, 'minggu_ke' => 'II'])->count());
        $this->assertDatabaseHas('iph_mingguan', [
            'id' => $record->id,
            'iph_kabupaten' => 3.10,
            'deleted_at' => null,
        ]);
    }

    public function test_can_reinput_same_iph_bulanan_period_after_deleting_it(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_reinput_bln', 'role' => 'admin_kabupaten']);
        $payload = ['tahun' => 2026, 'bulan' => 'Januari', 'iph_bulanan' => 1.10];

        $this->actingAs($user)->post(route('input.iph_bulanan.store'), $payload);
        $record = IphBulanan::where(['tahun' => 2026, 'bulan' => 1])->firstOrFail();

        $this->actingAs($user)->delete(route('input.iph_bulanan.destroy', $record));
        $this->assertSoftDeleted('iph_bulanan', ['id' => $record->id]);

        $this->actingAs($user)->post(route('input.iph_bulanan.store'), [...$payload, 'iph_bulanan' => 2.20])
            ->assertSessionHas('success');

        $this->assertSame(1, IphBulanan::withTrashed()->where(['tahun' => 2026, 'bulan' => 1])->count());
        $this->assertDatabaseHas('iph_bulanan', [
            'id' => $record->id,
            'iph_bulanan' => 2.20,
            'deleted_at' => null,
        ]);
    }

    public function test_can_export_iph_mingguan_as_excel_xlsx(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_3', 'role' => 'admin_kabupaten']);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'II',
            'periode_label' => 'Minggu II Mei 2026',
            'tanggal_awal_periode' => '2026-05-01',
            'tanggal_akhir_periode' => '2026-05-07',
            'tanggal_publikasi' => '2026-05-10',
            'user_id' => $user->id,
            'sumber_data' => 'BPS Gorut',
            'iph_kabupaten' => 2.33,
            'ranking' => 14,
            'komoditas_andil' => 'Cabai Rawit (0.38)',
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($user)->get(route('input.iph.export-excel'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_iph_bulanan_page_renders_excel_view(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_4', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('input.iph_bulanan'));

        $response->assertOk();
        $response->assertSee('IPH_Bulanan_Gorut.xlsx');
        $response->assertSee('Download Excel (.xlsx)');
        $response->assertSee('Input Data Bulanan ke Spreadsheet');
        $response->assertSee('Simpan ke Sheet');
        $response->assertSee('Sheet1 (IPH Bulanan)');
        $response->assertDontSee('Google Sheet');
    }

    public function test_can_input_iph_bulanan_and_persists_to_db_and_dashboard(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_5', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->post(route('input.iph_bulanan.store'), [
            'tahun' => 2026,
            'bulan' => 'Mei',
            'iph_bulanan' => 1.85,
            'ranking' => 16,
            'komoditas_andil' => 'Cabai Rawit (0.28), Beras (0.19)',
            'catatan' => 'Rilis resmi BPS Mei 2026',
        ]);

        $response->assertRedirect(route('input.iph_bulanan'));
        $this->assertDatabaseHas('iph_bulanan', [
            'tahun' => 2026,
            'bulan' => 5,
            'nama_bulan' => 'Mei',
            'iph_bulanan' => 1.85,
            'ranking' => 16,
        ]);

        // Check it appears on Excel table
        $indexResponse = $this->actingAs($user)->get(route('input.iph_bulanan'));
        $indexResponse->assertSee('1,85%');
        $indexResponse->assertSee('#16');
        $indexResponse->assertSee('Rilis resmi BPS Mei 2026');

        // Check it appears on dashboard monitoring tab iph-bulanan
        $dashboardResponse = $this->actingAs($user)->get(route('dashboard', ['tab' => 'iph-bulanan']));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('1,85%');
        $dashboardResponse->assertViewHas('chartBulananLabels', fn ($labels) => in_array('Mei', $labels, true));
        $dashboardResponse->assertViewHas('chartBulananValues', fn ($vals) => in_array(1.85, $vals, true));
    }

    public function test_can_export_iph_bulanan_as_excel_xlsx(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_6', 'role' => 'admin_kabupaten']);

        IphBulanan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'nama_bulan' => 'Mei',
            'iph_bulanan' => 1.85,
            'ranking' => 16,
            'komoditas_andil' => 'Cabai Rawit (0.28)',
            'status_kategori' => 'terkendali',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('input.iph_bulanan.export-excel'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->assertStringContainsString('.xlsx', $response->headers->get('Content-Disposition') ?? '');
    }

    public function test_can_delete_iph_rows(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_7', 'role' => 'admin_kabupaten']);

        $m = IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 1,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Januari 2026',
            'tanggal_awal_periode' => '2026-01-01',
            'tanggal_akhir_periode' => '2026-01-07',
            'tanggal_publikasi' => '2026-01-10',
            'user_id' => $user->id,
            'sumber_data' => 'BPS Gorut',
            'iph_kabupaten' => 1.5,
            'status_verifikasi' => 'disetujui',
        ]);

        $b = IphBulanan::create([
            'tahun' => 2026,
            'bulan' => 1,
            'nama_bulan' => 'Januari',
            'iph_bulanan' => 1.5,
            'status_kategori' => 'terkendali',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->delete(route('input.iph.destroy', $m->id))->assertRedirect();
        $this->assertSoftDeleted('iph_mingguan', ['id' => $m->id]);

        $this->actingAs($user)->delete(route('input.iph_bulanan.destroy', $b->id))->assertRedirect();
        $this->assertSoftDeleted('iph_bulanan', ['id' => $b->id]);
    }

    public function test_monitoring_iph_mingguan_filters_by_year_and_week(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_8', 'role' => 'admin_kabupaten']);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Mei 2026',
            'tanggal_awal_periode' => '2026-05-01',
            'tanggal_akhir_periode' => '2026-05-07',
            'tanggal_publikasi' => '2026-05-08',
            'user_id' => $user->id,
            'sumber_data' => 'BPS Gorut',
            'iph_kabupaten' => 1.95,
            'status_verifikasi' => 'disetujui',
        ]);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'II',
            'periode_label' => 'Minggu II Mei 2026',
            'tanggal_awal_periode' => '2026-05-08',
            'tanggal_akhir_periode' => '2026-05-14',
            'tanggal_publikasi' => '2026-05-15',
            'user_id' => $user->id,
            'sumber_data' => 'BPS Gorut',
            'iph_kabupaten' => 2.33,
            'status_verifikasi' => 'disetujui',
        ]);

        // Filter week II
        $resp = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'iph-mingguan',
            'iph_mingguan_tahun' => '2026',
            'iph_mingguan_minggu' => 'II',
        ]));

        $resp->assertOk();
        $resp->assertSee('Minggu II');
        $resp->assertSee('2,33%');
        $resp->assertViewHas('chartMingguanLabels', fn ($labels) => $labels === ['M-II Mei']);
        $resp->assertViewHas('chartMingguanValues', fn ($vals) => $vals === [2.33]);
    }

    public function test_monitoring_iph_bulanan_filters_by_month_and_year(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_9', 'role' => 'admin_kabupaten']);

        IphBulanan::create([
            'tahun' => 2026,
            'bulan' => 4,
            'nama_bulan' => 'April',
            'iph_bulanan' => 2.10,
            'status_kategori' => 'terkendali',
            'user_id' => $user->id,
        ]);

        IphBulanan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'nama_bulan' => 'Mei',
            'iph_bulanan' => 1.85,
            'status_kategori' => 'terkendali',
            'user_id' => $user->id,
        ]);

        // Filter month 5 (Mei)
        $resp = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'iph-bulanan',
            'iph_bulanan_tahun' => '2026',
            'iph_bulanan_bulan' => '5',
        ]));

        $resp->assertOk();
        $resp->assertSee('Mei 2026');
        $resp->assertSee('1,85%');
        $resp->assertViewHas('chartBulananLabels', fn ($labels) => $labels === ['Mei']);
        $resp->assertViewHas('chartBulananValues', fn ($vals) => $vals === [1.85]);
    }

    public function test_monitoring_iph_mingguan_filters_by_companion_week_range(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_10', 'role' => 'admin_kabupaten']);

        foreach ([
            ['minggu' => 'I', 'val' => 1.10],
            ['minggu' => 'II', 'val' => 2.20],
            ['minggu' => 'III', 'val' => 3.30],
            ['minggu' => 'IV', 'val' => 4.40],
            ['minggu' => 'V', 'val' => 5.50],
        ] as $item) {
            IphMingguan::create([
                'tahun' => 2026,
                'bulan' => 5,
                'minggu_ke' => $item['minggu'],
                'periode_label' => "Minggu {$item['minggu']} Mei 2026",
                'tanggal_awal_periode' => '2026-05-01',
                'tanggal_akhir_periode' => '2026-05-31',
                'tanggal_publikasi' => '2026-05-31',
                'user_id' => $user->id,
                'sumber_data' => 'BPS Gorut',
                'iph_kabupaten' => $item['val'],
                'status_verifikasi' => 'disetujui',
            ]);
        }

        // Test filter range: Minggu II ke Minggu IV
        $resp = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'iph-mingguan',
            'iph_mingguan_tahun' => '2026',
            'iph_mingguan_dari_minggu' => 'II',
            'iph_mingguan_sampai_minggu' => 'IV',
        ]));

        $resp->assertOk();
        $resp->assertViewHas('chartMingguanLabels', fn ($labels) => $labels === ['M-II Mei', 'M-III Mei', 'M-IV Mei']);
        $resp->assertViewHas('chartMingguanValues', fn ($vals) => $vals === [2.20, 3.30, 4.40]);
    }

    public function test_monitoring_iph_bulanan_filters_by_companion_month_range(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_11', 'role' => 'admin_kabupaten']);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni'
        ];

        foreach ($months as $num => $nama) {
            IphBulanan::create([
                'tahun' => 2026,
                'bulan' => $num,
                'nama_bulan' => $nama,
                'iph_bulanan' => (float) ("1.{$num}0"),
                'status_kategori' => 'terkendali',
                'user_id' => $user->id,
            ]);
        }

        // Test filter range: Januari (1) sampai Mei (5)
        $resp = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'iph-bulanan',
            'iph_bulanan_tahun' => '2026',
            'iph_bulanan_dari_bulan' => '1',
            'iph_bulanan_sampai_bulan' => '5',
        ]));

        $resp->assertOk();
        $resp->assertViewHas('chartBulananLabels', fn ($labels) => $labels === ['Januari', 'Februari', 'Maret', 'April', 'Mei']);
        $resp->assertViewHas('chartBulananValues', fn ($vals) => $vals === [1.10, 1.20, 1.30, 1.40, 1.50]);
    }

    public function test_input_pages_do_not_contain_google_sheets_links(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_12', 'role' => 'admin_kabupaten']);

        $respMingguan = $this->actingAs($user)->get(route('input.iph.index'));
        $respMingguan->assertOk();
        $respMingguan->assertDontSee('Google Sheets');
        $respMingguan->assertDontSee('docs.google.com/spreadsheets');

        $respBulanan = $this->actingAs($user)->get(route('input.iph_bulanan'));
        $respBulanan->assertOk();
        $respBulanan->assertDontSee('Google Sheets');
        $respBulanan->assertDontSee('docs.google.com/spreadsheets');
    }

    public function test_iph_tables_and_exports_do_not_contain_status_column(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_nostatus', 'role' => 'admin_kabupaten']);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Mei 2026',
            'tanggal_awal_periode' => '2026-05-01',
            'tanggal_akhir_periode' => '2026-05-07',
            'tanggal_publikasi' => '2026-05-10',
            'iph_kabupaten' => 2.50,
            'ranking' => 10,
            'komoditas_andil' => 'Cabai Rawit (0.3)',
            'status_kategori' => 'waspada',
            'user_id' => $user->id,
        ]);

        IphBulanan::create([
            'tahun' => 2026,
            'bulan' => 5,
            'nama_bulan' => 'Mei',
            'iph_bulanan' => 1.80,
            'ranking' => 12,
            'komoditas_andil' => 'Beras (0.2)',
            'status_kategori' => 'terkendali',
            'user_id' => $user->id,
        ]);

        // Input Mingguan Page
        $respMingguan = $this->actingAs($user)->get(route('input.iph.index'));
        $respMingguan->assertOk();
        $respMingguan->assertDontSee('>STATUS<', false);
        $respMingguan->assertSee('Komoditas Andil Utama');

        // Input Bulanan Page
        $respBulanan = $this->actingAs($user)->get(route('input.iph_bulanan'));
        $respBulanan->assertOk();
        $respBulanan->assertDontSee('>STATUS<', false);
        $respBulanan->assertSee('Komoditas Andil Utama');

        // Excel Mingguan Export
        $exportMingguan = $this->actingAs($user)->get(route('input.iph.export-excel'));
        $exportMingguan->assertOk();

        // Excel Bulanan Export
        $exportBulanan = $this->actingAs($user)->get(route('input.iph_bulanan.export-excel'));
        $exportBulanan->assertOk();
    }

    public function test_iph_mingguan_month_range_filter_and_chart_komoditas_details(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_range', 'role' => 'admin_kabupaten']);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 1,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Januari 2026',
            'tanggal_awal_periode' => '2026-01-01',
            'tanggal_akhir_periode' => '2026-01-07',
            'tanggal_publikasi' => '2026-01-10',
            'iph_kabupaten' => 1.10,
            'ranking' => 20,
            'komoditas_andil' => 'Beras (0.15)',
            'user_id' => $user->id,
        ]);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 3,
            'minggu_ke' => 'II',
            'periode_label' => 'Minggu II Maret 2026',
            'tanggal_awal_periode' => '2026-03-08',
            'tanggal_akhir_periode' => '2026-03-14',
            'tanggal_publikasi' => '2026-03-17',
            'iph_kabupaten' => 2.20,
            'ranking' => 15,
            'komoditas_andil' => 'Cabai Rawit (0.35), Bawang Merah (0.20)',
            'user_id' => $user->id,
        ]);

        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 6,
            'minggu_ke' => 'III',
            'periode_label' => 'Minggu III Juni 2026',
            'tanggal_awal_periode' => '2026-06-15',
            'tanggal_akhir_periode' => '2026-06-21',
            'tanggal_publikasi' => '2026-06-24',
            'iph_kabupaten' => 3.30,
            'ranking' => 5,
            'komoditas_andil' => 'Minyak Goreng (0.45)',
            'user_id' => $user->id,
        ]);

        // Filter range: Bulan 2 (Feb) sampai Bulan 4 (Apr)
        $resp = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'iph-mingguan',
            'iph_mingguan_tahun' => '2026',
            'iph_mingguan_dari_bulan' => '2',
            'iph_mingguan_sampai_bulan' => '4',
        ]));

        $resp->assertOk();
        $resp->assertViewHas('chartMingguanLabels', fn ($labels) => $labels === ['M-II Mar']);
        $resp->assertViewHas('chartMingguanValues', fn ($vals) => $vals === [2.20]);
        $resp->assertViewHas('chartMingguanDetails', function ($details) {
            return count($details) === 1
                && $details[0]['ranking'] === 15
                && count($details[0]['komoditas_list']) === 2
                && $details[0]['komoditas_list'][0]['nama'] === 'Cabai Rawit'
                && $details[0]['komoditas_list'][0]['raw_val'] === 0.35;
        });

        // Check HTML renders popup overlay and that separate bottom tables are removed
        $resp->assertSee('iphMingguanPopup');
        $resp->assertSee('iphBulananPopup');
        $resp->assertDontSee('iphMingguanDetailSection');
        $resp->assertDontSee('iphBulananDetailSection');
        $resp->assertDontSee('Tabel Komoditas Andil Utama');

        // Chart header filter bulan removed per user request
        $resp->assertDontSee('id="chart_filter_bulan"', false);
    }

    public function test_iph_year_dropdowns_have_no_duplicates_and_dynamically_include_inputted_years(): void
    {
        $user = User::factory()->create(['username' => 'admin_test_year_unique', 'role' => 'admin_kabupaten']);

        // Insert multiple records for 2026
        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 1,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Januari 2026',
            'tanggal_awal_periode' => '2026-01-01',
            'tanggal_akhir_periode' => '2026-01-07',
            'tanggal_publikasi' => '2026-01-10',
            'iph_kabupaten' => 1.5,
            'user_id' => $user->id,
        ]);
        IphMingguan::create([
            'tahun' => 2026,
            'bulan' => 2,
            'minggu_ke' => 'II',
            'periode_label' => 'Minggu II Februari 2026',
            'tanggal_awal_periode' => '2026-02-08',
            'tanggal_akhir_periode' => '2026-02-14',
            'tanggal_publikasi' => '2026-02-17',
            'iph_kabupaten' => 1.7,
            'user_id' => $user->id,
        ]);

        // Insert year 2027
        IphMingguan::create([
            'tahun' => 2027,
            'bulan' => 1,
            'minggu_ke' => 'I',
            'periode_label' => 'Minggu I Januari 2027',
            'tanggal_awal_periode' => '2027-01-01',
            'tanggal_akhir_periode' => '2027-01-07',
            'tanggal_publikasi' => '2027-01-10',
            'iph_kabupaten' => 2.0,
            'user_id' => $user->id,
        ]);

        // Check Dashboard
        $dashboardResp = $this->actingAs($user)->get(route('dashboard', ['tab' => 'iph-mingguan']));
        $dashboardResp->assertOk();
        $dashboardResp->assertViewHas('daftarTahunMingguan', function ($years) {
            // Must have 2027 and 2026, and exactly 2 elements (no duplicate 2026)
            return $years === [2027, 2026];
        });

        // Check Input Spreadsheet Mingguan
        $inputResp = $this->actingAs($user)->get(route('input.iph.index'));
        $inputResp->assertOk();
        $inputResp->assertViewHas('daftarTahun', function ($years) {
            return $years === [2027, 2026];
        });
    }
}
