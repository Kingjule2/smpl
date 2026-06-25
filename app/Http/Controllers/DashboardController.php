<?php

namespace App\Http\Controllers;

use App\Models\Kolam;
use App\Models\MonitoringLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with form and analysis results.
     */
    public function index()
    {
        $kolams = Kolam::where('user_id', Auth::id())
            ->with(['monitoringLogs' => function ($q) {
                $q->orderBy('created_at', 'desc')->limit(1);
            }])
            ->get();

        // Group kolams by jenis for grid display
        $kolamBibit = $kolams->where('jenis_kolam', 'bibit');
        $kolamPembesaran = $kolams->where('jenis_kolam', 'pembesaran');
        $kolamFinishing = $kolams->where('jenis_kolam', 'finishing');

        // Ambil 20 monitoring log terakhir untuk grafik tren
        $logs = MonitoringLog::whereIn('kolam_id', $kolams->pluck('id'))
            ->with('kolam')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get()
            ->reverse()
            ->values();

        // Ambil hasil analisis terakhir jika ada
        $latestLog = MonitoringLog::whereIn('kolam_id', $kolams->pluck('id'))
            ->with('kolam')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('dashboard', compact(
            'kolams',
            'kolamBibit',
            'kolamPembesaran',
            'kolamFinishing',
            'logs',
            'latestLog'
        ));
    }

    /**
     * Register a new kolam (one-time setup per kolam).
     */
    public function storeKolam(Request $request)
    {
        $validated = $request->validate([
            'nama_kolam' => 'required|string|max:255',
            'volume_kolam' => 'required|numeric|min:0.1|max:10000',
            'tgl_tebar' => 'required|date|before_or_equal:today',
            'metode_budidaya' => 'required|in:bioflok,konvensional',
            'jenis_kolam' => 'required|in:bibit,pembesaran,finishing',
            'status_awal' => 'required|in:kosong,terisi',
        ]);

        $kolam = Kolam::create([
            'user_id' => Auth::id(),
            'nama_kolam' => $validated['nama_kolam'],
            'volume_kolam' => $validated['volume_kolam'],
            'tgl_tebar' => $validated['tgl_tebar'],
            'metode_budidaya' => $validated['metode_budidaya'],
            'jenis_kolam' => $validated['jenis_kolam'],
            'status_kolam' => $validated['status_awal'],
            'tgl_masuk' => $validated['status_awal'] === 'terisi' ? $validated['tgl_tebar'] : null,
        ]);

        $jenisLabel = match ($validated['jenis_kolam']) {
            'bibit' => '🔺 Bibit',
            'pembesaran' => '⭕ Pembesaran',
            'finishing' => '⬛ Finishing',
        };

        return redirect()->route('dashboard')->with('success', "Kolam \"{$validated['nama_kolam']}\" ({$jenisLabel}) berhasil didaftarkan!");
    }

    /**
     * Konfirmasi pindah ikan ke tahap selanjutnya.
     */
    public function confirmMove(Request $request, Kolam $kolam)
    {
        // Pastikan kolam milik user
        if ($kolam->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'kolam_tujuan_id' => 'required|exists:kolams,id',
        ]);

        $kolamTujuan = Kolam::findOrFail($validated['kolam_tujuan_id']);

        // Pastikan kolam tujuan milik user & statusnya kosong
        if ($kolamTujuan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($kolamTujuan->status_kolam !== 'kosong') {
            return redirect()->route('dashboard')->with('error', 'Kolam tujuan harus dalam status kosong!');
        }

        // Pindahkan: kolam asal → kosong, kolam tujuan → terisi
        $kolam->update([
            'status_kolam' => 'kosong',
            'tgl_masuk' => null,
        ]);

        $kolamTujuan->update([
            'status_kolam' => 'terisi',
            'tgl_masuk' => Carbon::now()->toDateString(),
        ]);

        return redirect()->route('dashboard')->with('success', "Ikan dari \"{$kolam->nama_kolam}\" berhasil dipindahkan ke \"{$kolamTujuan->nama_kolam}\"! 🐟");
    }

    /**
     * Konfirmasi panen dari kolam finishing.
     */
    public function confirmHarvest(Kolam $kolam)
    {
        if ($kolam->user_id !== Auth::id()) {
            abort(403);
        }

        $kolam->update([
            'status_kolam' => 'kosong',
            'tgl_masuk' => null,
        ]);

        return redirect()->route('dashboard')->with('success', "🎉 Panen dari \"{$kolam->nama_kolam}\" berhasil! Kolam sekarang kosong dan siap diisi ulang.");
    }

    /**
     * Process monitoring input (water parameters only) and run analysis logic.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kolam_id' => 'required|exists:kolams,id',
            'suhu' => 'required|numeric|min:0|max:50',
            'ph' => 'required|numeric|min:0|max:14',
            'amonia' => 'required|numeric|min:0|max:100',
        ]);

        $kolam = Kolam::findOrFail($validated['kolam_id']);

        // ===== 1. CEK KELAYAKAN PANEN =====
        $umurIkan = (int) Carbon::parse($kolam->tgl_tebar)->diffInDays(Carbon::now());
        $metode = $kolam->metode_budidaya;

        $batasHari = $metode === 'bioflok' ? 80 : 100;
        $statusPanen = $umurIkan >= $batasHari ? 'Siap Panen' : 'Belum Siap';
        $sisaHari = (int) max(0, $batasHari - $umurIkan);

        // ===== 2. CEK KONDISI AIR (THRESHOLD JURNAL) =====
        $rekomendasi = [];
        $kondisiAir = 'normal';

        // Cek Amonia > 0.5 ppm
        if ($validated['amonia'] > 0.5) {
            $kondisiAir = 'bahaya';
            $dosisMolase = $kolam->volume_kolam * 100; // ml
            $rekomendasi[] = "⚠️ Kadar Amonia BAHAYA ({$validated['amonia']} ppm). Segera tambahkan " . number_format($dosisMolase, 0, ',', '.') . " ml Molase ke {$kolam->nama_kolam}.";
        }

        // Cek pH < 7
        if ($validated['ph'] < 7) {
            $kondisiAir = 'bahaya';
            $dosisKapur = $kolam->volume_kolam * 100; // gram
            $rekomendasi[] = "⚠️ pH Air RENDAH ({$validated['ph']}). Segera tambahkan " . number_format($dosisKapur, 0, ',', '.') . " gram Kapur ke {$kolam->nama_kolam}.";
        }

        // Cek Suhu di luar range 25-30°C
        if ($validated['suhu'] < 25 || $validated['suhu'] > 30) {
            $kondisiAir = 'bahaya';
            if ($validated['suhu'] < 25) {
                $rekomendasi[] = "🌡️ Suhu Air TERLALU RENDAH ({$validated['suhu']}°C). Gunakan heater atau tutup kolam untuk menaikkan suhu ke range ideal 25-30°C.";
            } else {
                $rekomendasi[] = "🌡️ Suhu Air TERLALU TINGGI ({$validated['suhu']}°C). Tambahkan aerasi atau beri peneduh untuk menurunkan suhu ke range ideal 25-30°C.";
            }
        }

        // Jika semua normal
        if (empty($rekomendasi)) {
            $rekomendasi[] = "✅ Semua parameter air dalam kondisi NORMAL. Lanjutkan monitoring rutin.";
        }

        // Tambahkan info status panen
        if ($statusPanen === 'Siap Panen') {
            $rekomendasi[] = "🐟 Ikan di {$kolam->nama_kolam} sudah berumur {$umurIkan} hari (metode " . ucfirst($metode) . "). Status: SIAP PANEN!";
        } else {
            $rekomendasi[] = "📅 Ikan di {$kolam->nama_kolam} berumur {$umurIkan} hari. Estimasi panen dalam {$sisaHari} hari lagi (metode " . ucfirst($metode) . ").";
        }

        $rekomendasiText = implode("\n", $rekomendasi);

        // ===== 3. SIMPAN KE DATABASE =====
        MonitoringLog::create([
            'kolam_id' => $kolam->id,
            'metode_budidaya' => $metode,
            'suhu' => $validated['suhu'],
            'ph' => $validated['ph'],
            'amonia' => $validated['amonia'],
            'status_panen' => $statusPanen,
            'umur_ikan' => $umurIkan,
            'kondisi_air' => $kondisiAir,
            'rekomendasi' => $rekomendasiText,
        ]);

        // ===== 4. REDIRECT DENGAN FLASH MESSAGE =====
        return redirect()->route('dashboard')->with([
            'success' => 'Data monitoring berhasil disimpan & dianalisis!',
            'analisis' => [
                'kolam_nama' => $kolam->nama_kolam,
                'kondisi_air' => $kondisiAir,
                'status_panen' => $statusPanen,
                'umur_ikan' => $umurIkan,
                'sisa_hari' => $sisaHari,
                'rekomendasi' => $rekomendasi,
                'metode' => ucfirst($metode),
                'suhu' => $validated['suhu'],
                'ph' => $validated['ph'],
                'amonia' => $validated['amonia'],
            ],
        ]);
    }
}
