<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DetailPesananAlatExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Menampilkan daftar data (misalnya tabel di view).
     */
    public function index()
    {
        // Logika untuk menampilkan tabel data di sini
        // Misalnya: $data = DetailPesananJadwalAlatModel::all();
        // return view('detail_pesanan.index', compact('data'));
        return view('detail_pesanan.index');
    }

    /**
     * Metode untuk memicu pengunduhan file export.
     */
    public function export()
    {
        // Tentukan nama file yang akan diunduh
        $filename = 'detail_pesanan_alat_' . now()->format('Ymd_His') . '.xlsx';

        // Panggil kelas export dan mulai proses download
        return Excel::download(new DetailPesananAlatExport, $filename);
    }
}
