@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Alat Dipinjam</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        @if ($cek_pesanan)
            <button type="button" class="btn btn-primary icon icon-left" onclick="btnJadwalGagal()"><i
                    class="bi bi-plus-lg"></i>
                Pinjam Alat
            </button>
        @else
            <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i
                    class="bi bi-plus-lg"></i>
                Pinjam Alat
            </button>
        @endif
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableJadwalAlat">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Foto Alat</th>
                                <th>Nama Alat</th>
                                <th>Jumlah Alat</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Dikembalikan</th>
                                
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @include('user.jadwal_alat_usr.md_add_pinjam_alat_usr')

    @push('script')
        <script>
            $(document).ready(function() {
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
                        url: "{{ url('/fetch_alat_dipinjam') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `<a target="_blank" href="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}"><img src="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}" class="foto_alat" max-width="90px" max-height="60px"></a>`;
                            }
                        },
    
                        {
                            data: 'nama_alat',
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'tgl_pinjam'
                        },
                        {
                            data: 'tgl_kembali'
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
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Beri Rating
                                                </button>
                                            </div>

                                             <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
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
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                }

                                // <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_pesanan_pinjam_alat}')">
                                //                     <i class="bi bi-pencil-square"></i>
                                // </button>

                                if (data.status_persetujuan === "P" && data.status_pengajuan !== "X") {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                            
                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jadwal(${data.id_pesanan_pinjam_alat})">
                                                    Batalkan
                                                </button>

                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
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
                                                    <button type="button" class="btn btn-warning icon icon-left text-white" onclick="PengembalianAlat(${data.id_pesanan_pinjam_alat})">
                                                        Pengembalian
                                                    </button>

                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
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
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
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


        </script>
        
    @endpush

        

   
@endsection
