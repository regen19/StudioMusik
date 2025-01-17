@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>TUTORIAL PENGGUNAAN ALAT</h3>
    </div>

    <a href="{{ url('/tutorial_penggunaan_alat') }}"><button class="btn btn-warning icon icon-left text-white mb-4"><i
                class="bi bi-left-arrow"></i>
            Kembali</button>
    </a>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <img src="{{ asset('/storage/img_upload/tutorial_alat/' . $data[0]->gambar_alat) }}" class="card-img-top"
                    alt="..." height="200px">
                <div class="card-body">

                    <h2 class="text-center text-uppercase"><u>{{ $data[0]->nama_alat }}</u></h2>
                    <p>{{ $data[0]->deskripsi }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
