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

                <label for="data_jasa_musik">Informasi Jasa Musik</label>
                <div id="inputContainer">

                </div>

                <button class="btn btn-success mb-4" id="addButton">Tambah Informasi</button>
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
                    <label for="data_jasa_musik">Informasi Jasa Musik</label>
                    <div id="up_inputContainer">

                    </div>

                    <button class="btn btn-success mb-4" id="up_addButton">Tambah Informasi</button>
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
    {{-- form informasi jasa musik --}}
    <script>
        $('#addButton').on('click', function(event) {
            event.preventDefault();

            // Container untuk input baru
            var $inputContainer = $('#inputContainer').append(`
                <div class="inputWrapper">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <select class="form-select jenis_form_jasa">
                                <option value="text">Text</option>
                                <option value="number">Angka</option>
                                <option value="file">File</option>
                            </select>
                        </div>
                        <input type="text" class="form-control form_jasa">
                        <button class="btn btn-danger removeButton" type="button">Hapus</button>
                    </div>
                </div>
            `);
        });
        $('#up_addButton').on('click', function(event) {
            event.preventDefault();

            // Container untuk input baru
            var $inputContainer = $('#up_inputContainer').append(`
                <div class="inputWrapper">
                    <div class="input-group mb-3">
                        <div class="input-group-text">
                            <select class="form-select jenis_form_jasa">
                                <option value="text">Text</option>
                                <option value="number">Angka</option>
                                <option value="file">File</option>
                            </select>
                        </div>
                        <input type="text" class="form-control form_jasa">
                        <button class="btn btn-danger removeButton" type="button">Hapus</button>
                    </div>
                </div>
            `);
        });
        // Event delegation untuk tombol hapus
        $('#inputContainer').on('click', '.removeButton', function() {
            $(this).closest('.inputWrapper').remove();
        });
        $('#up_inputContainer').on('click', '.removeButton', function() {
            $(this).closest('.inputWrapper').remove();
        });
    </script>

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
            let informasi_jasa_musik = [];
            $('#inputContainer .inputWrapper').each(function() {
                const jenisField = $(this).find('.jenis_form_jasa').val(); // Nilai dari select
                const namaField = $(this).find('.form_jasa').val();       // Nilai dari input

                informasi_jasa_musik.push({
                    jenis_field: jenisField,
                    nama_field: namaField
                });
            });

            let formData = new FormData();
            formData.append('nama_jenis_jasa', nama_jenis_jasa);
            formData.append('informasi_jasa_musik', JSON.stringify(informasi_jasa_musik));
            formData.append('sk', sk);
            formData.append('deskripsi', deskripsi);
            formData.append('gambar', gambar);
            formData.append('_token', "{{ csrf_token() }}")

            if (!nama_jenis_jasa || !sk || !deskripsi || !gambar || informasi_jasa_musik.length == 0) {
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
                    $('#inputContainer').empty();
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
                    $('#up_inputContainer').empty();
                    response.form_jasa.forEach((field) => {
                        $('#up_addButton').trigger('click');
                        $('#up_inputContainer .inputWrapper:last .form_jasa').val(field.nama_field);
                        $('#up_inputContainer .inputWrapper:last .jenis_form_jasa').val(field
                            .jenis_field);
                    });
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
            let gambar = $("#up_gambar")[0].files[0];
            let informasi_jasa_musik = [];
            $('#up_inputContainer .inputWrapper').each(function() {
                const jenisField = $(this).find('.jenis_form_jasa').val(); // Nilai dari select
                const namaField = $(this).find('.form_jasa').val();       // Nilai dari input

                informasi_jasa_musik.push({
                    jenis_field: jenisField,
                    nama_field: namaField
                });
            });

            if (!nama_jenis_jasa || !sk || !deskripsi || informasi_jasa_musik.length == 0) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return
            }

            let updateData = new FormData();
            updateData.append('id_jasa_musik', id_jasa_musik);
            updateData.append('nama_jenis_jasa', nama_jenis_jasa);
            updateData.append('informasi_jasa_musik', JSON.stringify(informasi_jasa_musik));
            updateData.append('sk', sk);
            updateData.append('deskripsi', deskripsi);
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
                    $('#up_inputContainer').empty();
                    Toast.fire({
                        icon: "success",
                        title: "Data Berhasil Diubah!"
                    });
                }
            });
        })
    </script> 
@endpush
