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
                <div class="card-body">
                    <div>
                        <img src="{{ asset('/storage/img_upload/tutorial_alat/' . $data[0]->gambar_alat) }}"
                            class="card-img-top" alt="..." style="max-height:600px; max-widht:500px">
                    </div>
                    <h2 class="text-center text-uppercase mt-3"><u>{{ $data[0]->nama_alat }}</u></h2>
                    <p>{!! html_entity_decode($data[0]->deskripsi) !!}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
