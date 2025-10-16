@extends('partials.main')

@section('MainContent')
<div class="page-heading">
    <h3>Persetujuan Peminjaman Alat </h3>
    <p>Halo, <strong>{{ auth()->user()->username }}</strong>. Berikut daftar pengajuan peminjaman alat.</p>
</div>

<div class="page-content">
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pengajuan</h5>
                <span class="badge bg-info">
                    Mode: @can('approve-peminjaman') Approver @else View-only @endcan
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Pemohon</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Waktu</th>
                                <th>Alat (jumlah)</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $row)
                                <tr>
                                    {{-- nomor urut yang benar saat paginate --}}
                                    <td>{{ $items->firstItem() + $loop->index }}</td>

                                    {{-- nama pemohon --}}
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
                                        @if ($row->status_persetujuan === 'Y')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($row->status_persetujuan === 'N')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        @if ($row->status_persetujuan === 'P')
                                            @can('approve-peminjaman')
                                                {{-- SETUJUI --}}
                                                <form class="d-inline js-approve-form"
                                                        method="POST"
                                                        action="{{ route('k3l.peminjaman.approve', $row->getKey()) }}">
                                                    @csrf
                                                    <button type="button" class="btn btn-sm btn-primary js-btn-approve">Setujui</button>
                                                </form>

                                                {{-- TOLAK --}}
                                                <form class="d-inline js-reject-form"
                                                        method="POST"
                                                        action="{{ route('k3l.peminjaman.reject', $row->getKey()) }}">
                                                    @csrf
                                                    <input type="hidden" name="ket_admin" value="">
                                                    <button type="button" class="btn btn-sm btn-outline-danger js-btn-reject">Tolak</button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>Setujui</button>
                                                <button class="btn btn-sm btn-secondary" disabled>Tolak</button>
                                            @endcan
                                        @else
                                            <em class="text-muted">Tidak ada aksi</em>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination di luar table + gunakan Bootstrap 5 --}}
                <div class="mt-3">
                    {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</section>
</div>

@push('script')
    <script>
    $(document).on('click', '.js-btn-approve', async function () {
        const form = $(this).closest('form')[0];
        const { isConfirmed } = await Swal.fire({
        title: 'Setujui pengajuan ini?',
        text: 'Pastikan jadwal & ketersediaan alat sudah dicek.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, setujui',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        allowOutsideClick: false,
        backdrop: true
        });
        if (isConfirmed) form.submit();
    });

    $(document).on('click', '.js-btn-reject', async function () {
        const $form = $(this).closest('form');

        const { isConfirmed, value } = await Swal.fire({
        title: 'Tolak pengajuan ini?',
        input: 'textarea',
        inputLabel: 'Keterangan Admin (alasan penolakan)',
        inputPlaceholder: 'Tulis alasan penolakan…',
        inputAttributes: { 'aria-label': 'Alasan penolakan' },
        inputValidator: v => (!v || !v.trim()) ? 'Keterangan wajib diisi' : undefined,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        allowOutsideClick: false,
        backdrop: true,
        didOpen: () => {
            const ta = Swal.getPopup().querySelector('textarea');
            if (ta) ta.focus();
        }
        });

        if (!isConfirmed) return;

        $form.find('input[name="ket_admin"]').val(String(value).trim());
        $form[0].submit();
    });
    </script>
@endpush
@endsection