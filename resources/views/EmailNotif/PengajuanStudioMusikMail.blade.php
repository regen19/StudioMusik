<!DOCTYPE html>
<html>

<head>
    <title>Pengajuan Peminjaman Studio Musik Baru</title>
</head>

<body>
    <h1>Pengajuan Baru</h1>
    <p><b>ID Pesanan</b> : {{ $pesanan->id_pesanan_jadwal_studio }}</p>
    <p><b>Nama Pelanggan</b> : {{ $pesanan->username }}</p>
    <p><b>Ruangan</b> : {{ $pesanan->nama_ruangan }}</p>
    <p><b>Tanggal Pinjam</b> : {{ $pesanan->tgl_pinjam }} </p>
</body>

</html>
