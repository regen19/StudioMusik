<!DOCTYPE html>
<html>

<head>
    <title>Persetujuan Peminjaman Studio Musik</title>
</head>

<body>
    <h1>Pengajuan peminjaman studio musik telah <b>
            @if ($pesanan->status_persetujuan == 'N')
                DITOLAK
            @elseif($pesanan->status_persetujuan == 'Y')
                DISETUJUI
            @endif
        </b>, silahkan cek website Studio Musik ITERA
    </h1>
</body>

</html>
