<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BarangRestock extends Notification
{
    use Queueable;

    public function __construct(public $pesanan) {}

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $url = in_array($notifiable->user_role, ['admin','k3l','ukmbs'])
            ? route('alat.peminjam')
            : url('/alat_dipinjam');

        return [
            'title'      => 'Barang dikembalikan / restock',
            'message'    => "Pesanan #{$this->pesanan->id_pesanan_pinjam_alat} telah dikembalikan dan stok diperbarui.",
            'url'        => $url,
            'pesanan_id' => $this->pesanan->id_pesanan_pinjam_alat,
        ];
    }
}