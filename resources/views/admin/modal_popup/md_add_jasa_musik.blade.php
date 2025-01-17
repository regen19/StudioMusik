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

                <small class="text-danger">URUTAN PENAMBAHAN JASA MUSIK</small> <br>
                <small class="text-danger fst-italic">1. Jingle</small><br>
                <small class="text-danger fst-italic">2. Aransemen</small><br>
                <small class="text-danger fst-italic">3. Minus One</small><br>
                <div class="form-group">
                    <label for="id_jenis_jasa">Jenis Jasa</label>
                    <div class="form-group row">
                        <div class="col-lg-11 col-10">
                            <select class="form-select" id="id_jenis_jasa">
                                <option selected disabled>Pilih Jenis</option>
                            </select>
                        </div>
                        <div class="col-lg-1 col-2">
                            <button type="button" class="btn btn-primary icon icon-left" data-bs-toggle="modal"
                                data-bs-target="#add_jenis_jasa"><i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
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
                        <label for="up_jenis_jasa">Jenis Jasa</label>
                        <select class="form-select" id="up_jenis_jasa">
                        </select>
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

{{-- MASTER JENIS JASA --}}
<div class="modal fade" id="add_jenis_jasa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="master_jenis" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="master_jenis">Tambah Master Jenis Jasa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_jenis_jasa">Nama Jenis Jasa <small class="text-danger fst-italic">jingle,
                            aransemen, minus one</small></label>
                    <div class="form-group row">
                        <div class="col-lg-10 col-9">
                            <input type="text" class="form-control" id="nama_jenis_jasa">
                        </div>
                        <div class="col-lg-2 col-3">
                            <button type="button" class="btn btn-primary" id="addJenisJasa"
                                onclick="addJenisJasa()">Simpan
                            </button>
                            <button type="button" class="btn btn-info text-white" style="display: none"
                                id="editJenisJasa">Edit
                            </button>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="my-3 table-responsive">
                    <table class="table" id="tableJenisJasa" width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Jenis Jasa</th>
                                <th>Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href=""><button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Kembali</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')
    {{-- MASTER JASA MUSIK --}}
    <script>
        $(document).ready(function() {
            list_jenis_jasa()


            $('#add_jasa').on('shown.bs.modal', function() {
                var element = document.getElementById('id_jenis_jasa');
                var choices = new Choices(element);
            });

            $('#edit_jasa').on('shown.bs.modal', function() {
                var element = document.getElementById('up_jenis_jasa');
                var choices = new Choices(element);
            });
        })

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
            let id_jenis_jasa = $('#id_jenis_jasa').val();
            let sk = $('#sk').val();
            let deskripsi = $('#deskripsi').val();
            let gambar = $("#gambar")[0].files[0];

            let formData = new FormData();
            formData.append('id_jenis_jasa', id_jenis_jasa);
            formData.append('sk', sk);
            formData.append('deskripsi', deskripsi);
            formData.append('gambar', gambar);
            formData.append('_token', "{{ csrf_token() }}")

            if (!id_jenis_jasa || !sk || !deskripsi || !gambar) {
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

                    $('#id_jenis_jasa').val("");
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
                    $('#id_jasa_musik').val(response.id_jasa_musik);
                    $('#up_sk').val(response.sk);
                    $('#up_deskripsi').val(response.deskripsi);
                    $('#up_output').attr('src', '{{ asset('storage/img_upload/') }}/' + response.gambar);
                    $('#up_output').show();
                    $("#up_jenis_jasa").empty();

                    $.ajax({
                        url: `{{ url('/fetch_master_jenis_jasa') }}`,
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(response2) {

                            let list_jenis = response2.data;

                            $.each(list_jenis, function(key, value) {
                                $("#up_jenis_jasa").append(
                                    `<option value="${value.id_jenis_jasa}" selected>${value.nama_jenis_jasa}</option>`
                                );
                            });
                            $('#up_jenis_jasa').val(response.id_jenis_jasa);
                        }
                    });
                }
            });
        }

        function list_jenis_jasa() {
            $.ajax({
                url: `{{ url('/fetch_master_jenis_jasa') }}`,
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    let list_jenis = response.data;

                    $.each(list_jenis, function(key, value) {
                        $("#id_jenis_jasa").append(
                            `<option value="${value.id_jenis_jasa}">${value.nama_jenis_jasa}</option>`
                        )
                    })
                }
            });
        }

        // EDIT JASA
        $(document).on('submit', '#EditJasaMusik', function(e) {
            e.preventDefault()

            let id_jasa_musik = $('#id_jasa_musik').val();
            let jenisJasa = $('#up_jenis_jasa').val();
            let sk = $('#up_sk').val();
            let deskripsi = $('#up_deskripsi').val();
            // let keterangan = $('#up_keterangan').val();
            // let biaya_produksi = $('#up_biaya_produksi').val();
            let gambar = $("#up_gambar")[0].files[0];

            let updateData = new FormData();
            updateData.append('id_jasa_musik', id_jasa_musik);
            updateData.append('id_jenis_jasa', jenisJasa);
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
