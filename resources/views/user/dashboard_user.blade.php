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
                                            <strong>Standar Operasional Prosedur (SOP)
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>
                                                <ol>
                                                    <li>Peminjaman studio hanya dilakukan pada hari <strong>Senin -
                                                            Jumat</strong>
                                                        dan bisa dipakai dari <strong>pukul 17.00 - 21.00</strong>.
                                                    </li>
                                                    <br>
                                                    <li>Setelah menggunakan studio, peminjam harus <strong>membereskan
                                                            barang bawaan</strong>
                                                        dan <strong>merapikan alat</strong> yang digunakan ke kondisi
                                                        semula.
                                                    </li>
                                                    <br>
                                                    <li>Peminjam wajib <strong>mengupload kondisi ruangan studio</strong>
                                                        sebelum dan sesudah
                                                        dipakai pada <strong>website studio</strong>.
                                                    </li>
                                                    <br>
                                                    <li><strong>Jumlah maksimal orang</strong> yang berada dalam studio
                                                        adalah <strong>10 orang</strong>.
                                                    </li>
                                                    <br>
                                                    <li>Setiap <strong>kerusakan alat</strong> harus diganti dengan
                                                        <strong>spesifikasi yang sama</strong>.
                                                        Apabila tidak ada itikad baik atau pertanggungjawaban, maka akan
                                                        diberikan <strong>sanksi</strong>.
                                                    </li>
                                                    <br>
                                                    <li>Studio <strong>tidak dapat dipinjam</strong> pada saat
                                                        berlangsungnya <strong>UTS/UAS</strong>
                                                        dan kegiatan <strong>UKMBSM ITERA</strong>.
                                                    </li>
                                                    <br>
                                                    <li><strong>Pemesanan jasa musik</strong> dilakukan dengan syarat &
                                                        ketentuan yang sudah tercantum.
                                                    </li>
                                                </ol>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <strong>Sejarah Studio Musik ITERA
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <strong>Studio Musik ITERA diresmikan pada tanggal 22 Februari 2018 oleh
                                                Alm. Prof. Ir. Ofyar Z Tamin, M.Sc., Ph.D., IPU., yang kala itu menjadi
                                                rektor ITERA. Studio Musik ITERA dibangun sebagai fasilitas kampus yang
                                                menunjang kegiatan non-akademik seluruh civitas akedemika ITERA.
                                                Berlokasi di Gedung D Lantai 3, tepat di ruangan ujung lantai.
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
                                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($display as $key => $dp)
                                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/img_upload/jasa_musik/' . $dp->gambar) }}"
                                                        class="img-fluid w-100" alt="...">

                                                    <!-- Caption di dalam gambar (Desktop) -->
                                                    <div class="carousel-caption d-none d-md-block custom-caption">
                                                        <h5>{{ $dp->nama_jenis_jasa }}</h5>
                                                        <p>{{ $dp->deskripsi }}</p>
                                                    </div>

                                                    <!-- Caption di luar gambar (Mobile) -->
                                                    <div class="d-block d-md-none text-center p-3 bg-light">
                                                        <h5>{{ $dp->nama_jenis_jasa }}</h5>
                                                        <p>{{ $dp->deskripsi }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
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

