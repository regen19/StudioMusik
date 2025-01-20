@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Daftar Jasa Musik</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" data-bs-toggle="modal" data-bs-target="#add_jasa"><i
                class="bi bi-plus-lg"></i> Tambah Jasa Musik
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableJasaMusik">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Jenis Jasa</th>
                                <th>Syarat & Ketentuan</th>
                                <th>Deskripsi</th>
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


    @include('admin.modal_popup.md_add_jasa_musik')

    @push('script')
        <script>
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });

            $(document).ready(function() {
                $('#tableJasaMusik').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_master_jasa_musik') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'nama_jenis_jasa',
                        },
                        {
                            data: 'sk',
                        },
                        {
                            data: 'deskripsi',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `<a target="_blank" href="{{ asset('storage/img_upload/jasa_musik') }}/${data.gambar}"><img src="{{ asset('storage/img_upload/jasa_musik') }}/${data.gambar}" class="img-thumbnail" max-width="100px" max-height="120px"></a>`;
                            }
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-info icon icon-left text-white"
                                                data-bs-toggle="modal" data-bs-target="#edit_jasa" onclick="show_byId_jasa(${data.id_jasa_musik})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jasa(${data.id_jasa_musik})"> 
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

            function hapus_jasa(id_jasa_musik) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data Jasa Musik akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_master_jasa_musik/${id_jasa_musik}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data jasa musik telah dihapus.",
                                    icon: "success"
                                });

                                $('#tableJasaMusik').DataTable().ajax.reload()

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
