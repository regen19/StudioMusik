<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PeminjamanApproved extends Notification
{
    use Queueable;

    public function __construct(public $pesanan) {}

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'      => 'Peminjaman Disetujui',
            'message'    => "Peminjaman #{$this->pesanan->id_pesanan_pinjam_alat} disetujui.",
            'pesanan_id' => $this->pesanan->id_pesanan_pinjam_alat,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}