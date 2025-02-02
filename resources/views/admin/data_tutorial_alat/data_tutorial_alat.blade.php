@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Data Tutorial Alat</h3>
    </div>

    <div class="mb-3">
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i class="bi bi-plus-lg"></i>
            Tambah Alat
        </button>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tbTutorial">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Gambar</th>
                                <th>Nama Alat</th>
                                <th>Deskripsi</th>
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

    @include('admin.data_tutorial_alat.md_add_tutorial_alat')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tbTutorial').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    ajax: {
                        url: "{{ url('/fetch_tutorial_alat') }}",
                        type: 'GET',
                    },
                    columns: [{
                            className: 'text-center',
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'nama_alat',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `<a target="_blank" href="{{ asset('storage/img_upload/tutorial_alat') }}/${data.gambar_alat}"><img src="{{ asset('storage/img_upload/tutorial_alat') }}/${data.gambar_alat}" class="img-thumbnail" style="width:100px; height:100px"></a>`;
                            }
                        },
                        {
                            data: 'deskripsi',
                            render: function(data) {
                                var div = document.createElement("div");
                                div.innerHTML = data;
                                return div.textContent || div.innerText || "";
                            }
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_tutorial}')">
                                                    <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_tutorial(${data.id_tutorial})">
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

            function hapus_tutorial(id_tutorial) {
                Swal.fire({
                    title: "Apakah ada yakin hapus?",
                    text: "Data tutorial alat akan terhapus.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `{{ url('/hapus_tutorial_alat/${id_tutorial}') }}`,
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            method: 'delete',
                            success: function(response) {
                                Swal.fire({
                                    title: "Dihapus!",
                                    text: "Data tutorial alat telah dihapus.",
                                    icon: "success"
                                });

                                $('#tbTutorial').DataTable().ajax.reload()

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
