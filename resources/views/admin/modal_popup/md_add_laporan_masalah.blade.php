{{-- TAMBAH LAPORAN --}}
<div class="modal fade" id="add_laporan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tgl_laporan">Tanggal Laporan</label>
                    <input type="date" id="tgl_laporan" class="form-control mb-3"
                        value="{{ date('Y-m-d', strtotime(now())) }}">
                </div>
                <div class="form-group">
                    <label for="jenis_laporan">Jenis Laporan</label>
                    <input type="text" class="form-control" name="jenis_laporan" id="jenis_laporan">
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="gambar">Gambar <small class="text-danger fst-italic">(max: 1 mb)</small></label>
                    <input type="file" class="image-preview-filepond form-control" id="gambar" name="gambar[]"
                        multiple required>

                    <div id="preview-container" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="BtnLaporan">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#gambar").on("change", function() {
            previewImg(this, '#preview-container');
        });

        function previewImg(input, outputContainer) {
            if (input.files) {
                $(outputContainer).empty(); // Hapus gambar sebelumnya

                Array.from(input.files).forEach(file => {
                    if (file) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            let imgElement = $("<img>")
                                .attr('src', e.target.result)
                                .css({
                                    "max-width": "150px",
                                    "max-height": "150px",
                                    "margin-right": "5px"
                                });

                            $(outputContainer).append(imgElement);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        function openModal(action, id_laporan = null) {
            $("#add_laporan").modal("show");

            const $title_header = $("#title_header");
            const $BtnLaporan = $("#BtnLaporan");

            const $id_laporan = $('#id_laporan');
            const $tgl_laporan = $('#tgl_laporan');
            const $jenis_laporan = $('#jenis_laporan');
            const $keterangan = $('#keterangan');
            const $gambar = $('#gambar');
            const $output = $('#output');

            if (action === 'add') {
                $title_header.text("Tambah Laporan");
                $BtnLaporan.text("Simpan");

                $id_laporan.val("");
                $tgl_laporan.val("");
                $jenis_laporan.val("");
                $keterangan.val("");
                $gambar.val("");
                $output.hide();

                $BtnLaporan.off('click').on("click", function() {
                    saveLaporan("add", id_laporan);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Laporan");
                $BtnLaporan.text("Ubah");

                show_byId_laporan(id_laporan);

                $BtnLaporan.off('click').on("click", function() {
                    saveLaporan("edit", id_laporan);
                });
            }
        }

        function show_byId_laporan(id_laporan) {
            $.ajax({
                url: `{{ url('/show_byId_laporan_masalah/${id_laporan}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    const $id_laporan = $('#id_laporan');
                    const $tgl_laporan = $('#tgl_laporan');
                    const $jenis_laporan = $('#jenis_laporan');
                    const $keterangan = $('#keterangan');
                    const $gambar = $('#gambar');

                    $('#id_laporan').val(response.id_laporan);
                    $('#tgl_laporan').val(response.tgl_laporan);
                    $("#jenis_laporan").val(response.jenis_laporan);
                    $("#keterangan").val(response.keterangan);

                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error,
                    });
                }
            });
        }

        function saveLaporan(action, id_laporan) {
            const tgl_laporan = $('#tgl_laporan').val();
            const jenis_laporan = $('#jenis_laporan').val();
            const keterangan = $('#keterangan').val();
            const gambarFiles = $('#gambar')[0].files;

            if (!tgl_laporan || !jenis_laporan || !keterangan) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            for (let i = 0; i < gambarFiles.length; i++) {
                if (gambarFiles[i].size > 1048576) { // 1MB = 1048576 bytes
                    Swal.fire({
                        title: "Gagal simpan.",
                        text: `File ${gambarFiles[i].name} terlalu besar! Maksimal 1MB.`,
                        icon: "error"
                    });
                    return;
                }
            }

            const formData = new FormData();
            formData.append('tgl_laporan', tgl_laporan);
            formData.append('jenis_laporan', jenis_laporan);
            formData.append('keterangan', keterangan);

            // Tambahkan Semua Gambar ke FormData
            for (let i = 0; i < gambarFiles.length; i++) {
                formData.append('gambar[]', gambarFiles[i]);
            }

            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_laporan_masalah') }}" :
                `{{ url('/edit_laporan_masalah/${id_laporan}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tbLaporan').DataTable().ajax.reload();
                    $("#add_laporan").modal("hide");

                    Swal.fire({
                        icon: "success",
                        title: `${response.msg}`,
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
                    let errorMsg = "";
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
