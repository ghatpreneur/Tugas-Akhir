<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedingLog; // <-- JANGAN SAMPAI KELUPAAN YANG INI, GAT!
use Illuminate\Http\Request;

class FeedingLogController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi data yang dikirim oleh Python
        $validated = $request->validate([
            'status' => 'required|string|in:KOSONG,BERHASIL ISI',
            'stock_percent' => 'required|integer|between:0,100',
            'brightness' => 'required|integer',
        ]);

        // 2. Simpan data baru ke database MySQL
        $log = FeedingLog::create($validated);

        // 3. Kirim respon balik berbentuk JSON ke Python
        return response()->json([
            'message' => 'Data log pakan berhasil disimpan, Cik!',
            'data' => $log
        ], 201);
    }
}