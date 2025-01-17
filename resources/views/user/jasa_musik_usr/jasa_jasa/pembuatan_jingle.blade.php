@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>PEMBUATAN JINGLE</h3>
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
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            SYARAT DAN KETENTUAN PEMBUATAN JINGLE
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @if ($jingle)
                                                {{ $jingle->sk }}
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
                                        Jingle</button></a>
                            </div>
                            <div class="card-body">
                                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        {{-- <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="0" class="active" aria-current="true"
                                            aria-label="Slide 1"></button> --}}
                                        {{-- <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="2" aria-label="Slide 3"></button> --}}
                                    </div>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            @if ($jingle)
                                                <img src="{{ asset('storage/img_upload/' . $jingle->gambar) }}"
                                                    class="d-block w-100 h-100" alt="...">
                                            @else
                                                <p class="text-center">Gambar sedang diperbarui</p>
                                            @endif

                                            {{-- <div class="carousel-caption d-none d-md-block">
                                                <h5>First slide label</h5>
                                                <p>Some representative placeholder content for the first slide.</p>
                                            </div> --}}
                                        </div>
                                        {{-- <div class="carousel-item">
                                            <img src="{{ asset('assets/img/icon-user/2.jpg') }}" class="d-block w-100 h-100"
                                                alt="...">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>Second slide label</h5>
                                                <p>Some representative placeholder content for the second slide.</p>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="{{ asset('assets/img/icon-user/3.jpg') }}" class="d-block w-100 h-100"
                                                alt="...">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5>Third slide label</h5>
                                                <p>Some representative placeholder content for the third slide.</p>
                                            </div>
                                        </div> --}}
                                    </div>
                                    {{-- <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <h3>Paket Paket</h3>
                </div>

                <div class="row">
                    @if ($paket !== null)
                        @foreach ($paket as $pk)
                            <div class="col-4">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase"><u>{{ $pk->nama_paket }}</u></h5>
                                        <p>
                                            <span class="badge text-bg-success"><i class="bi bi-cash-stack"></i>
                                                Rp{{ $pk->biaya_paket }}</span>
                                        </p>
                                        <p class="card-text">{{ $pk->deskripsi }}.</p>
                                        <p>Rincian {{ $pk->rincian_paket }}</p>
                                        <a href="{{ url('/pesanan_jasa_musik_saya') }}" class="btn btn-primary">Pesan</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div>Dalam pembaharuan</div>
                    @endif
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
