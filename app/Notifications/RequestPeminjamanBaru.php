<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestPeminjamanBaru extends Notification
{
    use Queueable;

    public function __construct(public $pesanan) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    private function linkFor($notifiable): string
    {
        return match ($notifiable->user_role) {
            'k3l'     => route('k3l.peminjaman.index'),
            'ukmbs'   => route('ukmbs.peminjaman.index'),
            'admin'   => route('alat.peminjam'),
            default   => route('notifications.index'),
        };
    }

    public function toDatabase($notifiable): array
    {
        $no = $this->pesanan->id_pesanan_pinjam_alat;
        $pemohon = optional($this->pesanan->user)->username ?? 'Pengguna';

        return [
            'title'      => 'Request Peminjaman Baru',
            'message'    => "$pemohon mengajukan peminjaman (#$no) dan menunggu persetujuan.",
            'url'        => $this->linkFor($notifiable),
            'pesanan_id' => $no,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Pengajuan peminjaman baru',
            'body'  => (optional($this->pesanan->user)->username ?? 'Pengguna').' mengajukan peminjaman.',
            'url'   => $this->linkFor($notifiable),
        ];
    }
}