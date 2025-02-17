<!DOCTYPE html>
<html>

<head>
    <title>Pengajuan Jasa Musik Baru</title>
</head>

<body>
    <h1>Informasi Pengajuan</h1>
    <p><b>ID Pesanan</b> : {{ $pesanan->id_pesanan_jasa_musik }}</p>
    <p><b>Nama Pemesan</b> : {{ $pesanan->username }}</p>
    <p><b>Jasa Musik</b> : {{ $pesanan->nama_jenis_jasa }}</p>
    <p><b>Tenggat Produksi</b> : {{ $pesanan->tenggat_produksi }} </p>
</body>

</html>
