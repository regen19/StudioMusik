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

                    <div class="form-group">
                        <label for="tgl_deadline">Tanggal Deadline Pesanan</label>
                        <input type="date" class="form-control" name="tgl_deadline" id="tgl_deadline" required>
                    </div>
                    <div class="form-group row">
                        <div class="col-7">
                            <label for="nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" name="nama_peminjam" id="nama_peminjam"
                                value="{{ Auth::user()->username }}" readonly required>
                        </div>
                        <div class="col-5">
                            <label for="no_wa">Nomor WhatsApp</label>
                            <input type="number" class="form-control" name="no_wa" id="no_wa"
                                value="{{ Auth::user()->no_wa }}" readonly required>
                        </div>

                        <input type="hidden" class="form-control" name="id_user" id="id_user"
                            value="{{ Auth::user()->id_user }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="id_jasa_musik">Jenis Jasa Musik</label>
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
                        <label for="keterangan">Keterangan</label>
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

{{-- EDIT JASA MUSIK --}}
{{-- <div class="modal fade" id="edit_jasa_musik" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Pesanan Jasa Musik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_tgl_deadline">Tanggal Deadline Pesanan</label>
                        <input type="date" class="form-control" name="edit_tgl_deadline" id="edit_tgl_deadline"
                            required>
                    </div>
                    <div class="form-group row">
                        <div class="col-7">
                            <label for="edit_nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" name="edit_nama_peminjam"
                                id="edit_nama_peminjam" value="{{ Auth::user()->username }}" readonly required>
                        </div>
                        <div class="col-5">
                            <label for="edit_no_wa">Nomor WhatsApp</label>
                            <input type="number" class="form-control" name="edit_no_wa" id="edit_no_wa"
                                value="{{ Auth::user()->no_wa }}" readonly required>
                        </div>
                        <input type="hidden" class="form-control" name="edit_id_user" id="edit_id_user"
                            value="{{ Auth::user()->id_user }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_jasa_musik">Jenis Jasa Musik</label>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-select" id="edit_id_jasa_musik">
                                    <option selected disabled>Pilih Jasa Musik</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="edit_containerInputJasaMusik">
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea class="form-control" name="edit_keterangan" id="edit_keterangan" cols="30" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="edit_btnSimpanText"
                        onclick="edit_btnSimpan()">Simpan</button>
                    <span id="edit_btnSimpanLoading" style="display:none;">
                        <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." style="width:20px;" />
                    </span>
                </div>
            </form>
        </div>
    </div>
</div> --}}

