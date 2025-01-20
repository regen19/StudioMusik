@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Pesanan Jasa Musik</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>
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

    @include('admin.modal_popup.md_detail_pesanan_jasa_musik')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbPesanan').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_pesanan_jasa_musik') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'tgl_produksi'
                        },
                        {
                            data: 'tenggat_produksi',

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
                                        <a type="button" class="badge bg-${color}" onclick="data_status(${data.id_pesanan_jasa_musik})"
                                            data-bs-toggle="modal" data-bs-target="#status_persetujuan">
                                            ${status}
                                        </a>
                                    </div>
                                `;
                            }
                        },
                        // {
                        //     data: null,
                        //     render: function(data) {

                        //         let status = "";
                        //         let color = "";

                        //         if (data.status_pembayaran === "Y") {
                        //             status = "Lunas"
                        //             color = "success"
                        //         } else if (data.status_pembayaran === "N") {
                        //             status = "Belum Bayar"
                        //             color = "warning"
                        //         }

                        //         return `
                //            <div>
                //                 <a type="button" class="badge bg-${color}">
                //                     ${status}
                //                 </a>
                //             </div>
                //         `;
                        //     }
                        // },
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
                                       <a type="button" class="badge bg-${color}" onclick="data_status(${data.id_pesanan_jasa_musik})"
                                            data-bs-toggle="modal" data-bs-target="#status_persetujuan">
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
                                return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-primary icon icon-left text-white"
                                                data-bs-toggle="modal" data-bs-target="#detail_pesanan" onclick="show_byID(${data.id_pesanan_jasa_musik})">
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                `;
                            }
                        }
                    ],
                });
            })
        </script>
    @endpush

    <div class="modal fade" id="status_persetujuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">PERSETUJUAN PESANAN</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_pinjam">Tenggat Produksi</label>
                        <input type="datetime-local" class="form-control" id="tenggat_produksi">
                    </div>

                    <div class="form-group">
                        <label for="tgl_pinjam">Status Persetujuan</label>
                        <select name="stts_setuju" id="stts_setuju" class="form-control" onchange="cek_setuju()">
                            <option value="P">Pengajuan</option>
                            <option value="Y">Disetujui</option>
                            <option value="N">Ditolak</option>
                        </select>

                        <input type="hidden" id="id_pesanan_jasa_musik">
                    </div>

                    <div class="form-group" style="display: none" id="ket_admin">
                        <label for="keterangan_admin">Keterangan Admin</label>
                        <textarea class="form-control" name="keterangan_admin" id="keterangan_admin" cols="30" rows="7" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="tgl_pinjam">Status Produksi</label>
                        <select name="stts_produksi" id="stts_produksi" class="form-control" onchange="cek_setuju()">
                            <option value="P">Diproses</option>
                            <option value="Y">Selesai</option>
                            <option value="N">Diajukan</option>
                        </select>
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
                let selectedValue = $('#stts_setuju').val()

                if (selectedValue != "P") {
                    $('#ket_admin').show();
                } else {
                    $('#ket_admin').hide();
                }
            }

            function data_status(id_pesanan_jasa_musik, data) {
                $.ajax({
                    url: `{{ url('/showById_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $("#id_pesanan_jasa_musik").val(id_pesanan_jasa_musik)
                        $('#stts_setuju').val(response.status_persetujuan)
                        $("#stts_produksi").val(response.status_produksi)
                        $("#keterangan_admin").val(response.keterangan_admin)
                        $("#tenggat_produksi").val(response.tenggat_produksi)
                    }
                });
            }

            function btnStatusSetuju() {
                let status_persetujuan = $('#stts_setuju').val()
                let status_produksi = $('#stts_produksi').val()
                let keterangan_admin = $('#keterangan_admin').val()
                let id_pesanan_jasa_musik = $('#id_pesanan_jasa_musik').val()
                let tenggat_produksi = $("#tenggat_produksi").val()

                $.ajax({
                    url: `{{ url('/status_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
                    method: 'post',
                    data: {
                        "tenggat_produksi": tenggat_produksi,
                        "status_persetujuan": status_persetujuan,
                        "keterangan_admin": keterangan_admin,
                        "status_produksi": status_produksi,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        $('#tbPesanan').DataTable().ajax.reload()
                        $("#status_persetujuan").modal("hide")

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
