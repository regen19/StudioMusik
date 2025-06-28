{{-- TAMBAH JADWAL ALAT --}}
<div class="modal fade" id="add_pinjam_alat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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

                <!-- <div class="form-group row">
                    <div class="col-12">
                        <label for="id_alat">Alat Yang Dipinjam</label>
                        <select name="id_alat" id="id_alat" class="form-control" multiple onchange="selectAlat()">
                            <option value="">Pilih Alat</option>
                        </select>
                    </div>
                </div> -->
                <form class="repeater">
                    <div data-repeater-list="list-alat">
                        <div data-repeater-item>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="id_alat">Alat Yang Dipinjam</label>
                                    <select name="id_alat" class="form-control" onchange="selectAlat()">
                                        <option value="">Pilih Alat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="jumlah">Jumlah Dipinjam</label>
                                    <input type="number" id="jumlah" name="jumlah" class="form-control" onchange="selectAlat()">
                                       
                                    </input>
                                </div>
                            </div>
                            <input data-repeater-delete type="button" value="Delete"/>
                        </div>
                    </div>
                    <input data-repeater-create type="button" value="Tambah Alat"/>
                </form>

                <div class="form-group">
                    <label for="tgl_pinjam">Tanggal Peminjaman <small class="text-danger fst-italic">*harap pilih
                            alat dahulu</small></label>
                    <input type="date" class="form-control" id="tgl_pinjam" required >
                    <span id="alert_tgl"></span>
                </div>

                <div class="form-group">
                    <label for="tgl_kembali">Tanggal Dikembalikan <small class="text-danger fst-italic">*max 2 hari (48jam)</small></label>
                    <input type="date" class="form-control" id="tgl_kembali" required >
                    <span id="alert_tgl"></span>
                </div>

                <div class="form-group row">
                    <div class="col-6">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <select class="form-control" id="waktu_mulai" required></select>
                    </div>
                    <div class="col-6">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <select class="form-control" id="waktu_selesai" required></select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ket_keperluan">Keperluan Peminjaman</label>
                    <textarea class="form-control" name="ket_keperluan" id="ket_keperluan" cols="30" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="foto_jaminan">Jaminan (KTP/KTM) <small class="text-danger fst-italic">(max: 1
                            mb)</small></label>
                    <input type="file" class="image-preview-filepond form-control" id="foto_jaminan" required>

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="BtnJadwalAlat">Simpan</button>
                <span id="btnSimpanLoading" style="display:none;">
                    <img src="{{ asset('assets/img/loading.gif') }}" alt="Loading..." style="width:20px;" />
                </span>
            </div>
        </div>
    </div>
</div>

