@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Jadwal Studio</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i class="bi bi-plus-lg"></i>
            Ajukan Sewa Studio
        </button>
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
                                <th>Nama Peminjam</th>
                                <th>Ruangan</th>
                                <th>Keperluan</th>
                                <th>Status Persetujuan</th>
                                <th>Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    @include('admin.modal_popup.md_add_pesanan_jadwal_studio')
    @include('admin.modal_popup.md_detail_jadwal_studio')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tableJadwalStudio').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_pesanan_jadwal_studio') }}",
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
                            data: 'nama_ruangan',
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
                                        <a type="button" class="badge bg-${color}" onclick="data_status(${data.id_pesanan_jadwal_studio})"
                                            data-bs-toggle="modal" data-bs-target="#status_jadwal_studio">
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

                                // if (data.status_persetujuan === "Y" && data.status_pembayaran === "N") {
                                //     textPersetujuan = "Bayar";
                                //     colorBtn = "info";
                                // }    

                                if (data.status_persetujuan === "P") {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_pesanan_jadwal_studio}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jadwal(${data.id_pesanan_jadwal_studio})"> 
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                } else if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                    "N") {
                                    // && data.status_pembayaran === "Y" &&
                                    return `
                                            <td>
                                                <div style="margin-right: 20px;">
                                                    <button type="button" class="btn btn-success icon icon-left text-white" onclick="PengembalianRuangan(${data.id_pesanan_jadwal_studio})">
                                                        Pengembalian
                                                    </button>

                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                        ${textPersetujuan}
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                } else {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_studio" onclick="show_byID(${data.id_pesanan_jadwal_studio})">
                                                    ${textPersetujuan}
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

            function hapus_jadwal(id_pesanan_jadwal_studio) {
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
                            url: `{{ url('/hapus_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
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

                                $('#tableJadwalStudio').DataTable().ajax.reload()

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

    <div class="modal fade" id="status_jadwal_studio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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

                        <input type="hidden" id="id_pesanan_jadwal_studio">
                    </div>

                    <div class="form-group" style="display: none" id="ket_admin">
                        <label for="keterangan_admin">Keterangan Admin</label>
                        <textarea class="form-control" name="keterangan_admin" id="keterangan_admin" cols="30" rows="7" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="btnStatusSetuju()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function cek_setuju() {
                let selectedValue = $('#status_persetujuan').val()

                if (selectedValue != "P") {
                    $('#ket_admin').show();
                } else {
                    $('#ket_admin').hide();
                }
            }

            function data_status(id_pesanan_jadwal_studio, data) {
                $.ajax({
                    url: `{{ url('/showById_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#id_pesanan_jadwal_studio").val(id_pesanan_jadwal_studio)
                        $('#status_persetujuan').val(response.status_persetujuan)

                        if (response.ket_admin) {
                            $("#ket_admin").show()
                            $('#keterangan_admin').val(response.ket_admin)
                        }
                    }
                });
            }

            function btnStatusSetuju() {
                let status_persetujuan = $('#status_persetujuan').val()
                let keterangan_admin = $('#keterangan_admin').val()
                let id_pesanan_jadwal_studio = $('#id_pesanan_jadwal_studio').val()

                $.ajax({
                    url: `{{ url('/status_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
                    method: 'post',
                    data: {
                        "status_persetujuan": status_persetujuan,
                        "ket_admin": keterangan_admin,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#tableJadwalStudio').DataTable().ajax.reload()
                        $("#status_jadwal_studio").modal("hide")

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
                    }
                });
            }
        </script>
    @endpush
@endsection
