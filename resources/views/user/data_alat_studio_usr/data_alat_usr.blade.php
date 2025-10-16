@extends('partials.main')
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
@section('MainContent')
    <div class="page-heading">
        <h3>Alat Studio Musik Itera</h3>
    </div>

<<<<<<< Updated upstream
    <div class="row">
        @foreach ($data_alat as $alat)
            <div class="col-4">
                <div class="card">
                    <img src="{{ asset('storage/img_upload/data_alat/' . $alat->foto_alat) }}" class="card-img-top"
                        alt="..." height="200px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $alat->nama_alat }}</h5>
                        <p><span class="badge text-bg-info text-white"><i class="bi bi-cart"></i>
                                {{ $alat->jumlah_alat }}</span>
                            {{-- <span class="badge text-bg-success"><i class="bi bi-cash-stack"></i>
                                Rp{{ $alat->harga_sewa }}</span> --}}
=======
    {{-- KONTEN UTAMA TOMBOL DAN SEARCH BAR DIBUNGKUS DALAM ROW --}}
    <div class="row align-items-center mb-4">
        {{-- Tombol "Lihat Jadwal Ketersediaan" di Sisi Kiri --}}
        <div class="col-md-6 col-sm-12 d-flex gap-2">
            <a href="{{ route('jadwal.alat', ['tanggal'=>date('Y-m-d'),'mulai'=>'08:00','selesai'=>'17:00']) }}"
                class="btn btn-secondary text-white">
                <i class="bi bi-calendar-week"></i> Lihat Jadwal Ketersediaan
            </a>
        </div>

        {{-- Search Bar di Sisi Kanan (menggunakan col-md-6, w-75, dan justify-content-end) --}}
        <div class="col-md-6 col-sm-12 d-flex justify-content-end mt-2 mt-md-0">
            <input type="text" id="searchInput" class="form-control w-75"
                   placeholder="Cari Alat...">
        </div>
    </div>
    {{-- AKHIR KONTEN UTAMA --}}

    <div class="row" id="alatList">
        @foreach ($data_alat as $alat)
            {{-- Tambahkan kelas alat-item dan data-attribute untuk pencarian --}}
            <div class="col-lg-4 col-md-6 col-sm-12 alat-item"
                 data-nama="{{ Str::lower($alat->nama_alat) }}"
                 data-tipe="{{ Str::lower($alat->tipe_alat) }}">
                <div class="card">
                    {{-- PERBAIKAN PATH DI SINI --}}
                    <img src="{{ asset('storage/img_upload/data_alat/' . $alat->foto_alat) }}" class="card-img-top"
                        alt="Foto {{ $alat->nama_alat }}" height="200px">
                    {{-- AKHIR PERBAIKAN --}}
                    <div class="card-body">
                        <h5 class="card-title">{{ $alat->nama_alat }}</h5>
                        <p class="d-flex gap-2 flex-wrap">
                            <span class="badge text-bg-info text-white">
                                <i class="bi bi-cart"></i> {{ $alat->jumlah_alat }}
                            </span>

                            <span class="badge text-bg-success">
                                <i class="bi bi-tools"></i>
                                Rp{{ number_format($alat->biaya_perawatan ?? 0, 0, ',', '.') }}
                            </span>
>>>>>>> Stashed changes
                        </p>
                        <p class="card-text">Tipe Alat = {{ $alat->tipe_alat }}</p>
                        <a href="{{ url('/alat_dipinjam') }}" class="btn btn-primary"><i class="bi bi-cart-check"></i>
                            Pinjam</a>
<<<<<<< Updated upstream
                        <!-- <a href="{{ url('/user_review_alat/' . $alat->id_alat) }}"
                            class="btn btn-secondary text-white"><i class="bi bi-star"></i>
                            User Review</a> -->
=======
                        {{-- Contoh link User Review yang dikomentari di kode asli:
                        --}}
>>>>>>> Stashed changes
                    </div>
                </div>
            </div>
        @endforeach
    </div>
<<<<<<< Updated upstream
@endsection
=======

@push('script')
<script>
    // Pastikan jQuery sudah dimuat
    $(document).ready(function() {
        /**
         * Logika Pencarian (Filter Client-Side)
         * Akan mencari berdasarkan 'nama_alat' dan 'tipe_alat'
         */
        $('#searchInput').on('keyup', function() {
            // 1. Ambil teks dari input pencarian dan ubah ke huruf kecil untuk pencarian non-case-sensitive
            const searchTerm = $(this).val().toLowerCase();

            // 2. Iterasi melalui setiap kartu alat
            $('#alatList .alat-item').each(function() {
                const $item = $(this);
                // 3. Ambil data nama dan tipe alat dari atribut data-nama dan data-tipe
                const namaAlat = $item.data('nama');
                const tipeAlat = $item.data('tipe');

                // 4. Cek apakah teks pencarian ada di nama alat ATAU tipe alat
                if (namaAlat.includes(searchTerm) || tipeAlat.includes(searchTerm)) {
                    $item.show(); // Tampilkan jika cocok
                } else {
                    $item.hide(); // Sembunyikan jika tidak cocok
                }
            });
        });
    });
</script>
@endpush

@endsection
>>>>>>> Stashed changes
