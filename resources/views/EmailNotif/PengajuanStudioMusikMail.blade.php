<!DOCTYPE html>
<html>

<head>
    <title>Pengajuan Peminjaman Studio Musik Baru</title>
</head>

<body>
    <h1>Informasi Pengajuan</h1>
    <p><b>ID Pesanan</b> : {{ $pesanan->id_pesanan_jadwal_studio }}</p>
    <p><b>Nama Peminjam</b> : {{ $pesanan->username }}</p>
    <p><b>Tanggal Pinjam</b> : {{ $pesanan->tgl_pinjam }} </p>
    <p><b>Waktu Pinjam</b> : {{ $pesanan->waktu_mulai }} - {{ $pesanan->waktu_selesai }}</p>
    <p><b>Keperluan</b> : {{ $pesanan->ket_keperluan }} </p>
</body>

</html>
