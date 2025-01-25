@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Laporan</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" data-bs-toggle="modal" data-bs-target="#add_laporan"><i
                class="bi bi-plus-lg"></i>
            Tambah Laporan
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbLaporan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Jenis Laporan</th>
                                <th>Keterangan</th>
                                <th>Gambar</th>
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


    @include('admin.modal_popup.md_add_laporan_masalah')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbLaporan').DataTable({
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
                        url: "{{ url('/fetch_laporan_masalah') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'tgl_laporan',
                        },
                        {
                            data: 'jenis_laporan',
                        },
                        {
                            data: 'keterangan',
                        },
                        {
                            data: 'gambar',
                            name: 'gambar',
                            render: function(data, type, row, meta) {
                                return data;
                            }
                        },
                        // {
                        //     data: null,
                        //     render: function(data) {
                        //         return `<a target="_blank" href="{{ asset('storage/img_upload') }}/${data.gambar}"><img src="{{ asset('storage/img_upload') }}/${data.gambar}" class="img-thumbnail" max-width="50px" max-height="50px"></a>`;
                        //     }
                        // },
                        {
                            data: null,
                            render: function(data) {
                                return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_laporan(${data.id_laporan})"> 
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                            }
                        }
                    ],
                });
            })

            function hapus_laporan(id_laporan) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data laporan akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_laporan_masalah/${id_laporan}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data laporan telah dihapus.",
                                    icon: "success"
                                });

                                $('#tbLaporan').DataTable().ajax.reload()

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
@endsection
