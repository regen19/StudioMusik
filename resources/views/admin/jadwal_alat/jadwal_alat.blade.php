@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Data Peminjam Alat</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

<<<<<<< Updated upstream
        <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i class="bi bi-plus-lg"></i>
            Ajukan Peminjaman
        </button>

=======
        <!-- <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i class="bi bi-plus-lg"></i>
            Ajukan Peminjaman
        </button> -->

        <a href="{{ route('jadwal.alat', ['tanggal'=>date('Y-m-d'),'mulai'=>'08:00','selesai'=>'17:00']) }}"
            class="btn btn-secondary text-white"><i class="bi bi-calendar-week"></i> Lihat Jadwal Ketersediaan
        </a>
>>>>>>> Stashed changes

        <input type="hidden" value="{{ Auth::user()->id_user }}" id="id_user">
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableJadwalAlat">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Waktu Mulai & Selesai</th>
                                <th>Nama Peminjam</th>
                                <th>Alat</th>
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

<<<<<<< Updated upstream

    <!-- @include('admin.modal_popup.md_add_pesanan_pinjam_alat')
    @include('admin.modal_popup.md_detail_jadwal_alat') -->
=======
    <div class="modal fade" id="detail_alat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan Pinjam Alat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="tgl_pengajuan"></p>
                    <table class="table">
                        <tr><th>Nama</th><td id="nama_user1"></td></tr>
                        <tr><th>Tanggal & Jam</th><td id="tanggal"></td></tr>
                        <tr><th>Keperluan</th><td id="catatan"></td></tr>
                        <tr><th>Status Persetujuan</th><td id="status_setuju"></td></tr>
                        <tr><th>Status Peminjaman</th><td id="status_pinjam"></td></tr>
                    </table>

                    <div class="d-flex gap-3">
                        <a id="link-foto-jaminan" target="_blank" style="display:none">
                            <img id="foto_jaminan1" style="max-width:200px;max-height:200px"/>
                        </a>
                    </div>

                    <div class="mt-3">
                        <label>Keterangan Admin</label>
                        <textarea id="catatan_admin" class="form-control" rows="3" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
