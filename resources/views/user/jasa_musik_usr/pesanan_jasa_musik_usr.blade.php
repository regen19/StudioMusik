@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Pesanan Jasa Musik</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" data-bs-toggle="modal" data-bs-target="#add_jasa_musik"><i
                class="bi bi-plus-lg"></i> Pesan Jasa Musik
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbPesanan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Produksi</th>
                                <th>Tenggat Produksi</th>
                                <th>Jenis Jasa</th>
                                <th>Nama Pemesan</th>
                                <th>Status Persetujuan</th>
                                <th>Status Produksi</th>
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


    @include('user.jasa_musik_usr.md_add_jasa_musik_usr')
    @include('user.jasa_musik_usr.md_detail_pesanan_jasa_musik_usr')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbPesanan').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_jasa_musik_saya') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'tgl_produksi'
                        },
                        {
                            data: 'tenggat_produksi'
                        },
                        {
                            data: 'nama_jenis_jasa',
                        },
                        {
                            data: 'username',
                        },
                        {
                            data: null,
                            render: function(data) {

                                let status = "";
                                let color = "";
                                if (data.status_persetujuan === "P") {
                                    status = "Pengajuan"
                                    color = "warning"
                                } else if (data.status_persetujuan === "Y") {
                                    status = "Disetujui"
                                    color = "success"
                                } else if (data.status_persetujuan === "N") {
                                    status = "Ditolak"
                                    color = "danger"
                                }

                                return `
                                   <div>
                                        <a type="button" class="badge bg-${color}">
                                            ${status}
                                        </a>

                                    </div>
                                `;
                            }
                        },
                        {
                            data: null,
                            render: function(data) {

                                let status = "";
                                let color = "";
                                if (data.status_produksi === "P") {
                                    status = "Diproses"
                                    color = "primary"
                                } else if (data.status_produksi === "Y") {
                                    status = "Selesai"
                                    color = "success"
                                } else if (data.status_produksi === "N") {
                                    status = "Diajukan"
                                    color = "warning"
                                }

                                return `
                                   <div>
                                        <a type="button" class="badge bg-${color}">
                                            ${status}
                                        </a>
                                    </div>
                                `;
                            }
                        },
                        {
                            title: 'Menu',
                            data: null,
                            render: function(data) {

                                let textPersetujuan = "Detail";
                                let colorBtn = "primary"

                                if (data.status_persetujuan === "Y" && data.status_produksi === "Y" &&
                                    data.review === null && data.rating === null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                    Beri Rating
                                                </button>
                                            </div>

                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-primary icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#detail_pesanan" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                    Detail
                                                </button>
                                            </div> 
                                        </td>
                                    `;
                                }

                                if (data.status_persetujuan === "Y" && data.status_produksi === "Y" &&
                                    data.review !== null && data.rating !== null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#detail_pesanan" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                }

                                if (data.status_persetujuan !== "P") {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#detail_pesanan" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                    ${textPersetujuan}
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                } else if (data.status_pengajuan === "X") {
                                    return `
                                    <td><i class="text-danger">Dibatalkan</i></td>
                                    `;
                                } else {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-primary icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#detail_pesanan" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                    Detail
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_pesanan(${data.id_pesanan_jasa_musik})"> 
                                                    Batalkan
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

            function hapus_pesanan(id_pesanan_jasa_musik) {
                Swal.fire({
                    title: "Batalkan pesanan?",
                    text: "Data pesanan akan dibatalkan.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Batalkan!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dibatalkan!",
                                    text: "Data pesanan telah dibatalkan.",
                                    icon: "success"
                                });

                                $('#tbPesanan').DataTable().ajax.reload()

                                setTimeout(() => {
                                    swal.close()
                                }, 1000);
                            }
                        })
                    }
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
                                <input type="radio" id="star5" name="rating0" value="5" />
                                <label for="star5" title="5 stars">&#9733;</label>
                                <input type="radio" id="star4" name="rating0" value="4" />
                                <label for="star4" title="4 stars">&#9733;</label>
                                <input type="radio" id="star3" name="rating0" value="3" />
                                <label for="star3" title="3 stars">&#9733;</label>
                                <input type="radio" id="star2" name="rating0" value="2" />
                                <label for="star2" title="2 stars">&#9733;</label>
                                <input type="radio" id="star1" name="rating0" value="1" />
                                <label for="star1" title="1 star">&#9733;</label>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="komentar_rating">Komentar</label> <br>
                            <textarea class="form-control" name="komentar_rating" id="komentar_rating" cols="30" rows="5" required></textarea>

                            <input type="hidden" id="id_pesanan_jasa_musik">
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
                let selectedRating = $('input[name="rating0"]:checked').val();
                let review = $("#komentar_rating").val()
                let id_pesanan_jasa_musik = $("#id_pesanan_jasa_musik").val()

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
                    url: `{{ url('/beri_rating_jasa/${id_pesanan_jasa_musik}') }}`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(result) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Terimakasih atas review anda.",
                            icon: "success"
                        });

                        $('#tbPesanan').DataTable().ajax.reload()
                        $("#rating").modal("hide")
                        selectedRating.val("")
                        review.val("")

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
