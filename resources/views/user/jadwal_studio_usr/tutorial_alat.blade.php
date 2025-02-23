@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Tutorial Penggunaan Alat</h3>
    </div>

    <div class="row">
        @foreach ($data as $ruangan)
            <div class="col col-md-4">
                <div class="card">
                    <img src="{{ asset('storage/img_upload/tutorial_alat/' . $ruangan->gambar_alat) }}" class="card-img-top"
                        alt="..." height="200px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ruangan->nama_alat }}</h5>

                        <p class="card-text">{!! html_entity_decode($ruangan->deskripsi) !!}......</p>
                        <a href="{{ url('/detail_penggunaan_alat/' . $ruangan->id_tutorial) }}" class="btn btn-primary"><i
                                class="bi bi-eye"></i>
                            Pelajari...</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
