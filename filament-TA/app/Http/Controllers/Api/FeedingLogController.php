<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedingLog; // <-- JANGAN SAMPAI KELUPAAN YANG INI, GAT!
use Illuminate\Http\Request;

class FeedingLogController extends Controller
{
    public function store(Request $request)
    {
       // 1. VALIDASI DISUNAT: Cuma nagih 'status' doang sesuai file deploy.py lu!
        $validated = $request->validate([
            'status' => 'required|string|in:KOSONG,BERHASIL ISI',
        ]);

        // 2. Simpan data baru ke database MySQL
        $log = FeedingLog::create($validated);

        // 3. SINKRONISASI: Kirim balik format JSON yang bener-bener dicari ama Python lu
        return response()->json([
            'alert'   => 'BERHASIL ISI',
            'tanggal' => now()->format('d-m-Y'),
            'waktu'   => now()->format('H:i:s'),
            'data_db' => $log // Bonus info kalau lu mau intip data ter-save
        ], 201);
    }
}