@push('script')
    
    <!-- <script src="{{ asset('assets/jquery.repeater/jquery-1.11.1.js') }}"></script> -->
    <script src="{{ asset('assets/jquery.repeater/jquery.repeater.js') }}"></script>
    
    <script>
        $(document).ready(function () {
            $('.repeater').repeater({
                // (Optional)
                // start with an empty list of repeaters. Set your first (and only)
                // "data-repeater-item" with style="display:none;" and pass the
                // following configuration flag
                initEmpty: true,
                // (Optional)
                // "show" is called just after an item is added.  The item is hidden
                // at this point.  If a show callback is not given the item will
                // have $(this).show() called on it.
                show: function () {
                    const currentRow = $(this); // 'this' adalah elemen hasil clone repeater
                    
                    $.ajax({
                        url: `{{ url('list_data_alat') }}`,
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: 'json',
                        success: function(response) {
                            const select = currentRow.find('select');
                            select.empty();
                            select.append(`<option value="">-- Pilih Alat --</option>`); // opsional placeholder

                            $.each(response, function(key, val) {
                                select.append(`<option value="${val.id_alat}">${val.nama_alat}</option>`);
                            });
                        },
                    });

                    currentRow.slideDown();
                },
                // (Optional)
                // "hide" is called when a user clicks on a data-repeater-delete
                // element.  The item is still visible.  "hide" is passed a function
                // as its first argument which will properly remove the item.
                // "hide" allows for a confirmation step, to send a delete request
                // to the server, etc.  If a hide callback is not given the item
                // will be deleted.
                hide: function (deleteElement) {
                    if(confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                
                // (Optional)
                // Removes the delete button from the first list item,
                // defaults to false.
                isFirstItemUndeletable: true
            })
        });
    </script>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: `{{ url('list_data_alat') }}`,
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {

                    $.each(response, function(key, val) {
                        $("#id_alat").append(
                            `<option value="${val.id_alat}">${val.nama_alat}</option>`
                        )
                    })
                },
            });

            // setting waktu
            function populateTimeOptions(elementId, startTime, endTime, intervalMinutes) {
                var $selectElement = $('#' + elementId);
                var currentTime = startTime;

                while (currentTime <= endTime) {
                    var option = $('<option></option>').val(currentTime).text(currentTime);
                    $selectElement.append(option);

                    var timeParts = currentTime.split(':');
                    var hours = parseInt(timeParts[0]);
                    var minutes = parseInt(timeParts[1]);

                    minutes += intervalMinutes;
                    if (minutes >= 60) {
                        hours += 1;
                        minutes = minutes - 60;
                    }

                    currentTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes;
                }
            }

            populateTimeOptions('waktu_mulai', '08:00', '17:00', 10);
            populateTimeOptions('waktu_selesai', '08:00', '17:00', 10);
        })

        // function cek_tanggal_kosong() {
        //     let tgl_pinjam = $("#tgl_pinjam").val();
        //     let tgl_kembali = $("#tgl_kembali").val();
        //     let id_alat = $("#id_alat").val();
        //     let waktu_mulai = $("#waktu_mulai").val();
        //     let waktu_selesai = $("#waktu_selesai").val();

        //     $.ajax({
        //         url: `{{ url('cek_tanggal_kosong') }}`,
        //         method: 'post',
        //         data: {
        //             "tgl_pinjam": tgl_pinjam,
        //             "tgl_kembali": tgl_kembali,
        //             "id_alat": id_alat,
        //             "waktu_mulai": waktu_mulai,
        //             "waktu_selesai": waktu_selesai,
        //             "_token": "{{ csrf_token() }}"
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.length === 0) {
        //                 $("#alert_tgl").html(`<small class="text-success fst-italic"><i class="bi bi-check-square"></i> Tanggal tersebut kosong
        //                 !</small>`);
        //             } else if (response.status == "ada" || response.status == "ada2") {
        //                 $("#alert_tgl").html(`<small class="text-danger fst-italic"><i
        //                     class="bi bi-exclamation-triangle-fill"></i> Tanggal tersebut sudah di BOOKING
        //                 !</small>`);
        //             } else if (response.status == "weekend") {
        //                 $("#alert_tgl").html(`<small class="text-danger fst-italic"><i
        //                     class="bi bi-exclamation-triangle-fill"></i> Tidak bisa di hari SABTU dan Minggu
        //                 !</small>`);
        //             }
        //         },
        //         error: function(err) {
        //             // reject(err);
        //         }
        //     });
        // }

        function selectAlat() {
            let selectedOption = $("#id_alat option:selected");
            let biaya_perawatan = selectedOption.data('harga');

            // $("#biaya_perawatan").val(biaya_perawatan);
        }

        $("#foto_jaminan").on("change", function() {
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

        function openModal(action, id_pesanan_pinjam_alat = null) {
            $("#add_pinjam_alat").modal("show");

            const $title_header = $("#title_header");
            const $BtnJadwalAlat = $("#BtnJadwalAlat");

            const $id_user = $('#id_user');
            const $jumlah = $('#jumlah');
            const $tgl_pinjam = $('#tgl_pinjam');
            const $tgl_kembali = $('#tgl_kembali');
            const $waktu_mulai = $('#waktu_mulai');
            const $waktu_selesai = $('#waktu_selesai');
            const $ket_keperluan = $('#ket_keperluan');
            const $foto_jaminan = $('#foto_jaminan');
            const $output = $('#output');

            
            if (action === 'add') {
                $title_header.text("Tambah Pengajuan Jadwal Alat");
                $BtnJadwalAlat.text("Simpan");

                
                $jumlah.val("");
                $tgl_pinjam.val("");
                $tgl_kembali.val("");
                $waktu_mulai.val("");
                $waktu_selesai.val("");
                $ket_keperluan.val("");
                $foto_jaminan.val("");
                $output.hide();

                $BtnJadwalAlat.off('click').on("click", function() {
                    saveJadwalAlat("add", id_pesanan_pinjam_alat);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Pengajuan Jadwal Alat");
                $BtnJadwalAlat.text("Ubah");

                show_byId_jadwalPesanan(id_pesanan_pinjam_alat);

                $BtnJadwalAlat.off('click').on("click", function() {
                    saveJadwalAlat("edit", id_pesanan_pinjam_alat);
                });
            }
        }

        function show_byId_jadwalPesanan(id_pesanan_pinjam_alat) {
            $.ajax({
                url: `{{ url('/showByid_pesanan_pinjam_alat/${id_pesanan_pinjam_alat}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    const $id_user = $('#id_user');
                    
                    
                    // const $biaya_perawatan = $('#biaya_perawatan');
                    const $tgl_pinjam = $('#tgl_pinjam');
                    const $tgl_kembali = $('#tgl_kembali');
                    const $waktu_mulai = $('#waktu_mulai');
                    const $waktu_selesai = $('#waktu_selesai');
                    const $ket_keperluan = $('#ket_keperluan');
                    const $foto_jaminan = $('#foto_jaminan');

                    
                    
                    // $('#biaya_perawatan').val(response.biaya_perawatan);
                    $('#tgl_pinjam').val(response.tgl_pinjam);
                    $('#tgl_kembali').val(response.tgl_kembali);
                    $("#waktu_mulai").val(response.waktu_mulai);
                    $("#waktu_selesai").val(response.waktu_selesai);
                    $("#ket_keperluan").val(response.ket_keperluan);

                    $('#output').attr('src', '{{ asset('storage/img_upload/pesanan_jadwal') }}/' + response
                        .foto_jaminan);
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

        function saveJadwalAlat(action, id_pesanan_pinjam_alat) {
            const id_user = $('#id_user').val();
            const jumlah = $('#jumlah').val();
            const tgl_pinjam = $('#tgl_pinjam').val();
            const tgl_kembali = $('#tgl_kembali').val();
            const waktu_mulai = $('#waktu_mulai').val();
            const waktu_selesai = $('#waktu_selesai').val();
            const ket_keperluan = $('#ket_keperluan').val();
            const no_wa = $('#no_wa').val();
            const foto_jaminan = $('#foto_jaminan')[0].files[0];
            let list_alat = [];

            $('[data-repeater-item]').each(function () {
                let id_alat = $(this).find('select[name^="id_alat"]').val();
                let jumlah = $(this).find('input[name^="jumlah"]').val();

                list_alat.push({
                    id_alat: id_alat,
                    jumlah: jumlah
                });
            });

            if (!list_alat || !jumlah || !tgl_pinjam || !waktu_mulai || !waktu_selesai || !ket_keperluan) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            const isEdit = action === "edit";
            const isAdd = action === "add";

            if (isEdit) {
                submitForm(action, id_pesanan_pinjam_alat, {
                    id_user,
                    list_alat,
                    jumlah,
                    tgl_pinjam,
                    tgl_kembali,
                    waktu_mulai,
                    waktu_selesai,
                    ket_keperluan,
                    foto_jaminan,
                    no_wa,
                });
            } else if (isAdd) {
                submitForm(action, id_pesanan_pinjam_alat, {
                    id_user,
                    list_alat,
                    jumlah,
                    tgl_pinjam,
                    tgl_kembali,
                    waktu_mulai,
                    waktu_selesai,
                    ket_keperluan,
                    foto_jaminan,
                    no_wa,
                });
            }

        }

        function submitForm(action, id_pesanan_pinjam_alat, formDataObj) {
            $("#BtnJadwalAlat").hide();
            $("#btnSimpanLoading").show();

            const formData = new FormData();
            for (const key in formDataObj) {
                formData.append(key, formDataObj[key]);
            }
            formData.append('_token', "{{ csrf_token() }}");
            const ajaxUrl = action === "add" ? "{{ url('/add_pesanan_pinjam_alat') }}" :
                `{{ url('/edit_pesanan_pinjam_alat/${id_pesanan_pinjam_alat}') }}`;
            console.log(formData)
            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tableJadwalAlat').DataTable().ajax.reload();
                    // $("#add_pinjam_alat").modal("hide");

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
                        // location.reload()
                    }, 1500);
                },
                complete: function() {
                    // Mengembalikan teks tombol dan menyembunyikan loading
                    $("#BtnJadwalAlat").show();
                    // $("#btnSimpanLoading").hide();
                },
                error: function(xhr, status, error) {
                    $("#BtnJadwalAlat").show();
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
@endpush
