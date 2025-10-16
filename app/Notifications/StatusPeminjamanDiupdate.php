<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPeminjamanDiupdate extends Notification
{
    use Queueable;

    public function __construct(public $pesanan, public string $status) {}

    public function via($notifiable){ return ['database']; }

    public function toDatabase($notifiable){
        $label = $this->status === 'Y' ? 'Disetujui' : ($this->status === 'N' ? 'Ditolak' : 'Diproses');
        return [
            'title'   => "Status peminjaman: {$label}",
            'message' => "Pengajuan #{$this->pesanan->id_pesanan_pinjam_alat} {$label}.",
            'url'     => route('pinjam-alat.detail', $this->pesanan->id_pesanan_pinjam_alat),
            'pesanan_id' => $this->pesanan->id_pesanan_pinjam_alat,
        ];
    }
}