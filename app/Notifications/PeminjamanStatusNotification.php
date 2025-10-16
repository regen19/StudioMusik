<?php

namespace App\Notifications;

use App\Models\PesananPinjamAlat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PeminjamanStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public PesananPinjamAlat $pinjam) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title'   => 'Status Peminjaman Alat',
            'message' => "Peminjaman #{$this->pinjam->id_pesanan_pinjam_alat} " .
                         ($this->pinjam->status_persetujuan === 'Y' ? 'disetujui' : 'ditolak'),
            'status'  => $this->pinjam->status_persetujuan,
            'id'      => $this->pinjam->id_pesanan_pinjam_alat,
        ];
    }
}