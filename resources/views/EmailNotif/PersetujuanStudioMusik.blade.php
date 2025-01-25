<!DOCTYPE html>
<html>

<head>
    <title>Persetujuan Pesanan Jasa Musik</title>
</head>

<body>
    <h1>Pengajuan peminjaman studio musik telah <b>
            @if ($pesanan->status_persetujuan == 'N')
                DITOLAK
            @elseif($pesanan->status_persetujuan == 'Y')
                DISETUJUI
            @endif
        </b>, silahkan cek website ukmbs
        itera.
    </h1>
</body>

</html>
