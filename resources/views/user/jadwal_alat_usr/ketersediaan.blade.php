@extends('partials.main')

@section('MainContent')
  <div class="page-heading">
    <h3>Ketersediaan Alat</h3>
    <!-- <p class="text-muted mb-2">
      Periode: {{ $tglMulai }} s/d {{ $tglSelesai }}
    </p> -->
    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
      <i class="bi bi-arrow-left"></i> Kembali
    </button>
  </div>

  <div class="card mt-3">
    <div class="card-body table-responsive">
      <table class="table table-sm table-bordered">
        <thead>
          <tr>
            <th>Alat</th>
            <th>Stok</th>
            <th>Dipinjam</th>
            <th>Terpesan (Periode)</th>
            <th>Sisa</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($ketersediaan as $row)
              @php
                  // Normalisasi field agar tahan bentuk data lama/baru
                  $stok     = (int) ($row->stok ?? $row->jumlah_alat ?? 0);
                  $dipinjam = (int) ($row->dipinjam ?? 0);
                  $terpesan = (int) ($row->terpesan ?? $row->jumlah_dipesan ?? 0);

                  // Sisa yang DITAMPILKAN = stok - dipinjam (tidak boleh negatif)
                  $sisaNow  = max($stok - $dipinjam, 0);
              @endphp

              <tr @class(['table-warning' => $sisaNow === 0])>
                  <td>{{ $row->nama_alat }}</td>
                  <td>{{ $stok }}</td>
                  <td>{{ $dipinjam }}</td>
                  <td>{{ $terpesan }}</td>
                  <td>{{ $sisaNow }}</td>
              </tr>
          @empty
              <tr>
                  <td colspan="5" class="text-center text-muted">Tidak ada data.</td>
              </tr>
          @endforelse
          </tbody>
      </table>
    </div>
  </div>
@endsection