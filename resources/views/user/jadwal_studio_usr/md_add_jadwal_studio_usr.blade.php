{{-- TAMBAH JASA MUSIK --}}
<div class="modal fade" id="add_jadwal_studio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-7">
                        <label for="id_user">Nama Peminjam</label>
                        <input type="text" class="form-control" name="nama_user" id="nama_user"
                            value="{{ Auth::user()->username }}" readonly required>
                    </div>
                    <div class="col-5">
                        <label for="no_wa">Nomor WhatsApp <small class="text-danger fst-italic"></label>
                        <input type="number" class="form-control" name="no_wa" id="no_wa"
                            value="{{ Auth::user()->no_wa }}" readonly required>
                    </div>


                    <input type="hidden" value="{{ Auth::user()->id_user }}" name="id_user" id="id_user">
                </div>

                <div class="form-group row">
                    <div class="col-12">
                        <label for="id_ruangan">Ruangan Yang Dipinjam</label>
                        <select name="id_ruangan" id="id_ruangan" class="form-control" onchange="selectRuangan()">
                            <option value="">Pilih Ruangan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tgl_pinjam">Tanggal Peminjaman <small class="text-danger fst-italic">*harap pilih
                            ruangan dahulu</small></label>
                    <input type="date" class="form-control" id="tgl_pinjam" required onchange="cek_tanggal_kosong()">
                    <span id="alert_tgl"></span>
                </div>

                <div class="form-group row">
                    <div class="col-6">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <input type="time" class="form-control" id="waktu_mulai" required>
                    </div>
                    <div class="col-6">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <input type="time" class="form-control" id="waktu_selesai" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ket_keperluan">Keperluan Peminjaman</label>
                    <textarea class="form-control" name="ket_keperluan" id="ket_keperluan" cols="30" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="img_jaminan">Jaminan (KTP/KTM) <small class="text-danger fst-italic">(max: 1
                            mb)</small></label>
                    <input type="file" class="image-preview-filepond form-control" id="img_jaminan" required>

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="BtnJadwalStudio">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: `{{ url('list_data_ruangan') }}`,
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {

                    $.each(response, function(key, val) {
                        $("#id_ruangan").append(
                            `<option value="${val.id_ruangan}">${val.nama_ruangan}</option>`
                        )
                    })
                },
            });
        })

        function cek_tanggal_kosong() {
            let tgl_pinjam = $("#tgl_pinjam").val();
            let id_ruangan = $("#id_ruangan").val();

            $.ajax({
                url: `{{ url('cek_tanggal_kosong') }}`,
                method: 'post',
                data: {
                    "tgl_pinjam": tgl_pinjam,
                    "id_ruangan": id_ruangan,
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    if (response.length === 0) {
                        $("#alert_tgl").html(`<small class="text-success fst-italic"><i class="bi bi-check-square"></i> Tanggal tersebut kosong
                        !</small>`);
                    } else {
                        $("#alert_tgl").html(`<small class="text-danger fst-italic"><i
                            class="bi bi-exclamation-triangle-fill"></i> Tanggal tersebut sudah di BOOKING
                        !</small>`);
                    }
                },
                error: function(err) {
                    reject(err);
                }
            });
        }

        function selectRuangan() {
            let selectedOption = $("#id_ruangan option:selected");
            let harga_sewa = selectedOption.data('harga');

            // $("#harga_sewa").val(harga_sewa);
        }

        $("#img_jaminan").on("change", function() {
            previewImg(this, '#output');
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

        function openModal(action, id_pesanan_jadwal_studio = null) {
            $("#add_jadwal_studio").modal("show");

            const $title_header = $("#title_header");
            const $BtnJadwalStudio = $("#BtnJadwalStudio");

            const $id_user = $('#id_user');
            const $id_ruangan = $('#id_ruangan');
            const $tgl_pinjam = $('#tgl_pinjam');
            const $waktu_mulai = $('#waktu_mulai');
            const $waktu_selesai = $('#waktu_selesai');
            const $ket_keperluan = $('#ket_keperluan');
            const $img_jaminan = $('#img_jaminan');
            const $output = $('#output');

            if (action === 'add') {
                $title_header.text("Tambah Pengajuan Jadwal Studio");
                $BtnJadwalStudio.text("Simpan");

                $id_ruangan.val("");
                $tgl_pinjam.val("");
                $waktu_mulai.val("");
                $waktu_selesai.val("");
                $ket_keperluan.val("");
                $img_jaminan.val("");
                $output.hide();

                $BtnJadwalStudio.off('click').on("click", function() {
                    saveJadwalStudio("add", id_pesanan_jadwal_studio);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Pengajuan Jadwal Studio");
                $BtnJadwalStudio.text("Ubah");

                show_byId_jadwalPesanan(id_pesanan_jadwal_studio);

                $BtnJadwalStudio.off('click').on("click", function() {
                    saveJadwalStudio("edit", id_pesanan_jadwal_studio);
                });
            }
        }

        function show_byId_jadwalPesanan(id_pesanan_jadwal_studio) {
            $.ajax({
                url: `{{ url('/showById_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    const $id_user = $('#id_user');
                    const $id_ruangan = $('#id_ruangan');
                    // const $harga_sewa = $('#harga_sewa');
                    const $tgl_pinjam = $('#tgl_pinjam');
                    const $waktu_mulai = $('#waktu_mulai');
                    const $waktu_selesai = $('#waktu_selesai');
                    const $ket_keperluan = $('#ket_keperluan');
                    const $img_jaminan = $('#img_jaminan');

                    $('#id_ruangan').val(response.id_ruangan);
                    // $('#harga_sewa').val(response.harga_sewa);
                    $('#tgl_pinjam').val(response.tgl_pinjam);
                    $("#waktu_mulai").val(response.waktu_mulai);
                    $("#waktu_selesai").val(response.waktu_selesai);
                    $("#ket_keperluan").val(response.ket_keperluan);

                    $('#output').attr('src', '{{ asset('storage/img_upload/pesanan_jadwal') }}/' + response
                        .img_jaminan);
                    $('#output').show();
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memproses data.',
                    });
                }
            });
        }

        function saveJadwalStudio(action, id_pesanan_jadwal_studio) {
            const id_user = $('#id_user').val();
            const id_ruangan = $('#id_ruangan').val();
            const tgl_pinjam = $('#tgl_pinjam').val();
            const waktu_mulai = $('#waktu_mulai').val();
            const waktu_selesai = $('#waktu_selesai').val();
            const ket_keperluan = $('#ket_keperluan').val();
            const img_jaminan = $('#img_jaminan')[0].files[0];

            if (!id_ruangan || !tgl_pinjam || !waktu_mulai || !waktu_selesai || !ket_keperluan) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            $.ajax({
                url: "{{ url('cek_tanggal_kosong') }}",
                method: 'post',
                data: {
                    id_ruangan,
                    tgl_pinjam,
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    const isDateBooked = response.length !== 0;
                    const isEdit = action === "edit";
                    const isAdd = action === "add";

                    if (isEdit) {
                        if (response.status === "ada" || response.status === "ada2") {
                            Swal.fire({
                                title: "Gagal simpan.",
                                text: "Tanggal tersebut telah di BOOKING!!",
                                icon: "error"
                            });

                            return 0;
                        } else if (!isDateBooked || (response[0].tgl_pinjam == tgl_pinjam)) {
                            submitForm(action, id_pesanan_jadwal_studio, {
                                id_user,
                                id_ruangan,
                                tgl_pinjam,
                                waktu_mulai,
                                waktu_selesai,
                                ket_keperluan,
                                img_jaminan
                            });
                        }
                    } else if (isAdd && !isDateBooked) {
                        submitForm(action, id_pesanan_jadwal_studio, {
                            id_user,
                            id_ruangan,
                            tgl_pinjam,
                            waktu_mulai,
                            waktu_selesai,
                            ket_keperluan,
                            img_jaminan
                        });
                    } else if (isAdd && isDateBooked) {
                        Swal.fire({
                            title: "Gagal simpan.",
                            text: "Tanggal tersebut telah di BOOKING!",
                            icon: "error"
                        });

                        return 0;
                    }
                },
                error: function(xhr, status, error) {
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

        function submitForm(action, id_pesanan_jadwal_studio, formDataObj) {
            const formData = new FormData();
            for (const key in formDataObj) {
                formData.append(key, formDataObj[key]);
            }
            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_pesanan_jadwal_studio') }}" :
                `{{ url('/edit_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tableJadwalStudio').DataTable().ajax.reload();
                    $("#add_jadwal_studio").modal("hide");

                    Swal.fire({
                        icon: "success",
                        title: action === "add" ? `${response.msg}` : `${response.msg}`,
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    setTimeout(() => {
                        location.reload()
                    }, 1500);
                },
                error: function(xhr, status, error) {
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
@endpush
