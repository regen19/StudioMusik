<?php

namespace App\Exports;

use App\Models\PesananPinjamAlat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataPeminjamExport implements FromView
{
    public $bulan;
    public $tahun;

    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? date('Y'); // Jika $tahun NULL, gunakan tahun saat ini
    }

    public function view(): View
    {
        $query = PesananPinjamAlat::with(['user', 'details.alat']);

        if ($this->bulan && $this->tahun) {
            $query->whereMonth('tgl_pinjam', $this->bulan)
                  ->whereYear('tgl_pinjam', $this->tahun);
        }

        $peminjaman = $query->get();

        return view('admin_ukmbs.peminjaman.export', [
            'peminjaman' => $peminjaman
        ]);
    }
}
