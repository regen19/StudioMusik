<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $pesanan;
    public $subject;
    public $view;

    public function __construct($pesanan, $subject, $view)
    {
        $this->pesanan = $pesanan;
        $this->subject = $subject;
        $this->view = $view;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view($this->view)
            ->view('EmailNotif.PersetujuanJasaMusik')
            
            ->view('EmailNotif.PersetujuanStudioMusik');
    }
}
