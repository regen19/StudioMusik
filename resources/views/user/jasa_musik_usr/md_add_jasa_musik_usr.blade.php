<div class="modal fade" id="add_jasa_musik" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-group row">
                        <div class="col-7">
                            <label for="nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" name="nama_peminjam" id="nama_peminjam"
                                value="{{ Auth::user()->username }}" disabled required>
                        </div>
                        <div class="col-5">
                            <label for="no_wa">Nomor WhatsApp</label>
                            <input type="number" class="form-control" name="no_wa" id="no_wa"
                                value="{{ Auth::user()->no_wa }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label for="tgl_deadline">Tanggal Deadline Pesanan</label>
                            <input type="date" class="form-control" name="tgl_deadline" id="tgl_deadline" required>
                        </div>
                        <input type="hidden" class="form-control" name="id_user" id="id_user"
                            value="{{ Auth::user()->id_user }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="id_jasa_musik">Jenis Jasa Musik<small
                                class="text-danger fst-italic">*</small></label>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-select" id="id_jasa_musik">
                                    <option selected disabled>Pilih Jasa Musik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="containerInputJasaMusik">

                    </div>

                    <div class="form-group">
                        <label for="keterangan">Keterangan<small class="text-danger fst-italic">*</small></label>
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSimpanText">Simpan</button>
                    <span id="btnSimpanLoading" style="display:none;">
                        <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." style="width:20px;" />
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            list_jasa_musik();
            event_list_jasa_musik();

            const today = new Date();


            // Hitung tanggal min: besok
            const minDateObj = new Date(today);
            minDateObj.setDate(minDateObj.getDate() + 1);

            // Hitung tanggal max: 3 bulan dari hari ini
            const maxDateObj = new Date(today);
            maxDateObj.setMonth(maxDateObj.getMonth() + 3);

            // Fungsi bantu untuk format YYYY-MM-DD
            const formatDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            // Set atribut min dan max pada input
            // Set atribut min dan max pada input
            $('#tgl_deadline').attr('min', formatDate(minDateObj));
            $('#tgl_deadline').attr('max', formatDate(maxDateObj));

        })


        async function event_list_jasa_musik() {
            $("#id_jasa_musik").on('change', function() {
                $("#containerInputJasaMusik").empty();
                var selectedValue = $(this).val();
                var kondisiAction = $("#btnSimpanText");
                var id_pesanan = $("#btnSimpanText").data("id_pesanan");

                if (kondisiAction.text() == "Ubah") {
                    $.ajax({
                        url: `{{ url('/showById_pesanan_jasa_musik/${id_pesanan}') }}`,
                        method: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(response) {

                            $("#containerInputJasaMusik").empty();
                            if (response.id_jasa_musik == selectedValue) {
                                $.each(response.jasa_informasi, function(key, value) {

                                    $("#containerInputJasaMusik").append(
                                        `
                                            <div class="form-group">
                                                <label for="input_${value.id}">${value.nama_field}</label>
                                                ${value.tipe_field == "text" ?
                                                `<textarea type="${value.tipe_field}" class="form-control informasi" name="${value.nama_field}" id="input_${value.id}">${value.value_field}</textarea>`
                                                : value.tipe_field == "number" ?
                                                `<input type="${value.tipe_field}" class="form-control informasi" value="${value.value_field}" name="${value.nama_field}" id="input_${value.id}" >`
                                                : value.tipe_field == "file" ?
                                                `<input type="${value.tipe_field}" class="form-control informasi" name="${value.nama_field}" id="input_${value.id}" data-file-url="${value.value_field}" accept=".mp3, .mp4, .wav">`
                                                : ""
                                                }
                                            </div>
                                        `
                                    );
                                    if (value.tipe_field == "file") {
                                        // Menambahkan tautan untuk file yang di-upload
                                        var fileUrl = value.value_field;
                                        $("#containerInputJasaMusik").append(

                                            `<a href="{{ asset('storage/pesanan/jasa_musik_file') }}/${fileUrl}" target="_blank">Lihat File</a>`
                                        );
                                    }
                                });
                            } else {
                                $.ajax({
                                    url: `{{ url('/informasi_jasa_musik/${selectedValue}') }}`,
                                    method: 'get',
                                    data: {
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        $("#containerInputJasaMusik").empty();
                                        $.each(response, function(key, value) {
                                            $("#containerInputJasaMusik")
                                                .append(
                                                    `
                                                    <div class="form-group">
                                                        <label for="${value.nama_field}">${value.nama_field}</label>
                                                        ${value.jenis_field=="text"?
                                                        `<textarea type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}"></textarea>`
                                                        :
                                                        `<input type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}" ${value.jenis_field === "file" ? 'accept=".mp3, .mp4, .wav"' : ""}>`
                                                        }

                                                    </div>
                                                    `
                                                )
                                        })
                                    }
                                });
                            }


                        }
                    });
                } else {
                    $.ajax({
                        url: `{{ url('/informasi_jasa_musik/${selectedValue}') }}`,
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(response) {
                            $("#containerInputJasaMusik").empty();
                            $.each(response, function(key, value) {
                                $("#containerInputJasaMusik").append(
                                    `
                                <div class="form-group">
                                    <label for="${value.nama_field}">${value.nama_field}</label>
                                    ${value.jenis_field=="text"?
                                    `<textarea type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}"></textarea>`
                                    :
                                    `<input type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}" ${value.jenis_field === "file" ? 'accept=".mp3, .mp4, .wav"' :"" }>`
                                    }

                                </div>
                                `
                                )
                            })
                        }
                    });
                }

            });

            $('#add_jasa_musik').on('hidden.bs.modal', function() {
                $('#id_jasa_musik').val("");
                $('#tgl_deadline').val("");
                $('#keterangan').val("");
                $("#containerInputJasaMusik").empty();
            });
        }

        function list_jasa_musik() {
            $.ajax({
                url: `{{ url('/list_data_jasa_musik') }}`,
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(key, value) {
                        $("#id_jasa_musik").append(
                            `<option value="${value.id_jasa_musik}">${value.nama_jenis_jasa}</option>`
                        )
                    })
                }
            });
        }

        // FORM EDIT
        function openModal(action, id_pesanan_jasa_musik) {
            $("#add_jasa_musik").modal("show");

            const $title_header = $("#title_header");
            const $btnSimpanText = $("#btnSimpanText");

            if (action === 'add') {
                $title_header.text("Tambah Pesanan Jasa Musik");
                $btnSimpanText.text("Simpan").removeData("id_pesanan");

                $('#tgl_deadline').val("");
                $('#keterangan').val("");
                $("#containerInputJasaMusik").empty();

                $("#containerInputJasaMusik").empty();


                $btnSimpanText.off('click').on("click", function() {
                    saveJasaMusik("add", id_pesanan_jasa_musik);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Pesanan Jasa Musik");
                $btnSimpanText.text("Ubah").data("id_pesanan", id_pesanan_jasa_musik);
                $("#containerInputJasaMusik").empty();

                show_byid_jasa_musik(id_pesanan_jasa_musik);

                $btnSimpanText.off('click').on("click", function() {
                    saveJasaMusik("edit", id_pesanan_jasa_musik);
                });
            }
        }

        function show_byid_jasa_musik(id_pesanan_jasa_musik) {
            $.ajax({
                url: `{{ url('/showById_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $("#tgl_deadline").val(response.tenggat_produksi)
                    $("#keterangan").val(response.keterangan)

                    // LIST JASA MUSIK
                    $.ajax({
                        url: `{{ url('/list_data_jasa_musik') }}`,
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(listResponse) {
                            $("#id_jasa_musik").empty();

                            $.each(listResponse, function(key, value) {
                                var selected = response.id_jasa_musik === value
                                    .id_jasa_musik ? 'selected' : '';
                                $("#id_jasa_musik").append(
                                    `<option value="${value.id_jasa_musik}" ${selected}>${value.nama_jenis_jasa}</option>`
                                );
                            });

                            $("#id_jasa_musik").val(response.id_jasa_musik).change();
                        }
                    });

                }
            });
        }

        function saveJasaMusik(action, id_pesanan_jasa_musik) {
            $("#btnSimpanText").hide();
            $("#btnSimpanLoading").show();

            let id_jasa_musik = $('#id_jasa_musik').val();
            let tenggat_produksi = $('#tgl_deadline').val();
            let id_user = "{{ Auth::user()->id_user }}";
            let no_wa = $('#no_wa').val();
            let keterangan = $('#keterangan').val();
            let informasi = [];
            let formData = new FormData();
            let errorMessages = [];
            formData.append("id_jasa_musik", id_jasa_musik);
            formData.append("tenggat_produksi", tenggat_produksi);
            formData.append("id_user", id_user);
            formData.append("no_wa", no_wa);
            formData.append("keterangan", keterangan);
            formData.append("_token", "{{ csrf_token() }}");
            // Validasi input utama
            if (!tenggat_produksi) errorMessages.push("Tenggat produksi wajib diisi!");
            if (!id_jasa_musik) errorMessages.push("Pilih jasa musik!");
            $("#containerInputJasaMusik .informasi").each(function(index) {
                let namaField = $(this).attr("name");
                let tipeField = $(this).attr("type");
                let nilaiField = $(this).val();
                if (tipeField === 'file') {
                    let file = $(this)[0].files[0];

                    const allowedTypes = ['video/mp4', 'audio/mpeg', 'audio/wav',
                    'audio/x-wav']; // tipe MIME yang diperbolehkan

                    if (!file && action === "add") {
                        errorMessages.push(`File untuk "${namaField}" wajib diunggah!`);
                    } else if (file && !allowedTypes.includes(file.type)) {
                        errorMessages.push(
                            `Format file untuk "${namaField}" tidak didukung! (Hanya .mp3, .mp4, .wav)`);
                    } else {
                        // Hanya append jika file valid
                        formData.append(`informasi[${index}][file]`, file);
                    }

                    formData.append(`informasi[${index}][nama_field]`, namaField);
                    formData.append(`informasi[${index}][tipe_field]`, tipeField);
                } else {
                    if (!nilaiField.trim()) {
                        errorMessages.push(`Kolom "${namaField}" tidak boleh kosong!`);
                    }
                    // Append data non-file
                    formData.append(`informasi[${index}][nama_field]`, namaField);
                    formData.append(`informasi[${index}][value_field]`, nilaiField);
                    formData.append(`informasi[${index}][tipe_field]`, tipeField);
                }
                informasi.push({
                    nama_field: namaField,
                    value_field: nilaiField,
                    tipe_field: tipeField,
                });
            });

            if (!keterangan) errorMessages.push("Keterangan wajib diisi!");
            if (errorMessages.length > 0) {
                Swal.fire({
                    title: "Gagal Simpan",
                    html: errorMessages.join("<br>"),
                    icon: "error"
                });
                $("#btnSimpanText").show();
                $("#btnSimpanLoading").hide();
                return;
            }

            const ajaxUrl = action === "add" ? "{{ url('/add_pesanan_jasa_musik') }}" :
                `{{ url('/edit_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tbPesanan').DataTable().ajax.reload();
                    $("#add_jasa_musik").modal("hide")

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

                    $('#id_jasa_musik').val("");
                    $('#tgl_produksi').val("");
                    $('#keterangan').val("");
                },
                complete: function() {
                    // Mengembalikan teks tombol dan menyembunyikan loading
                    $("#btnSimpanText").show();
                    $("#btnSimpanLoading").hide();
                },
                error: function(xhr, status, error) {
                    $("#btnSimpanText").show();
                    $("#btnSimpanLoading").hide();
                    var errorMsg = "";
                    if (xhr.responseJSON && xhr.responseJSON.msg) {
                        for (const [key, value] of Object.entries(xhr.responseJSON.msg)) {
                            errorMsg += `${value.join(', ')}\n`;
                        }
                    } else {
                        errorMsg = "Terjadi kesalahan saat menghubungi server.";
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMsg,
                    });
                }

            });
        }
    </script>
    <script>
        document.getElementById("tgl_deadline").addEventListener("click", function() {
            this.showPicker(); // Memunculkan datepicker secara otomatis
        });
    </script>
@endpush
