@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Dashboard User</h3>
        <p>Halo, Selamat datang {{ Auth::user()->username }}!</p>
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
                                            Accordion Item #1
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>This is the first item's accordion body.</strong> It is shown by
                                            default, until the collapse plugin adds the appropriate classes that we use to
                                            style each element. These classes control the overall appearance, as well as the
                                            showing and hiding via CSS transitions. You can modify any of this with custom
                                            CSS or overriding our default variables. It's also worth noting that just about
                                            any HTML can go within the <code>.accordion-body</code>, though the transition
                                            does limit overflow.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Accordion Item #2
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>This is the second item's accordion body.</strong> It is hidden by
                                            default, until the collapse plugin adds the appropriate classes that we use to
                                            style each element. These classes control the overall appearance, as well as the
                                            showing and hiding via CSS transitions. You can modify any of this with custom
                                            CSS or overriding our default variables. It's also worth noting that just about
                                            any HTML can go within the <code>.accordion-body</code>, though the transition
                                            does limit overflow.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Accordion Item #3
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>This is the third item's accordion body.</strong> It is hidden by
                                            default, until the collapse plugin adds the appropriate classes that we use to
                                            style each element. These classes control the overall appearance, as well as the
                                            showing and hiding via CSS transitions. You can modify any of this with custom
                                            CSS or overriding our default variables. It's also worth noting that just about
                                            any HTML can go within the <code>.accordion-body</code>, though the transition
                                            does limit overflow.
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
                                <h4>Informasi Terbaru</h4>
                            </div>
                            <div class="card-body">
                                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="0" class="active" aria-current="true"
                                            aria-label="Slide 1"></button>
                                        <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                        <button type="button" data-bs-target="#carouselExampleCaptions"
                                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                                    </div>
                                    <div class="carousel-inner">
                                        @foreach ($display as $key => $dp)
                                            @if ($key == 0)
                                                <div class="carousel-item active">
                                                    <img src="{{ asset('storage/img_upload/jasa_musik/' . $dp->gambar) }}"
                                                        class="d-block w-100 h-100" alt="...">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h5>{{ $dp->nama_jenis_jasa }}</h5>
                                                        <p class="text-white">{{ $dp->deskripsi }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="carousel-item">
                                                    <img src="{{ asset('storage/img_upload/jasa_musik/' . $dp->gambar) }}"
                                                        class="d-block w-100 h-100" alt="...">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h5>{{ $dp->nama_jenis_jasa }}</h5>
                                                        <p class="text-white">{{ $dp->deskripsi }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