>>>>>>> Stashed changes

    @push('script')
        <script>
            $(document).ready(function() {
                let id_user = $("#id_user").val();
<<<<<<< Updated upstream

                $('#tableJadwalAlat').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    "columnDefs": [{
                        "width": "100%",
                    }],
                    layout: {
                        topStart: {
                            buttons: ['excel', 'pdf']
                        }
                    },
                    ajax: {
                        url: "{{ url('/fetch_pesanan_jadwal_alat') }}",
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
                            data: 'username',
                        },
                        {
                            data: 'nama_alat',
                        },
                        {
                            data: 'ket_keperluan',
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
=======
                    $('#tableJadwalAlat').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        searching: true,
                        ajax: {
                            url: "{{ url('/fetch_pesanan_pinjam_alat') }}", 
                            type: 'GET',
                        },
                        order: [[1, 'desc']],
                        columns: [
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },

                            { data: 'tgl_pinjam', name: 'p.tgl_pinjam' },

                            {
                            data: null,
                            render: function (row) {
                                return row.waktu_mulai + ' - ' + row.waktu_selesai;
                            },
                            name: 'p.waktu_mulai'
                            },

                            { data: 'username', name: 'u.username' },

                            { data: 'nama_alat', name: 'a.nama_alat' },

                            { data: 'ket_keperluan', name: 'p.ket_keperluan' },

                            {
                            data: null,
                            render: function (data) {
                                let status = "", color = "";
                                if (data.status_persetujuan === "P") { status = "Pengajuan"; color = "warning"; }
                                else if (data.status_persetujuan === "Y") { status = "Disetujui"; color = "success"; }
                                else if (data.status_persetujuan === "N") { status = "Ditolak";   color = "danger";  }
                                return `<div><a type="button" class="badge bg-${color}">${status}</a></div>`;
>>>>>>> Stashed changes
                            }
                        },
                        {
                            data: null,
                            render: function(data) {

                                let textPersetujuan = "Detail";
                                let colorBtn = "primary"

                                if (data.id_user == id_user) {
                                    if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                        "Y" &&
                                        data.review === null && data.rating === null) {
                                        return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-warning icon icon-left text-white"
<<<<<<< Updated upstream
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    Beri Rating
                                                </button>
                                            </div>

                                            <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    ${textPersetujuan}
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                    } else if (data.status_persetujuan === "Y" && data
                                        .status_peminjaman ===
                                        "Y" &&
                                        data.review !== null && data.rating !== null) {
                                        return `
                                        <td>
                                            <div style="margin-rigth=20px;">
<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                    }


                                    if (data.status_persetujuan === "P" && data.status_pengajuan !==
                                        "X") {
                                        return `
                                        <td>
                                            <div style="margin-right: 20px;">
<<<<<<< Updated upstream
=======
                                              @canany(['isAdmin','isUser'])
>>>>>>> Stashed changes
                                                <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_pesanan_jadwal_alat}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jadwal(${data.id_pesanan_jadwal_alat})">
                                                    Batalkan
                                                </button>

<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
                                                    Detail
                                                </button>
                                            </div>
=======
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
                                                    Detail
                                                </button>
                                            </div>
                                            @else
                                                <span class="text-muted">View only</span>
                                            @endcanany
>>>>>>> Stashed changes
                                        </td>
                                    `;

                                    } else if (data.status_persetujuan === "Y" && data
                                        .status_peminjaman === "N") {
                                        return `
                                            <td>
                                                <div style="margin-right: 20px;">
                                                    <button type="button" class="btn btn-success icon icon-left text-white" onclick="PengembalianAlat(${data.id_pesanan_jadwal_alat})">
                                                        Pengembalian
                                                    </button>

<<<<<<< Updated upstream
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                        ${textPersetujuan}
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                    } else if (data.status_pengajuan === "X" && data
                                        .status_persetujuan === "P") {
                                        return `
                                            <td><i class="text-danger">Dibatalkan</i></td>
                                        `;
                                    } else {
                                        return `
                                            <td>
                                                <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                    <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                    <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                        Selesai
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                    }
                                } else {
                                    if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                        "Y" &&
                                        data.review === null && data.rating === null) {
                                        return `
                                        <td>
                                            <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    ${textPersetujuan}
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                    } else if (data.status_persetujuan === "Y" && data
                                        .status_peminjaman ===
                                        "Y" &&
                                        data.review !== null && data.rating !== null) {
                                        return `
                                        <td>
                                            <div style="margin-rigth=20px;">
<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                    }


                                    if (data.status_persetujuan === "P" && data.status_pengajuan !==
                                        "X") {
                                        return `
                                        <td>
                                            <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                    } else if (data.status_persetujuan === "Y" && data
                                        .status_peminjaman ===
                                        "N") {
                                        return `
                                            <td>
                                                <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                        ${textPersetujuan}
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                    } else if (data.status_pengajuan === "X" && data
                                        .status_persetujuan ===
                                        "P") {
                                        return `
                                        <td><i class="text-danger">Dibatalkan</i></td>
                                    `;
                                    } else {
                                        return `
                                        <td>
                                            <div style="margin-right: 20px;">
<<<<<<< Updated upstream
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_jadwal_alat})">
=======
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat || data.id_pesanan_jadwal_alat})">
>>>>>>> Stashed changes
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                    }
                                }
                            }
                        }
                    ],
                });
            })

            function PengembalianAlat(id_pesanan_jadwal_alat) {
                Swal.fire({
                    title: "Selesaikan Peminjaman Alat?",
                    text: "Peminjaman akan selesai, dan bisa mengajukan peminjaman lagi!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yaa, selesaikan!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('/pengembalian_alat') }}`,
                            data: {
                                "id_pesanan_jadwal_alat": id_pesanan_jadwal_alat,
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'post',
                            success: function(response) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Peminjaman Alat telah selesai.",
                                    icon: "success"
                                });

                                $('#tableJadwalAlat').DataTable().ajax.reload()

                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        })
                    }
                });
            }

            function hapus_jadwal(id_pesanan_jadwal_alat) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data Jadwal akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_pesanan_jadwal_alat/${id_pesanan_jadwal_alat}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data jadwal telah dihapus.",
                                    icon: "success"
                                });

                                $('#tableJadwalAlat').DataTable().ajax.reload()

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

