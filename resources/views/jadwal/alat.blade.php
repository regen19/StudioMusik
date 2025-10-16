@extends('partials.main')

@section('MainContent')
  <div class="page-heading">
    <h3>Jadwal Ketersediaan Alat</h3>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('jadwal.alat') }}">
        <div class="col-md-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Mulai</label>
          <input type="time" name="mulai" class="form-control" value="{{ $mulai }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Selesai</label>
          <input type="time" name="selesai" class="form-control" value="{{ $selesai }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button class="btn btn-primary w-100">Filter</button>
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><strong>Tersedia (sisa &gt; 0)</strong></div>
        <div class="card-body table-responsive">
          <table class="table table-sm table-bordered">
            <thead>
              <tr><th>Alat</th><th>Stok</th><th>Terpakai</th><th>Sisa</th></tr>
            </thead>
            <tbody>
            @foreach ($alat->where('sisa','>',0) as $row)
              <tr>
                <td>{{ $row->nama_alat }}</td>
                <td>{{ $row->jumlah_alat }}</td>
                <td>{{ $row->total_dipinjam }}</td>
                <td>{{ $row->sisa }}</td>
              </tr>
            @endforeach
            @if($alat->where('sisa','>',0)->isEmpty())
              <tr><td colspan="4" class="text-center text-muted">Tidak ada alat tersedia pada rentang waktu ini.</td></tr>
            @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header"><strong>Dipinjam / Akan dipinjam (sisa ≤ 0)</strong></div>
        <div class="card-body table-responsive">
          <table class="table table-sm table-bordered">
            <thead>
              <tr><th>Alat</th><th>Stok</th><th>Terpakai</th><th>Sisa</th></tr>
            </thead>
            <tbody>
            @foreach ($alat->where('sisa','<=',0) as $row)
              <tr class="table-warning">
                <td>{{ $row->nama_alat }}</td>
                <td>{{ $row->jumlah_alat }}</td>
                <td>{{ $row->total_dipinjam }}</td>
                <td>{{ $row->sisa }}</td>
              </tr>
            @endforeach
            @if($alat->where('sisa','<=',0)->isEmpty())
              <tr><td colspan="4" class="text-center text-muted">Belum ada yang terjadwal pada rentang waktu ini.</td></tr>
            @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection