@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Riwayat Jadwal Produksi Jasa Musik</h3>
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
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>


    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbPesanan').DataTable({
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
                        url: "{{ url('/riwayat_pesanan_data') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'tgl_produksi',
                            render: function(data) {

                                return data ? data.split(" ")[0] : `<div>
                                <a type="button" class="badge bg-warning">
                                    Diajukan
                            </a> <div>`
                            }
                        },
                        {
                            data: 'tenggat_produksi',
                            render: function(data) {
                                return data.split(" ")[0]
                            }
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
                    ],
                });
            })
        </script>
    @endpush
@endsection
