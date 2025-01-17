{{-- TAMBAH JASA MUSIK --}}
<div class="modal fade" id="add_jasa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Jasa Musik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_jenis_jasa">Nama Jenis Jasa</label>
                    <input type="text" class="form-control" name="nama_jenis_jasa" id="nama_jenis_jasa">
                </div>
                <div class="form-group">
                    <label for="sk">Syarat & Ketentuan</label>
                    <textarea class="form-control" name="sk" id="sk" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar <small class="text-danger fst-italic">(max: 1
                            mb)</small></small></label>
                    <input type="file" class="image-preview-filepond form-control" id="gambar">

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="addMasterJasaMusik()">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- EDIT JASA MUSIK --}}
<div class="modal fade" id="edit_jasa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Jasa Musik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="EditJasaMusik" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="up_nama_jenis_jasa">Nama Jenis Jasa</label>
                        <input type="text" class="form-control" name="up_nama_jenis_jasa" id="up_nama_jenis_jasa">
                    </div>
                    <div class="form-group">
                        <label for="up_sk">Syarat & Ketentuan</label>
                        <textarea class="form-control" name="up_sk" id="up_sk" cols="30" rows="5"></textarea>
                        <input type="hidden" class="form-control" id="id_jasa_musik">
                    </div>
                    <div class="form-group">
                        <label for="up_deskripsi">Deskripsi</label>
                        <textarea class="form-control" name="up_deskripsi" id="up_deskripsi" cols="30" rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar <small class="text-danger fst-italic">(max: 1
                                mb)</small></label>

                        <input type="file" class="image-preview-filepond form-control" id="up_gambar">

                        <p class="my-3 output"><img id="up_output"
                                style="display: none; max-width: 200px; max-height: 200px;" />
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#gambar").on("change", function() {
            previewImg(this, '#output');
        });

        $("#up_gambar").on("change", function() {
            previewImg(this, '#up_output');
        });

        function previewImg(input, outputId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $(outputId).attr('src', e.target.result);
                    $(outputId).css('display', 'block');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function addMasterJasaMusik() {
            let nama_jenis_jasa = $('#nama_jenis_jasa').val();
            let sk = $('#sk').val();
            let deskripsi = $('#deskripsi').val();
            let gambar = $("#gambar")[0].files[0];

            let formData = new FormData();
            formData.append('nama_jenis_jasa', nama_jenis_jasa);
            formData.append('sk', sk);
            formData.append('deskripsi', deskripsi);
            formData.append('gambar', gambar);
            formData.append('_token', "{{ csrf_token() }}")

            if (!nama_jenis_jasa || !sk || !deskripsi || !gambar) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return
            }

            $.ajax({
                url: "{{ url('/add_master_jasa_musik') }}",
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(response) {
                    $('#tableJasaMusik').DataTable().ajax.reload()
                    $('#add_jasa').modal('hide');

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
                        title: "Data Berhasil Disimpan!"
                    });

                    $('#nama_jenis_jasa').val("");
                    $('#sk').val("");
                    $('#deskripsi').val("");
                    $('#keterangan').val("");
                    $("#gambar").val("");
                    // $('#biaya_produksi').val("");
                }
            });
        }

        function show_byId_jasa(id_jasa_musik) {
            $.ajax({
                url: `{{ url('/showById_master_jasa_musik/${id_jasa_musik}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#id_jasa_musik').val(id_jasa_musik)
                    $('#up_nama_jenis_jasa').val(response.nama_jenis_jasa);
                    $('#up_sk').val(response.sk);
                    $('#up_deskripsi').val(response.deskripsi);
                    $('#up_output').attr('src', '{{ asset('storage/img_upload/jasa_musik') }}/' + response
                        .gambar);
                    $('#up_output').show();
                }
            });
        }

        // EDIT JASA
        $(document).on('submit', '#EditJasaMusik', function(e) {
            e.preventDefault()

            let id_jasa_musik = $('#id_jasa_musik').val();
            let nama_jenis_jasa = $('#up_nama_jenis_jasa').val();
            let sk = $('#up_sk').val();
            let deskripsi = $('#up_deskripsi').val();
            // let keterangan = $('#up_keterangan').val();
            // let biaya_produksi = $('#up_biaya_produksi').val();
            let gambar = $("#up_gambar")[0].files[0];

            let updateData = new FormData();
            updateData.append('id_jasa_musik', id_jasa_musik);
            updateData.append('nama_jenis_jasa', nama_jenis_jasa);
            updateData.append('sk', sk);
            updateData.append('deskripsi', deskripsi);
            // updateData.append('keterangan', keterangan);
            // updateData.append('biaya_produksi', biaya_produksi);
            updateData.append('gambar', gambar);
            updateData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: `{{ url('/edit_master_jasa_musik/${id_jasa_musik}') }}`,
                method: 'post',
                data: updateData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#tableJasaMusik').DataTable().ajax.reload()
                    $('#edit_jasa').modal('hide');

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
                        title: "Data Berhasil Diubah!"
                    });
                }
            });
        })
    </script>

    {{-- MASTER JENIS JASA --}}
    <script>
        $(document).ready(function() {
            $('#tableJenisJasa').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/fetch_master_jenis_jasa') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'nama_jenis_jasa',
                    },
                    {
                        title: 'Menu',
                        data: null,
                        render: function(data) {
                            return `
                                    <td>
                                        <div style="margin-rigth=20px;">
                                            <button type="button" class="btn btn-info icon icon-left text-white" onclick="show_byId_jenisJasa(${data.id_jenis_jasa})">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jenis(${data.id_jenis_jasa})"> 
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                        }
                    }
                ],

                paging: true,
                searching: true
            });
        })

        function addJenisJasa() {
            let nama_jenis_jasa = $('#nama_jenis_jasa').val();

            if (!nama_jenis_jasa) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return
            }

            $.ajax({
                url: "{{ url('/add_master_jenis_jasa') }}",
                method: 'POST',
                data: {
                    "nama_jenis_jasa": nama_jenis_jasa,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#tableJenisJasa').DataTable().ajax.reload()

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
                        title: "Data Berhasil Disimpan!"
                    });

                    $('#nama_jenis_jasa').val("");
                }
            });
        }

        function editJenisJasa(id_jenis_jasa) {
            let nama_jenis_jasa = $('#nama_jenis_jasa').val();

            if (!nama_jenis_jasa) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return
            }

            $.ajax({
                url: `{{ url('/edit_master_jenis_jasa/${id_jenis_jasa}') }}`,
                method: 'put',
                data: {
                    "nama_jenis_jasa": nama_jenis_jasa,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#tableJenisJasa').DataTable().ajax.reload()

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
                        title: "Data Berhasil Disimpan!"
                    });

                    $('#nama_jenis_jasa').val("");
                    $("#addJenisJasa").show();
                    $("#editJenisJasa").hide();
                }
            });
        }

        function show_byId_jenisJasa(id_jenis_jasa) {
            $("#addJenisJasa").hide();
            $("#editJenisJasa").show();

            $("#editJenisJasa").off();
            $("#editJenisJasa").on('click', function() {
                editJenisJasa(id_jenis_jasa);
            })

            $.ajax({
                url: `{{ url('/showById_master_jenis_jasa/${id_jenis_jasa}') }}`,
                method: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#nama_jenis_jasa').val(response.nama_jenis_jasa);
                }
            });
        }

        function hapus_jenis(id_jenis_jasa) {
            Swal.fire({
                title: "Apakah ada yakin hapus?",
                text: "Data Jenis Jasa Musik akan terhapus.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/hapus_master_jenis_jasa/${id_jenis_jasa}') }}`,
                        method: 'delete',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            console.log(response)
                            Swal.fire({
                                title: "Dihapus!",
                                text: "Data jenis jasa musik telah dihapus.",
                                icon: "success"
                            });

                            $('#tableJenisJasa').DataTable().ajax.reload()

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
