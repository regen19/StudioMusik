@extends('partials.main')
@section('MainContent')
    @php
        $segments = Request::segments();
    @endphp
    <div class="page-heading">
        <h3>Paket Jasa Musik <b>{{ $musik->nama_jenis_jasa }}</b></h3>
    </div>

    <div class="mb-3">
        <a href="{{ url('/master_jasa_musik') }}"><button class="btn btn-danger icon icon-left text-white"><i
                    class="bi bi-arrow-left"></i>
                Kembali</button>
        </a>

        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" data-bs-toggle="modal" data-bs-target="#add_paket"><i
                class="bi bi-plus-lg"></i> Tambah Paket
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbPaketHarga">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis Jasa</th>
                                <th>Nama Paket</th>
                                <th>Deskripsi</th>
                                <th>Rincian Paket</th>
                                <th>Harga</th>
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

    @include('admin.jasa_musik.modal_paket_jasa_musik')
    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbPaketHarga').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_paket_harga_jasa_musik') }}",
                        type: 'GET',
                        data: {
                            id_jasa_musik: "{{ $segments[1] }}",
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'nama_jenis_jasa',
                        },
                        {
                            data: 'nama_paket',
                        },
                        {
                            data: 'deskripsi',
                        },
                        {
                            data: 'rincian_paket',
                        },
                        {
                            data: 'biaya_paket',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-info icon icon-left text-white"
                                                data-bs-toggle="modal" data-bs-target="#edit_paket" onclick="show_byId(${data.id_paket_jasa_musik})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_paket(${data.id_paket_jasa_musik})"> 
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

            function hapus_paket(id_paket_jasa_musik) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Paket Jasa Musik akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_paket_harga/${id_paket_jasa_musik}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Paket jasa musik telah dihapus.",
                                    icon: "success"
                                });

                                $('#tbPaketHarga').DataTable().ajax.reload()

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