<<<<<<< Updated upstream
=======
    @push('script')
    <script>
        (function () {
            window.show_byID = function (id) {
                $.ajax({
                url: `{{ url('/showById_pesanan_pinjam_alat') }}/${id}`,
                type: 'POST',
                dataType: 'json',
                data: { _token: "{{ csrf_token() }}" },
                success: function (r) {
                    $('#tgl_pengajuan').text('Pengajuan pada : ' + (r.created_at ?? r.tgl_pinjam));
                    $('#nama_user1').text(r.username ?? '-');
                    $('#tanggal').text(`${r.tgl_pinjam} / ${r.waktu_mulai} - ${r.waktu_selesai}`);
                    $('#catatan').text(r.ket_keperluan ?? '-');
                    $('#catatan_admin').val(r.ket_admin ?? '');

                    const stMap   = { P: ['Pengajuan','warning'], Y: ['Disetujui','success'], N: ['Ditolak','danger'] };
                    const pinjMap = { Y: ['Telah Selesai','success'], N: ['Proses','warning'] };
                    const s1 = stMap[r.status_persetujuan] || ['-','secondary'];
                    const s2 = pinjMap[r.status_peminjaman] || ['-','secondary'];
                    $('#status_setuju').html(`<span class="badge bg-${s1[1]}">${s1[0]}</span>`);
                    $('#status_pinjam').html(`<span class="badge bg-${s2[1]}">${s2[0]}</span>`);

                    if (r.foto_jaminan) {
                    const src = `{{ asset('storage/img_upload/data_jaminan') }}/${r.foto_jaminan}`;
                    $('#link-foto-jaminan').attr('href', src).show();
                    $('#foto_jaminan1').attr('src', src).show();
                    } else {
                    $('#link-foto-jaminan').attr('href','').hide();
                    $('#foto_jaminan1').attr('src','').hide();
                    }

                    if (r.img_kondisi_awal) {
                    const s = `{{ asset('storage/img_upload/kondisi/awal') }}/${r.img_kondisi_awal}`;
                    $('#show_form_kondisi_awal').hide();
                    $('#img_kondisi_awal').attr('src', s);
                    $('#link-img-kondisi-awal').attr('href', s);
                    $('#show_kondisi_awal').show();
                    } else {
                    $('#show_kondisi_awal').hide();
                    $('#show_form_kondisi_awal').show();
                    }

                    if (r.img_kondisi_akhir) {
                    const s = `{{ asset('storage/img_upload/kondisi/akhir') }}/${r.img_kondisi_akhir}`;
                    $('#show_form_kondisi_akhir').hide();
                    $('#img_kondisi_akhir').attr('src', s);
                    $('#link-img-kondisi-akhir').attr('href', s);
                    $('#show_kondisi_akhir').show();
                    } else {
                    $('#show_kondisi_akhir').hide();
                    $('#show_form_kondisi_akhir').show();
                    }

                        bootstrap.Modal.getOrCreateInstance(document.getElementById('detail_alat')).show();
                    },
                        error: function () { Swal.fire({ icon:'error', title:'Oops…', text:'Gagal memuat detail.' });
                    }
                });
            };
        })();
    </script>
    @endpush

