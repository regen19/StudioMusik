@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Ruangan Studio Musik Itera</h3>
    </div>

    <div class="row">
        @foreach ($data_ruangan as $ruangan)
            <div class="col col-md-4">
                <div class="card">
                    <img src="{{ asset('storage/img_upload/data_ruangan/' . $ruangan->thumbnail) }}" class="card-img-top"
                        alt="..." height="200px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ruangan->nama_ruangan }}</h5>
                        <p><span class="badge text-bg-info text-white"><i class="bi bi-people"></i>
                                {{ $ruangan->kapasitas }}</span>
                            {{-- <span class="badge text-bg-success"><i class="bi bi-cash-stack"></i>
                                Rp{{ $ruangan->harga_sewa }}</span> --}}
                        </p>
                        <p class="card-text">Fasilitas = {{ $ruangan->fasilitas }}</p>
                        <a href="{{ url('/jadwal_studio_saya') }}" class="btn btn-primary"><i class="bi bi-cart-check"></i>
                            Pinjam</a>
                        <a href="{{ url('/user_review_ruangan/' . $ruangan->id_ruangan) }}"
                            class="btn btn-secondary text-white"><i class="bi bi-star"></i>
                            User Review</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
