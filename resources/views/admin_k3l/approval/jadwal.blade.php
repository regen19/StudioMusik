@extends('partials.main')

@section('MainContent')
<div class="page-heading">
    <h3>Jadwal Peminjaman Alat</h3>
    <p>Daftar jadwal alat yang <strong>sedang/akan dipinjam</strong> (status: disetujui).</p>
</div>

<div class="page-content">
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Jadwal Disetujui</h5>
                <span class="badge bg-success">Approved</span>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Waktu</th>
                        <th>Alat (jumlah)</th>
                        <th>Status Pengembalian</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($list as $row)
                        <tr>
                            <td>{{ $loop->iteration + ($list->currentPage()-1)*$list->perPage() }}</td>
                            <td>{{ optional($row->user)->username ?? '-' }}</td>
                            <td>{{ $row->tgl_pinjam }}</td>
                            <td>{{ $row->tgl_kembali }}</td>
                            <td>{{ $row->waktu_mulai }}–{{ $row->waktu_selesai }}</td>
                            {{-- daftar alat dari relasi details.alat --}}
                                    <td>
                                        @forelse ($row->details as $d)
                                            <div>{{ $d->alat->nama_alat ?? '-' }} ({{ $d->jumlah ?? $d->qty ?? 1 }})</div>
                                        @empty
                                            <em>-</em>
                                        @endforelse
                                    </td>
                            <td>
                                @if($row->status_pengembalian === 'Y')
                                    <span class="badge bg-success">Sudah kembali</span>
                                @else
                                    <span class="badge bg-warning text-dark">Belum kembali</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Tidak ada jadwal</td></tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $list->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection