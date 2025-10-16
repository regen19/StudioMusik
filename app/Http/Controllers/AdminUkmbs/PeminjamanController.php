<?php

namespace App\Http\Controllers\AdminUkmbs;

use App\Exports\DataPeminjamExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\PesananPinjamAlat;
use App\Models\DetailPesananPinjamAlat;
use App\Models\User;
use App\Support\Notify;
use App\Notifications\BarangRestock;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestPeminjamanBaru;
use App\Notifications\PeminjamanApproved;
use App\Notifications\PeminjamanRejected;
use App\Notifications\PeminjamanStatusNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Admin\DataAlatModel as DataAlat;
class PeminjamanController extends Controller
{
    public function index()
    {
        $items = PesananPinjamAlat::with(['user', 'details.alat'])
            ->where('status_persetujuan', 'Y')
            ->orderByDesc('id_pesanan_pinjam_alat')
            ->paginate(20);

        return view('admin_ukmbs.peminjaman.index', compact('items'));
    }

    public function pengembalian($id, Request $request)
    {
        $pesanan = DB::transaction(function () use ($id) {
            /** @var \App\Models\PesananPinjamAlat $pinjam */
            $pinjam = PesananPinjamAlat::with(['details','user'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($pinjam->status_persetujuan !== 'Y') {
                // Asumsi: Hanya pesanan yang sudah disetujui ('Y') yang bisa dikembalikan
                abort(400, 'Belum disetujui.'); 
            }
            if ($pinjam->status_pengembalian === 'Y') {
                return $pinjam;
            }

            $pinjam->status_pengembalian = 'Y';
            $pinjam->save();
            
            // =======================================================
            // LOGIC KRUSIAL: TAMBAH STOK KEMBALI
            // =======================================================
            
            // Loop melalui detail pesanan untuk mengembalikan stok setiap alat
            $pinjam->details->each(function ($detail) {
                // Menggunakan alias DataAlat yang di-use di atas
                DataAlat::where('id_alat', $detail->id_alat)
                    ->increment('jumlah_alat', $detail->jumlah);
            });
            // =======================================================
            
            // Optional: Update status_peminjaman di tabel detail
            if (\Illuminate\Support\Facades\Schema::hasColumn('detail_pesanan_pinjam_alat', 'status_peminjaman')) {
                DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $pinjam->id_pesanan_pinjam_alat)
                    ->update(['status_peminjaman' => 'N']);
            }

            return $pinjam;
        });

        // NOTIFIKASI ADMIN: BarangRestock
        $roles 	 = ['admin','k3l','ukmbs'];
        $targets = User::whereIn('user_role', $roles)
            ->where('id_user', '!=', auth()->user()->id_user)
            ->get();

        if ($targets->isNotEmpty()) {
            Notification::send($targets, new BarangRestock($pesanan));
        }

        // NOTIFIKASI USER: PeminjamanStatusNotification (dikembalikan)
        $pesanan->user?->notify(new PeminjamanStatusNotification($pesanan, 'dikembalikan'));

        return back()->with('success','Pengembalian dicatat & stok diperbarui');
    }

    public function approve($id, Request $r)
    {
        $pesanan = DB::transaction(function () use ($id, $r) {
            /** @var \App\Models\PesananPinjamAlat $p */
            $p = PesananPinjamAlat::with(['user','details'])
                ->lockForUpdate()
                ->findOrFail($id);

            if ($p->status_persetujuan !== 'Y') {
                $p->status_persetujuan  = 'Y';
            }

            $p->status_pengembalian = $p->status_pengembalian ?: 'N';
            $p->ket_admin           = $r->input('ket_admin', '');
            $p->save();

            if (\Illuminate\Support\Facades\Schema::hasColumn('detail_pesanan_pinjam_alat', 'status_persetujuan')) {
                DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $p->id_pesanan_pinjam_alat)
                    ->update(['status_persetujuan' => 'Y']);
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('detail_pesanan_pinjam_alat', 'status_peminjaman')) {
                DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $p->id_pesanan_pinjam_alat)
                    ->update(['status_peminjaman' => 'Y']);
            }

            return $p;
        });

        if ($pesanan->user) {
            $pesanan->user->notify(new PeminjamanApproved($pesanan));
        }

        return back()->with('ok','Pengajuan disetujui.');
    }

    public function reject($id, Request $r)
    {
        $p = PesananPinjamAlat::with('user')->findOrFail($id);
        $alasan = $r->input('alasan', '');

        $p->status_persetujuan = 'N';
        $p->ket_admin = $alasan;
        $p->save();

        if (\Illuminate\Support\Facades\Schema::hasColumn('detail_pesanan_pinjam_alat', 'status_peminjaman')) {
            DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $p->id_pesanan_pinjam_alat)
                ->update(['status_peminjaman' => 'N']);
        }

        if ($p->user) {
            $p->user->notify(new PeminjamanRejected($p, $alasan));
        }

        return back()->with('ok','Pengajuan ditolak.');
    }

    public function export(Request $request) 
    {
        $bulan = $request->input('bulan'); 
        $tahun = $request->input('tahun'); 
    
        // Daftar nama bulan
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    
        // Tentukan nama bulan & tahun di file
        $bulanText = $namaBulan[$bulan] ?? 'Semua_Bulan';
        $tahunText = $tahun ?? date('Y'); // default tahun sekarang
    
        // Bentuk nama file
        $filename = "Data_Peminjam_{$bulanText}_{$tahunText}.xlsx";
    
        // Jalankan export
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DataPeminjamExport($bulan, $tahun),
            $filename
        );
    }
    
}