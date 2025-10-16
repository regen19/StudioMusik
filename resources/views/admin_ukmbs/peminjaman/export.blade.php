<table>
    <thead>
    <tr>
        <th>Nama Peminjam</th>
        <th>Nama Alat</th>
        <th>Tanggal Pinjam</th>
        <th>Tanggal Kembali</th>
        <th>Waktu Mulai</th>
        <th>Waktu Selesai</th>
        <th>Keterangan Keperluan</th>
        <th>Keterangan Admin</th>
        <th>Status Peminjaman</th>
        <th>Status pengembalian</th>
    </tr>
    </thead>
    <tbody>
    @foreach($peminjaman as $peminjam)
        <tr>
            <td>{{ $peminjam->user->username ?? '-' }}</td>
            <td>
                @forelse ($peminjam->details as $d)
                    <div>{{ $d->alat->nama_alat ?? '-' }} ({{ $d->jumlah ?? $d->qty ?? 1 }})</div>
                         @empty
                         <em>-</em>
                 @endforelse
            </td>          
            <td>{{ $peminjam->tgl_pinjam }}</td>
            <td>{{ $peminjam->tgl_kembali }}</td>
            <td>{{ $peminjam->waktu_mulai }}</td>
            <td>{{ $peminjam->waktu_selesai }}</td>
            <td>{{ $peminjam->ket_keperluan }}</td>
            <td>{{ $peminjam->ket_admin }}</td>
            <td>
                    @if ($peminjam->status_persetujuan == 'Y')
                        Disetujui
                    @elseif ($peminjam->status_persetujuan == 'N')
                        Ditolak
                    @else
                        Menunggu
                    @endif
            </td>
            <td>
                    @if ($peminjam->status_pengembalian == 'Y')
                        Sudah Dikembalikan
                    @elseif ($peminjam->status_pengembalian == 'N')
                        Belum Dikembalikan
                    @else
                        Menunggu
                    @endif
            </td>

        </tr>
    @endforeach
    </tbody>
</table>