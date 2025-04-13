@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Data Alat Studio</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i class="bi bi-plus-lg"></i>
            Buat alat
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbDataAlat">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Foto Alat</th>
                                <th>Nama Alat</th>
                                {{-- <th>Biaya Perawatan</th> --}}
                                <th>Tipe Alat</th>
                                <th>Jumlah</th>
                                <th>Biaya Perawatan</th>
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

    @include('admin.data_alat.modal_data_alat.md_add_data_alat')

    @push('script')
        <script>
            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join('');
                var ribuan = reverse.match(/\d{1,3}/g);
                var formatted = ribuan.join('.').split('').reverse().join('');
                return 'Rp' + formatted;
            }

            $(document).ready(function() {
                $('#tbDataAlat').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_data_alat') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: null,
                            // render: function(data) {
                            //     return `<a target="_blank" href="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}"><img src="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}" class="img-foto_alat" max-width="90px" max-height="60px"></a>`;
                            // }
                        },
                        {
                            data: 'nama_alat'
                        },
                        // {
                        //     data: 'harga_sewa',
                        //     render: function(data) {
                        //         return formatRupiah(data);
                        //     }
                        // },
                        {
                            data: 'tipe_alat',
                            // render: function(data) {
                            //     return `<span>${data}</span>`;
                            // }
                        },
                        {
                            data: 'jumlah_alat',
                            render: function(data) {
                                return `<span>${data}</span>`;
                            }
                        },
                        {
                            data: 'biaya_perawatan',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_alat}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_alat(${data.id_alat})">
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

            function hapus_alat(id_alat) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data alat akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_data_alat/${id_alat}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data alat telah dihapus.",
                                    icon: "success"
                                });

                                $('#tbDataAlat').DataTable().ajax.reload()

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
