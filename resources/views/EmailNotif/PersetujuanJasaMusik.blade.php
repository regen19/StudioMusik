<!DOCTYPE html>
<html>

<head>
    <title>Persetujuan Jasa Musik</title>
</head>

<body>
    <h1>Pengajuan pembuatan jasa musik telah <b>
            @if ($pesanan->status_persetujuan == 'N')
                DITOLAK
            @elseif($pesanan->status_persetujuan == 'Y')
                DISETUJUI
            @endif
        </b>, status produksi sedang dalam
        <b>
            @if ($pesanan->status_produksi == 'N')
                PENGAJUAN
            @elseif($pesanan->status_produksi == 'P')
                PROSES
            @elseif($pesanan->status_produksi == 'Y')
                SELESAI
            @endif
        </b>,
        silahkan cek website Studio Musik ITERA.
    </h1>
</body>

</html>
