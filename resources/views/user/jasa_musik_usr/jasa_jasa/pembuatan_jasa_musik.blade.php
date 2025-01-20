@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3 class="text-uppercase">PEMBUATAN {{ $jenis_jasa->nama_jenis_jasa }}</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button text-uppercase" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            SYARAT DAN KETENTUAN PEMBUATAN {{ $jenis_jasa->nama_jenis_jasa }}
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @if ($jenis_jasa)
                                                {{ $jenis_jasa->sk }}
                                            @else
                                                <p>Sedang dalam pembaruan...</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">
                                <a href="{{ url('/pesanan_jasa_musik_saya') }}"><button class="btn btn-success btn-lg">Buat
                                        {{ $jenis_jasa->nama_jenis_jasa }}</button></a>
                            </div>
                            <div class="card-body">
                                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">

                                    </div>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            @if ($jenis_jasa)
                                                <img src="{{ asset('storage/img_upload/jasa_musik/' . $jenis_jasa->gambar) }}"
                                                    class="d-block w-100 h-100" alt="...">
                                            @else
                                                <p class="text-center">Gambar sedang diperbarui</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Review Pengguna</h5>
                            </div>
                            <div class="card-body">
                                <div class="list_review overflow-auto" style="height: 600px;">
                                    <div class="row">
                                        @if ($rating !== null)
                                            @foreach ($rating as $rt)
                                                <div class="my-2" style="width:100%; border: 1px solid red">
                                                    <div class="p-2" style="font-size: 16px">
                                                        {{ $rt->username }}

                                                        <div class="rating" style="font-size: 10px">
                                                            <input type="radio"
                                                                id="star5_{{ $rt->id_pesanan_jasa_musik }}"
                                                                name="rating_{{ $rt->id_pesanan_jasa_musik }}"
                                                                value="5" {{ $rt->rating == 5 ? 'checked' : '' }}
                                                                disabled />
                                                            <label for="star5_{{ $rt->id_pesanan_jasa_musik }}"
                                                                title="5 stars">&#9733;</label>
                                                            <input type="radio"
                                                                id="star4_{{ $rt->id_pesanan_jasa_musik }}"
                                                                name="rating_{{ $rt->id_pesanan_jasa_musik }}"
                                                                value="4" {{ $rt->rating == 4 ? 'checked' : '' }}
                                                                disabled />
                                                            <label for="star4_{{ $rt->id_pesanan_jasa_musik }}"
                                                                title="4 stars">&#9733;</label>
                                                            <input type="radio"
                                                                id="star3_{{ $rt->id_pesanan_jasa_musik }}"
                                                                name="rating_{{ $rt->id_pesanan_jasa_musik }}"
                                                                value="3" {{ $rt->rating == 3 ? 'checked' : '' }}
                                                                disabled />
                                                            <label for="star3_{{ $rt->id_pesanan_jasa_musik }}"
                                                                title="3 stars">&#9733;</label>
                                                            <input type="radio"
                                                                id="star2_{{ $rt->id_pesanan_jasa_musik }}"
                                                                name="rating_{{ $rt->id_pesanan_jasa_musik }}"
                                                                value="2" {{ $rt->rating == 2 ? 'checked' : '' }}
                                                                disabled />
                                                            <label for="star2_{{ $rt->id_pesanan_jasa_musik }}"
                                                                title="2 stars">&#9733;</label>
                                                            <input type="radio"
                                                                id="star1_{{ $rt->id_pesanan_jasa_musik }}"
                                                                name="rating_{{ $rt->id_pesanan_jasa_musik }}"
                                                                value="1" {{ $rt->rating == 1 ? 'checked' : '' }}
                                                                disabled />
                                                            <label for="star1_{{ $rt->id_pesanan_jasa_musik }}"
                                                                title="1 star">&#9733;</label>
                                                        </div>
                                                        <p class="m-0 blockquote-footer">{{ $rt->review }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div>Belum ada penilain pengguna</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
