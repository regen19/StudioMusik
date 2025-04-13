@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Alat Studio Musik Itera</h3>
    </div>

    <div class="row">
        @foreach ($data_alat as $alat)
            <div class="col-4">
                <div class="card">
                    <img src="{{ asset('storage/img_upload/data_alat/' . $alat->foto_alat) }}" class="card-img-top"
                        alt="..." height="200px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $alat->nama_alat }}</h5>
                        <p><span class="badge text-bg-info text-white"><i class="bi bi-people"></i>
                                {{ $alat->kapasitas }}</span>
                            {{-- <span class="badge text-bg-success"><i class="bi bi-cash-stack"></i>
                                Rp{{ $alat->harga_sewa }}</span> --}}
                        </p>
                        <p class="card-text">Fasilitas = {{ $alat->fasilitas }}</p>
                        <a href="{{ url('/jadwal_studio_saya') }}" class="btn btn-primary"><i class="bi bi-cart-check"></i>
                            Pinjam</a>
                        <a href="{{ url('/user_review_alat/' . $alat->id_alat) }}"
                            class="btn btn-secondary text-white"><i class="bi bi-star"></i>
                            User Review</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