@push('script')
    <script>
        $(document).ready(function() {
            list_jasa_musik();
            event_list_jasa_musik();

            var today = new Date().toISOString().split('T')[0];
            $('#tgl_deadline').attr('min', today);
        })


        async function event_list_jasa_musik() {
            $("#id_jasa_musik").on('change', function() {
                $("#containerInputJasaMusik").empty();
                var selectedValue = $(this).val();
                $.ajax({
                    url: `{{ url('/informasi_jasa_musik/${selectedValue}') }}`,
                    method: 'get',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(key, value) {
                            $("#containerInputJasaMusik").append(
                                `
                                <div class="form-group">
                                    <label for="${value.nama_field}">${value.nama_field}</label>
                                    ${value.jenis_field=="text"?
                                    `<textarea type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}"></textarea>`
                                    :
                                    `<input type="${value.jenis_field}" class="form-control informasi" name="${value.nama_field}" id="${value.nama_field}" >`
                                    }

                                </div>
                                `
                            )
                        })
                    }
                });
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

        function btnSimpan() {
            $("#btnSimpanText").hide();
            $("#btnSimpanLoading").show();

            let id_jasa_musik = $('#id_jasa_musik').val();
            let tgl_deadline = $('#tgl_deadline').val();
            let id_user = "{{ Auth::user()->id_user }}";
            let no_wa = $('#no_wa').val();
            let keterangan = $('#keterangan').val();
            let informasi = [];
            let formData = new FormData();
            formData.append("id_jasa_musik", id_jasa_musik);
            formData.append("tgl_deadline", tgl_deadline);
            formData.append("id_user", id_user);
            formData.append("no_wa", no_wa);
            formData.append("keterangan", keterangan);
            formData.append("_token", "{{ csrf_token() }}");
            $("#containerInputJasaMusik .informasi").each(function(index) {
                let namaField = $(this).attr("name");
                let tipeField = $(this).attr("type");
                let nilaiField = $(this).val();
                if (tipeField === 'file') {
                    // Append file ke formData
                    formData.append(`informasi[${index}][file]`, $(this)[0].files[0]);
                    formData.append(`informasi[${index}][nama_field]`, namaField);
                    formData.append(`informasi[${index}][tipe_field]`, tipeField);
                } else {
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

            if (!id_jasa_musik || !tgl_deadline || !id_user || !no_wa || !keterangan || informasi.length ==
                0) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });

                $("#btnSimpanText").show();
                $("#btnSimpanLoading").hide();
                return
            }

            $.ajax({
                url: "{{ url('/add_pesanan_jasa_musik') }}",
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

        // FORM EDIT
        function openModal(action, id_pesanan_jasa_musik) {
            $("#add_jasa_musik").modal("show");

            const $title_header = $("#title_header");
            const $btnSimpanText = $("#btnSimpanText");

            if (action === 'add') {
                $title_header.text("Tambah Pesanan Jasa Musik");
                $btnSimpanText.text("Simpan");

                $btnSimpanText.off('click').on("click", function() {
                    saveJasaMusik("add", id_pesanan_jasa_musik);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Pesanan Jasa Musik");
                $btnSimpanText.text("Ubah");

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
                    // console.log(response)
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
                            $("#id_jasa_musik").empty()
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

                    // INFORMASI 
                    $.each(response.jasa_informasi, function(key, value) {
                        console.log(value)
                        $("#containerInputJasaMusik").append(
                            `
                                <div class="form-group">
                                    <label for="${value.nama_field}">${value.nama_field}</label>
                                    ${value.tipe_field=="text"?
                                    `<textarea type="${value.tipe_field}" class="form-control informasi" value="${value.value_field}" name="${value.nama_field}" id="${value.nama_field}"></textarea>`
                                    :
                                    `<input type="${value.tipe_field}" class="form-control informasi" value="${value.value_field}" name="${value.nama_field}" id="${value.nama_field}" >`
                                    }
                                </div>
                            `
                        )
                    });

                }
            });
        }

        // function saveLaporan(action, id_laporan) {
        //     const tgl_laporan = $('#tgl_laporan').val();
        //     const jenis_laporan = $('#jenis_laporan').val();
        //     const keterangan = $('#keterangan').val();
        //     const gambarFiles = $('#gambar')[0].files;

        //     if (!tgl_laporan || !jenis_laporan || !keterangan) {
        //         Swal.fire({
        //             title: "Gagal simpan.",
        //             text: "Harap isi semua form!",
        //             icon: "error"
        //         });
        //         return;
        //     }

        //     for (let i = 0; i < gambarFiles.length; i++) {
        //         if (gambarFiles[i].size > 1048576) { // 1MB = 1048576 bytes
        //             Swal.fire({
        //                 title: "Gagal simpan.",
        //                 text: `File ${gambarFiles[i].name} terlalu besar! Maksimal 1MB.`,
        //                 icon: "error"
        //             });
        //             return;
        //         }
        //     }

        //     const formData = new FormData();
        //     formData.append('tgl_laporan', tgl_laporan);
        //     formData.append('jenis_laporan', jenis_laporan);
        //     formData.append('keterangan', keterangan);

        //     // Tambahkan Semua Gambar ke FormData
        //     for (let i = 0; i < gambarFiles.length; i++) {
        //         formData.append('gambar[]', gambarFiles[i]);
        //     }

        //     formData.append('_token', "{{ csrf_token() }}");

        //     const ajaxUrl = action === "add" ? "{{ url('/add_laporan_masalah') }}" :
        //         `{{ url('/edit_laporan_masalah/${id_laporan}') }}`;

        //     $.ajax({
        //         url: ajaxUrl,
        //         method: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             $('#tbLaporan').DataTable().ajax.reload();
        //             $("#add_laporan").modal("hide");

        //             Swal.fire({
        //                 icon: "success",
        //                 title: `${response.msg}`,
        //                 toast: true,
        //                 position: "top-end",
        //                 showConfirmButton: false,
        //                 timer: 1500,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.onmouseenter = Swal.stopTimer;
        //                     toast.onmouseleave = Swal.resumeTimer;
        //                 }
        //             });

        //             setTimeout(() => {
        //                 location.reload()
        //             }, 1500);
        //         },
        //         error: function(xhr, status, error) {
        //             let errorMsg = "";
        //             if (xhr.responseJSON && xhr.responseJSON.msg) {
        //                 for (const [key, value] of Object.entries(xhr.responseJSON.msg)) {
        //                     errorMsg += `${value.join(', ')}\n`;
        //                 }
        //             } else {
        //                 errorMsg = "Terjadi kesalahan saat menghubungi server.";
        //             }
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Oops...',
        //                 text: errorMsg,
        //             });
        //         }
        //     });
        // }
    </script>
@endpush
