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
            Buat Ruangan
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbDataRuangan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Thumbnail</th>
                                <th>Nama Ruangan</th>
                                {{-- <th>Biaya Perawatan</th> --}}
                                <th>Kapasitas</th>
                                <th>Lokasi</th>
                                <th>Fasilitas</th>
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

    @include('admin.data_ruangan.modal_data_ruangan.md_add_data_ruangan')

    @push('script')
        <script>
            function formatRupiah(angka) {
                var reverse = angka.toString().split('').reverse().join('');
                var ribuan = reverse.match(/\d{1,3}/g);
                var formatted = ribuan.join('.').split('').reverse().join('');
                return 'Rp' + formatted;
            }

            $(document).ready(function() {
                $('#tbDataRuangan').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_data_ruangan') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `<a target="_blank" href="{{ asset('storage/img_upload/data_ruangan') }}/${data.thumbnail}"><img src="{{ asset('storage/img_upload/data_ruangan') }}/${data.thumbnail}" class="img-thumbnail" max-width="90px" max-height="60px"></a>`;
                            }
                        },
                        {
                            data: 'nama_ruangan'
                        },
                        // {
                        //     data: 'harga_sewa',
                        //     render: function(data) {
                        //         return formatRupiah(data);
                        //     }
                        // },
                        {
                            data: 'kapasitas',
                        },
                        {
                            data: 'lokasi',
                        },
                        {
                            data: 'fasilitas',
                        },
                        {
                            title: 'Menu',
                            data: null,
                            render: function(data) {
                                return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_ruangan}')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_ruangan(${data.id_ruangan})"> 
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

            function hapus_ruangan(id_ruangan) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data Ruangan akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_data_ruangan/${id_ruangan}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data ruangan telah dihapus.",
                                    icon: "success"
                                });

                                $('#tbDataRuangan').DataTable().ajax.reload()

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
