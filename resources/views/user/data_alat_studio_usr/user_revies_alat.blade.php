@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Review User Studio Musik</h3>
    </div>

    <a href="{{ url('/data_alat_studio') }}"><button class="btn btn-warning icon icon-left text-white mb-4"><i
                class="bi bi-left-arrow"></i>
            Kembali</button>
    </a>

    @if ($data_alat == null)
        <p>tidak ada data...</p>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <img src="{{ asset('storage/img_upload/data_alat/' . $data_alat->thumbnail) }}"
                                class="card-img-top " alt="..." style="max-width: 1200px; max-height: 500px;">
                        </div>
                        <h4 class="card-title mt-3">{{ $data_alat->nama_alat }}</h4>
                        <p><span class="badge text-bg-info text-white"><i class="bi bi-people"></i>
                                {{ $data_alat->kapasitas }}</span>

                        </p>
                        <p class="card-text">Fasilitas = {{ $data_alat->fasilitas }}</p>

                        <hr>
                        <h4><i>Review User</i></h4>
                        @if ($review_user->isEmpty())
                            <p>...</p>
                        @else
                            @foreach ($review_user as $reviewnya)
                                @if ($reviewnya->rating !== null)
                                    <div class="my-2" style="width:100%; border: 1px solid red">
                                        <div class="p-2" style="font-size: 16px">
                                            {{ $reviewnya->username }}

                                            <div class="rating" style="font-size: 10px">
                                                <input type="radio" id="star5_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    name="rating_{{ $reviewnya->id_pesanan_jadwal_studio }}" value="5"
                                                    {{ $reviewnya->rating == 5 ? 'checked' : '' }} disabled />
                                                <label for="star5_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    title="5 stars">&#9733;</label>
                                                <input type="radio" id="star4_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    name="rating_{{ $reviewnya->id_pesanan_jadwal_studio }}" value="4"
                                                    {{ $reviewnya->rating == 4 ? 'checked' : '' }} disabled />
                                                <label for="star4_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    title="4 stars">&#9733;</label>
                                                <input type="radio" id="star3_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    name="rating_{{ $reviewnya->id_pesanan_jadwal_studio }}" value="3"
                                                    {{ $reviewnya->rating == 3 ? 'checked' : '' }} disabled />
                                                <label for="star3_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    title="3 stars">&#9733;</label>
                                                <input type="radio" id="star2_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    name="rating_{{ $reviewnya->id_pesanan_jadwal_studio }}" value="2"
                                                    {{ $reviewnya->rating == 2 ? 'checked' : '' }} disabled />
                                                <label for="star2_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    title="2 stars">&#9733;</label>
                                                <input type="radio" id="star1_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    name="rating_{{ $reviewnya->id_pesanan_jadwal_studio }}" value="1"
                                                    {{ $reviewnya->rating == 1 ? 'checked' : '' }} disabled />
                                                <label for="star1_{{ $reviewnya->id_pesanan_jadwal_studio }}"
                                                    title="1 star">&#9733;</label>
                                            </div>
                                            <p class="m-0 blockquote-footer">{{ $reviewnya->review }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
