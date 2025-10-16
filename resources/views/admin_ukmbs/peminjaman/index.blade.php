@extends('partials.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

@section('MainContent')
<div class="page-heading">
  <h3>Data Peminjaman Alat (UKMBS)</h3>
  <p>Halo, {{ auth()->user()->username }}! Berikut adalah daftar peminjaman alat yang sudah disetujui.</p>
 
</div>

<div class="card">
  <div class="card-header">Daftar Peminjaman</div>
  
  <form action="{{ url('ukmbs/peminjaman/export') }}" method="GET" class="d-flex gap-2 mb-3">
    <select name="bulan" class="form-select w-auto">
        <option value="">-- Pilih Bulan --</option>
        @foreach ([
            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
            7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
        ] as $key => $nama)
            <option value="{{ $key }}">{{ $nama }}</option>
        @endforeach
    </select>

    <select name="tahun" class="form-select w-auto">
        <option value="">-- Pilih Tahun --</option>
        @for ($i = date('Y'); $i >= 2020; $i--)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>

    <button type="submit" class="btn btn-success">
        <i class="fas fa-file-excel me-2"></i> Export Excel
    </button>
</form>




  <div class="card-body table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Peminjam</th>
          <th>Tgl Pinjam</th>
          <th>Tgl Kembali</th>
          <th>Waktu</th>
          <th>Alat (jumlah)</th>
          <th>Status Pengembalian</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($items as $row)
        <tr>
          <td>{{ $loop->iteration + ($items->currentPage()-1)*$items->perPage() }}</td>
          <td>{{ $row->user->username ?? '-' }}</td>
          <td>{{ $row->tgl_pinjam }}</td>
          <td>{{ $row->tgl_kembali }}</td>
          <td>{{ $row->waktu_mulai }}–{{ $row->waktu_selesai }}</td>
          <td>
            @foreach($row->details as $d)
              <div>{{ $d->alat->nama_alat ?? 'Alat' }} ({{ $d->jumlah }})</div>
            @endforeach
          </td>
          <td>
            @if($row->status_pengembalian === 'Y')
              <span class="badge bg-success">Sudah kembali</span>
            @else
              <span class="badge bg-warning text-dark">Belum kembali</span>
            @endif
          </td>
          <td class="text-end">
            {{-- DETAIL (modal, dengan upload kondisi untuk UKMBS) --}}
            <button type="button"
                    class="btn btn-sm btn-primary me-1"
                    onclick="openDetailUkmbs({{ $row->id_pesanan_pinjam_alat }})">
              Detail
            </button>

            @if($row->status_pengembalian === 'N')
              <form class="d-inline" method="POST"
                    action="{{ route('ukmbs.pengembalian', $row->id_pesanan_pinjam_alat) }}"
                    onsubmit="return confirm('Proses pengembalian & kembalikan stok?');">
                @csrf
                <button class="btn btn-sm btn-success">Proses Pengembalian</button>
              </form>
            @else
              <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center text-muted">Tidak ada data</td></tr>
      @endforelse
      </tbody>
    </table>

    <div class="mt-3">
                    {{ $items->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
  </div>
</div>

{{-- ===== Modal Detail UKMBS (upload kondisi awal/akhir, tanpa review) ===== --}}
@include('admin_ukmbs.peminjaman.detail_ukmbs')
@endsection