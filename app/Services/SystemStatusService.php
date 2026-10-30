<?php

namespace App\Services;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\EwsAlert;
use Illuminate\Support\Facades\Schema;

class SystemStatusService
{
    /**
     * Mengambil status komprehensif untuk Pangan, LPG, dan BBM.
     * Hasil sinkron dengan data inputan terbaru di database dan EWS Alert.
     *
     * @return array<string, array{label: string, status: string, dot_class: string, text_class: string, bg_pill: string}>
     */
    public static function getHeaderStatuses(): array
    {
        return [
            'pangan' => self::getPanganStatus(),
            'lpg' => self::getLpgStatus(),
            'bbm' => self::getBbmStatus(),
        ];
    }

    public static function getPanganStatus(): array
    {
        $status = 'AMAN';

        try {
            if (Schema::hasTable('data_pangan')) {
                $hasCriticalAlert = false;
                $hasWarningAlert = false;

                if (Schema::hasTable('ews_alerts')) {
                    $alerts = EwsAlert::where('kategori', 'pangan')
                        ->whereIn('status', ['terbuka', 'diproses'])
                        ->get();

                    $hasCriticalAlert = $alerts->contains(fn ($a) => in_array($a->tingkat_risiko, ['kritis', 'tinggi']));
                    $hasWarningAlert = $alerts->contains(fn ($a) => $a->tingkat_risiko === 'sedang');
                }

                // Ambil entri terbaru per komoditas
                $latestRecords = DataPangan::orderBy('tanggal_input', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->unique('komoditas_id');

                $hasCriticalData = $latestRecords->contains(function ($r) {
                    return $r->status_ketersediaan === 'kosong'
                        || $r->status_harga === 'harga_tinggi'
                        || (float) $r->perubahan_harga_persen >= 25;
                });

                $hasWarningData = $latestRecords->contains(function ($r) {
                    return in_array($r->status_ketersediaan, ['kurang', 'waspada'])
                        || $r->status_harga === 'naik_tinggi'
                        || (float) $r->perubahan_harga_persen >= 10;
                });

                if ($hasCriticalAlert || $hasCriticalData) {
                    $status = 'BAHAYA';
                } elseif ($hasWarningAlert || $hasWarningData) {
                    $status = 'WASPADA';
                }
            }
        } catch (\Throwable $e) {
            $status = 'AMAN';
        }

        return self::formatStatusPayload('STATUS PANGAN', $status);
    }

    public static function getLpgStatus(): array
    {
        $status = 'AMAN';

        try {
            if (Schema::hasTable('data_lpg')) {
                $hasCriticalAlert = false;
                $hasWarningAlert = false;

                if (Schema::hasTable('ews_alerts')) {
                    $alerts = EwsAlert::where('kategori', 'lpg')
                        ->whereIn('status', ['terbuka', 'diproses'])
                        ->get();

                    $hasCriticalAlert = $alerts->contains(fn ($a) => in_array($a->tingkat_risiko, ['kritis', 'tinggi']));
                    $hasWarningAlert = $alerts->contains(fn ($a) => $a->tingkat_risiko === 'sedang');
                }

                // Ambil entri terbaru per pangkalan & jenis
                $latestRecords = DataLpg::orderBy('tanggal_input', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->unique(fn ($r) => $r->pangkalan_lpg_id . '-' . $r->jenis_lpg);

                $hasCriticalData = $latestRecords->contains(function ($r) {
                    return $r->status_ketersediaan === 'kosong'
                        || $r->status_harga === 'melampaui_het_tinggi'
                        || in_array($r->status_distribusi, ['macet', 'panic_buying']);
                });

                $hasWarningData = $latestRecords->contains(function ($r) {
                    return in_array($r->status_ketersediaan, ['menipis', 'waspada'])
                        || $r->status_harga === 'melampaui_het_ringan'
                        || $r->status_distribusi === 'terhambat';
                });

                if ($hasCriticalAlert || $hasCriticalData) {
                    $status = 'BAHAYA';
                } elseif ($hasWarningAlert || $hasWarningData) {
                    $status = 'WASPADA';
                }
            }
        } catch (\Throwable $e) {
            $status = 'AMAN';
        }

        return self::formatStatusPayload('STATUS LPG', $status);
    }

    public static function getBbmStatus(): array
    {
        $status = 'AMAN';

        try {
            if (Schema::hasTable('data_bbm')) {
                $hasCriticalAlert = false;
                $hasWarningAlert = false;

                if (Schema::hasTable('ews_alerts')) {
                    $alerts = EwsAlert::where('kategori', 'bbm')
                        ->whereIn('status', ['terbuka', 'diproses'])
                        ->get();

                    $hasCriticalAlert = $alerts->contains(fn ($a) => in_array($a->tingkat_risiko, ['kritis', 'tinggi']));
                    $hasWarningAlert = $alerts->contains(fn ($a) => $a->tingkat_risiko === 'sedang');
                }

                // Ambil entri terbaru per SPBU & jenis BBM
                $latestRecords = DataBbm::orderBy('tanggal_input', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->unique(fn ($r) => $r->spbu_id . '-' . $r->jenis_bbm);

                $hasCriticalData = $latestRecords->contains(function ($r) {
                    return $r->status_ketersediaan === 'kosong'
                        || $r->status_antrean === 'sangat_panjang';
                });

                $hasWarningData = $latestRecords->contains(function ($r) {
                    return in_array($r->status_ketersediaan, ['menipis', 'waspada'])
                        || $r->status_antrean === 'panjang';
                });

                if ($hasCriticalAlert || $hasCriticalData) {
                    $status = 'BAHAYA';
                } elseif ($hasWarningAlert || $hasWarningData) {
                    $status = 'WASPADA';
                }
            }
        } catch (\Throwable $e) {
            $status = 'AMAN';
        }

        return self::formatStatusPayload('STATUS BBM', $status);
    }

    private static function formatStatusPayload(string $label, string $status): array
    {
        return match ($status) {
            'BAHAYA' => [
                'label' => $label,
                'status' => 'BAHAYA',
                'dot_class' => 'bg-status-bahaya',
                'text_class' => 'text-status-bahaya',
                'bg_pill' => 'bg-rose-50 border-rose-200 text-rose-700',
            ],
            'WASPADA' => [
                'label' => $label,
                'status' => 'WASPADA',
                'dot_class' => 'bg-status-waspada',
                'text_class' => 'text-status-waspada',
                'bg_pill' => 'bg-amber-50 border-amber-200 text-amber-700',
            ],
            default => [
                'label' => $label,
                'status' => 'AMAN',
                'dot_class' => 'bg-status-aman',
                'text_class' => 'text-status-aman',
                'bg_pill' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
            ],
        };
    }
}

