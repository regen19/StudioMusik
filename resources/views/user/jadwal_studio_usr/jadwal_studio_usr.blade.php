@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Jadwal Saya</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        @if ($cek_pesanan)
            <button type="button" class="btn btn-primary icon icon-left" onclick="btnJadwalGagal()"><i
                    class="bi bi-plus-lg"></i>
                Ajukan Sewa Studio
            </button>
        @else
            <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i
                    class="bi bi-plus-lg"></i>
                Ajukan Sewa Studio
            </button>
        @endif
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableJadwalStudio">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Waktu Mulai & Selesai</th>
                                <th>Ruangan</th>
                                {{-- <th>Nomor WA</th> --}}
                                <th>Keperluan</th>
                                <th>Status Persetujuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @include('user.jadwal_studio_usr.md_add_jadwal_studio_usr')
    @include('user.jadwal_studio_usr.md_detail_jadwal_studio_usr')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tableJadwalStudio').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_jadwal_studio_saya') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'tgl_pinjam'
                        },
                        {
                            data: null,
                            render: function(data) {
                                return data.waktu_mulai + " - " + data.waktu_selesai;
                            }
                        },
                        {
                            data: 'nama_ruangan',
                        },
                        // {
                        //     data: 'no_wa',
                        // },
                        {
                            data: 'ket_keperluan',
                        },
                        {
                            data: null,
                            render: function(data) {

                                let statusPersetujuan = "";
                                let colorPersetujuan = "";

                                if (data.status_persetujuan === "P") {
                                    statusPersetujuan = "Pengajuan"
                                    colorPersetujuan = "warning"
                                } else if (data.status_persetujuan === "Y") {
                                    statusPersetujuan = "Disetujui"
                                    colorPersetujuan = "success"
                                } else if (data.status_persetujuan === "N") {
                                    statusPersetujuan = "Ditolak"
                                    colorPersetujuan = "danger"
                                }

                                return `
                                    <div>
                                        <a type="button" class="badge bg-${colorPersetujuan}">
                                            ${statusPersetujuan}
                                        </a>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: null,
                            render: function(data) {

                                let textPersetujuan = "Detail";
                                let colorBtn = "primary"

                                if (data.status_persetujuan === "Y" && data.status_peminjaman === "Y" &&
                                    data.review === null && data.rating === null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-warning icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    Beri Rating
                                                </button>
                                            </div>

                                             <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    ${textPersetujuan}
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                } else if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                    "Y" &&
                                    data.review !== null && data.rating !== null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                }


                                if (data.status_persetujuan === "P" && data.status_pengajuan !== "X") {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_pesanan_jadwal_studio}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jadwal(${data.id_pesanan_jadwal_studio})">
                                                    Batalkan
                                                </button>

                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                } else if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                    "N") {
                                    return `
                                            <td>
                                                <div style="margin-right: 20px;">
                                                    <button type="button" class="btn btn-warning icon icon-left text-white" onclick="PengembalianRuangan(${data.id_pesanan_jadwal_studio})">
                                                        Pengembalian
                                                    </button>

                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                        ${textPersetujuan}
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                } else if (data.status_pengajuan === "X" && data.status_persetujuan ===
                                    "P") {
                                    return `
                                    <td><i class="text-danger">Dibatalkan</i></td>
                                    `;
                                } else {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                }
                            }
                        }
                    ],
                });
            })



            function hapus_jadwal(id_pesanan_jadwal_studio) {
                Swal.fire({
                    title: "Batalkan Pesanan Studio?",
                    text: "Data Jadwal akan dibatalkan.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Batalkan!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dibatalkan!",
                                    text: "Data jadwal telah dibatalkan.",
                                    icon: "success"
                                });

                                $('#tableJadwalStudio').DataTable().ajax.reload()

                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        })
                    }
                });
            }

            function PengembalianRuangan(id_pesanan_jadwal_studio) {
                Swal.fire({
                    title: "Selesaikan Peminjaman Ruangan?",
                    text: "Peminjaman akan selesai, dan bisa mengajukan peminjaman lagi!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yaa, selesaikan!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/pengembalian_ruangan') }}`,
                            data: {
                                "id_pesanan_jadwal_studio": id_pesanan_jadwal_studio,
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'post',
                            success: function(response) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Peminjaman Ruangan telah selesai.",
                                    icon: "success"
                                });

                                $('#tableJadwalStudio').DataTable().ajax.reload()

                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        })
                    }
                });
            }

            function btnJadwalGagal() {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Anda tidak dapat membuat jadwal baru, terdapat pengajuan jadwal yang belum terselesaikan!",
                });
            }
        </script>
    @endpush

    <div class="modal fade" id="rating" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Beri Rating</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rating">Nilai Kami</label> <br>

                            <div class="rating">
                                <input type="radio" id="star5" name="rating1" value="5" />
                                <label for="star5" title="5 stars">&#9733;</label>
                                <input type="radio" id="star4" name="rating1" value="4" />
                                <label for="star4" title="4 stars">&#9733;</label>
                                <input type="radio" id="star3" name="rating1" value="3" />
                                <label for="star3" title="3 stars">&#9733;</label>
                                <input type="radio" id="star2" name="rating1" value="2" />
                                <label for="star2" title="2 stars">&#9733;</label>
                                <input type="radio" id="star1" name="rating1" value="1" />
                                <label for="star1" title="1 star">&#9733;</label>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="komentar_rating">Komentar</label> <br>
                            <textarea class="form-control" name="komentar_rating" id="komentar_rating" cols="30" rows="5" required></textarea>

                            <input type="hidden" id="id_pesanan_jadwal_studio">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="btnRating()">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function btnRating() {
                let selectedRating = $('input[name="rating1"]:checked').val();
                let review = $("#komentar_rating").val()
                let id_pesanan_jadwal_studio = $("#id_pesanan_jadwal_studio").val()

                if (!selectedRating) {
                    alert('Pilih nilai rating terlebih dahulu!');
                    return;
                }

                const data = {
                    rating: selectedRating,
                    review: review,
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({
                    url: `{{ url('/beri_rating_studio/${id_pesanan_jadwal_studio}') }}`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(result) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Terimakasih atas review anda.",
                            icon: "success"
                        });

                        $('#tableJadwalStudio').DataTable().ajax.reload()
                        $("#rating").modal("hide")

                        setTimeout(() => {
                            swal.close()
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengirim rating');
                    }
                });
            }
        </script>
    @endpush
@endsection
