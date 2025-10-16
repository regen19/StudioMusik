<?php

namespace App\Http\Controllers\AdminK3l;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\PesananPinjamAlat;
use App\Models\DetailPesananPinjamAlat;
use App\Models\Admin\DataAlatModel as DataAlat;
use App\Models\User;
use App\Support\Notify;
use App\Notifications\StatusPeminjamanDiupdate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestPeminjamanBaru;
use App\Notifications\PeminjamanApproved;
use App\Notifications\PeminjamanRejected;
use App\Notifications\PeminjamanStatusNotification;
use Illuminate\Support\Facades\Http;

class ApprovalController extends Controller
{
    /**
     * INDEX: daftar peminjaman (Pending/Approved/Rejected)
     * Mendukung filter ?status=P|Y|N
     * View default: k3l.peminjaman.index (sesuai target)
     */
   public function index(Request $r)
    {
        $items = PesananPinjamAlat::with([
                'user:id_user,username',
                'details.alat:id_alat,nama_alat'
            ])
            ->when($r->filled('status'), fn ($q) => 
                $q->where('status_persetujuan', $r->input('status'))
            )
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('k3l.peminjaman.index', compact('items'));
    }

    public function alat(Request $r)
    {
        $q = PesananPinjamAlat::with(['user', 'details.alat'])
            ->orderByRaw("FIELD(status_persetujuan, 'P','Y','N')")
            ->orderByDesc('id_pesanan_pinjam_alat');

        if ($r->filled('status')) {
            $q->where('status_persetujuan', $r->input('status'));
        }

        $items = $q->paginate(10);

        $ids = $items->pluck('id_pesanan_pinjam_alat')->all();
        $detail = DetailPesananPinjamAlat::with('alat')
            ->whereIn('id_pesanan_pinjam_alat', $ids)
            ->get()
            ->groupBy('id_pesanan_pinjam_alat');

        return view('admin_k3l.approval.alat', compact('items', 'detail'));
    }

    public function jadwal(Request $r)
    {
        $q = PesananPinjamAlat::with(['user:id_user,username', 'details.alat:id_alat,nama_alat'])
            ->where('status_persetujuan','Y')
            ->orderByDesc('id_pesanan_pinjam_alat');

        if ($r->filled('status')) {
            $q->where('status_persetujuan', $r->input('status'));
        }

        $list = $q->paginate(10);

        $ids = $list->pluck('id_pesanan_pinjam_alat')->all();
        $detail = DetailPesananPinjamAlat::with('alat')
            ->whereIn('id_pesanan_pinjam_alat', $ids)
            ->get()
            ->groupBy('id_pesanan_pinjam_alat');

        return view('admin_k3l.approval.jadwal', compact('list','detail'));
    }

    /**
     * APPROVE: hanya bisa dari status P (Pending)
     * - Cek & kurangi stok per alat dengan lock
     * - Set status_persetujuan = Y
     * - Optional: update kolom detail.status_persetujuan jika ada
     * - Notifikasi user (kalau class ada)
     */
    public function approve($id)
    {
        $pinjamId = null;

        DB::transaction(function () use ($id, &$pinjamId) {
            /** @var \App\Models\PesananPinjamAlat $pinjam */
            $pinjam = PesananPinjamAlat::with('details')
                ->lockForUpdate()
                ->findOrFail($id);
    
            if ($pinjam->status_persetujuan !== 'P') {
                abort(400, 'Permintaan sudah diproses.');
            }
    
            $pinjam->status_persetujuan = 'Y';
            $pinjam->status_pengembalian = $pinjam->status_pengembalian ?: 'N';
            $pinjam->save();
    
            // =======================================================
            // TAMBAHAN LOGIC KRUSIAL: KURANGI STOK ALAT
            // =======================================================
            
            $pinjam->details->each(function ($detail) {
                // Asumsi DataAlat adalah Model untuk tabel data_alat
                // Ambil Model DataAlat
                $alat = DataAlat::lockForUpdate()->find($detail->id_alat);
                
                // Kurangi jumlah_alat berdasarkan jumlah yang dipinjam
                if ($alat && $alat->jumlah_alat >= $detail->jumlah) {
                    $alat->decrement('jumlah_alat', $detail->jumlah);
                } 
                // Optional: Tambahkan else{} untuk handling jika stok ternyata tidak cukup saat proses approve.
            });
    
            // =======================================================
            // END TAMBAHAN LOGIC
            // =======================================================
    
            DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $pinjam->id_pesanan_pinjam_alat)
                ->update([
                    'status_persetujuan' => 'Y',
                    'status_peminjaman'  => 'Y',
                ]);
    
            $pinjamId = $pinjam->id_pesanan_pinjam_alat;
        });

        $pesanan = PesananPinjamAlat::with('user')->findOrFail($pinjamId);

        // --- 1. Kirim Notifikasi ke User ---
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $pesanan->user->no_wa,
            'message' => "Request anda sudah di approve, ambil sesuai jadwal",
            'countryCode' => '62', // Kode negara Indonesia
        ]);

        // --- 2. Kirim Notifikasi ke UKMBS ---
        $ukmbs_users = \App\Models\User::where('user_role', 'ukmbs')
                                        ->whereNotNull('no_wa')
                                        ->get();

        foreach ($ukmbs_users as $user_ukmbs) {
            try {
             \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target'      => $user_ukmbs->no_wa, // Nomor WA user UKMBS saat ini
            'message'     => "Ada request pemeinjaman baru yang sudah di approve",
            'countryCode' => '62',
            ]);
        } catch (\Throwable $e) {
            \Log::error("Gagal mengirim WA ke UKMBS ({$user_ukmbs->username}): " . $e->getMessage());
        }
        }

        $targets = User::whereIn('user_role', ['admin','k3l','ukmbs'])->get();

        Notification::send($targets, new PeminjamanApproved($pesanan));
        $pesanan->user?->notify(new PeminjamanApproved($pesanan));

        return back()->with('success', 'Peminjaman disetujui.');
    }

    /**
     * REJECT: hanya dari status P (Pending)
     * - Set status_persetujuan = N (+ ket_admin jika ada)
     * - Optional: update detail.status_persetujuan jika ada
     * - Notifikasi user
     */
    public function reject($id, Request $request)
    {
        $data = $request->validate([
            'ket_admin' => ['required','string','max:1000'],
        ]);

        $pinjamId = null;

        DB::transaction(function () use ($id, $data, &$pinjamId) {
            $pinjam = PesananPinjamAlat::lockForUpdate()->findOrFail($id);
            if ($pinjam->status_persetujuan !== 'P') abort(400, 'Permintaan sudah diproses.');

            $pinjam->status_persetujuan = 'N';
            $pinjam->ket_admin = $data['ket_admin'];
            $pinjam->save();

            DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $pinjam->id_pesanan_pinjam_alat)
                ->update(['status_persetujuan' => 'N']);

            $pinjamId = $pinjam->id_pesanan_pinjam_alat;
        });

        $pesanan = PesananPinjamAlat::with('user')->findOrFail($pinjamId);
        // notif wa
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $pesanan->user->no_wa,
            'message' => "Request anda ditolak \n\n Alasan : {$pesanan->ket_admin}",
            'countryCode' => '62', // Kode negara Indonesia
        ]);
        $targets = User::whereIn('user_role', ['admin','k3l','ukmbs'])->get();

        Notification::send($targets, new PeminjamanRejected($pesanan, $data['ket_admin']));
        $pesanan->user?->notify(new PeminjamanRejected($pesanan, $data['ket_admin']));

        return back()->with('success', 'Peminjaman ditolak.');
    }
}