>>>>>>> Stashed changes
    {{-- STATUS PERSETUJUAN --}}
    <div class="modal fade" id="status_jadwal_alat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Status Persetujuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_pinjam">Status</label>
                        <select name="status_persetujuan" id="status_persetujuan" class="form-control"
                            onchange="cek_setuju()">
                            <option value="P">Pengajuan</option>
                            <option value="Y">Disetujui</option>
                            <option value="N">Ditolak</option>
                        </select>

                        <input type="hidden" id="id_pesanan_jadwal_alat">
                    </div>

                    <div class="form-group" style="display: none" id="ket_admin">
                        <label for="keterangan_admin">Keterangan Admin</label>
                        <textarea class="form-control" name="keterangan_admin" id="keterangan_admin" cols="30" rows="7" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnSimpanText" class="btn btn-primary"
                        onclick="btnStatusSetuju()">Simpan</button>
                    <span id="btnSimpanLoading" style="display:none;">
                        <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." style="width:20px;" />
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- RATING --}}
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

                            <input type="hidden" id="id_pesanan_jadwal_alat1">
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
            let status_persetujuan1
            let status_pengajuan1
            let status_peminjaman1
            let id_pesanan_jadwal_alat1

            function cek_setuju() {
                let selectedValue = $('#status_persetujuan').val()

                if (selectedValue != "P") {
                    $('#ket_admin').show();
                } else {
                    $('#ket_admin').hide();
                }
            }

            function data_status(id_pesanan_jadwal_alat, data) {
                $.ajax({
                    url: `{{ url('/showById_pesanan_jadwal_alat/${id_pesanan_jadwal_alat}') }}`,
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        status_persetujuan1 = response.status_peminjaman
                        status_pengajuan1 = response.status_pengajuan
                        status_peminjaman1 = response.status_peminjaman
                        id_pesanan_jadwal_alat1 = id_pesanan_jadwal_alat

                        $(".id_pesanan_jadwal_alat").val(id_pesanan_jadwal_alat)
                        $('#status_persetujuan').val(response.status_persetujuan)

                        if (response.ket_admin) {
                            $("#ket_admin").show()
                            $('#keterangan_admin').val(response.ket_admin)
                        }
                    }
                });
            }

            function btnStatusSetuju() {
                $("#btnSimpanText").hide();
                $("#btnSimpanLoading").show();

                let status_persetujuan = $('#status_persetujuan').val()
                let keterangan_admin = $('#keterangan_admin').val()
                let id_pesanan_jadwal_alat = $('#id_pesanan_jadwal_alat').val()

                if (status_peminjaman1 == "Y" && status_persetujuan1 == "Y" && status_pengajuan1 == "Y") {
                    Swal.fire({
                        title: "Pesanan Telah Selesai!",
                        text: "Tidak dapat mengubah status persetujuan",
                        icon: "warning"
                    });

                    return 0;
                } else if (status_pengajuan1 == "X") {
                    Swal.fire({
                        title: "Pesanan Telah Dibatalkan!",
                        text: "Tidak dapat mengubah status persetujuan",
                        icon: "warning"
                    });

                    return 0;
                } else {
                    $.ajax({
                        url: `{{ url('/status_pesanan_jadwal_alat/${id_pesanan_jadwal_alat1}') }}`,
                        method: 'post',
                        data: {
                            "status_persetujuan": status_persetujuan,
                            "ket_admin": keterangan_admin,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#tableJadwalAlat').DataTable().ajax.reload()
                            $("#status_jadwal_alat").modal("hide")

                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });

                            Toast.fire({
                                icon: "success",
                                title: "Status persetujuan berhasil diubah!"
                            });
                        },
                        complete: function() {
                            // Mengembalikan teks tombol dan menyembunyikan loading
                            $("#btnSimpanText").show();
                            $("#btnSimpanLoading").hide();
                        },
                        error: function(xhr, status, error) {
                            // Tangani kesalahan jika terjadi
                            console.error('Error:', error);
                            $("#btnSimpanText").show();
                            $("#btnSimpanLoading").hide();
                        }
                    });
                }
            }

            function btnRating() {
                let selectedRating = $('input[name="rating1"]:checked').val();
                let review = $("#komentar_rating").val()
                let id_pesanan_jadwal_alat = $('#id_pesanan_jadwal_alat1').val()

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
                    url: `{{ url('/beri_rating_alat/${id_pesanan_jadwal_alat}') }}`,
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(data),
                    success: function(result) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Terimakasih atas review anda.",
                            icon: "success"
                        });

                        $('#tableJadwalAlat').DataTable().ajax.reload()
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
