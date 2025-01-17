{{-- TAMBAH LAPORAN --}}
<div class="modal fade" id="add_ruangan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_ruangan">Nama Ruangan</label>
                    <input type="text" id="nama_ruangan" class="form-control mb-3" required>
                </div>

                {{-- <div class="form-group">
                    <label for="harga_sewa">Biaya Perawatan</label>
                    <input type="number" class="form-control" name="harga_sewa" id="harga_sewa" min="1"
                        required>
                </div> --}}

                <div class="form-group">
                    <label for="kapasitas">Kapasitas</label>
                    <input type="number" class="form-control" name="kapasitas" id="kapasitas" min="1" required>
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" class="form-control" name="lokasi" id="lokasi" required>
                </div>

                <div class="form-group">
                    <label for="fasilitas">Fasilitas</label>
                    <textarea class="form-control" name="fasilitas" id="fasilitas" cols="30" rows="5"></textarea>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Thumbnail <small class="text-danger fst-italic">(max: 1
                            mb)</small></small></label>
                    <input type="file" class="image-preview-filepond form-control" id="thumbnail" required>

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="BtnDataRuangan"></button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#thumbnail").on("change", function() {
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

        function openModal(action, id_ruangan = null) {
            $("#add_ruangan").modal("show");

            const $title_header = $("#title_header");
            const $btnDataRuangan = $("#BtnDataRuangan");

            // const $harga_sewa = $('#harga_sewa');
            const $nama_ruangan = $('#nama_ruangan');
            const $kapasitas = $('#kapasitas');
            const $thumbnail = $('#thumbnail');
            const $lokasi = $('#lokasi');
            const $fasilitas = $('#fasilitas');
            const $output = $('#output');

            if (action === 'add') {
                $title_header.text("Tambah Data Ruangan");
                $btnDataRuangan.text("Simpan");

                $nama_ruangan.val("");
                $kapasitas.val("");
                $thumbnail.val("");
                $lokasi.val("");
                $fasilitas.val("");
                $output.hide();

                $btnDataRuangan.off('click').on("click", function() {
                    saveRuangan("add", id_ruangan);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Data Ruangan");
                $btnDataRuangan.text("Ubah");
                show_byId_ruangan(id_ruangan);

                $btnDataRuangan.off('click').on("click", function() {
                    saveRuangan("edit", id_ruangan);
                });
            }
        }

        function show_byId_ruangan(id_ruangan) {
            $.ajax({
                url: `{{ url('/showById_data_ruangan/${id_ruangan}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#nama_ruangan').val(response.nama_ruangan);
                    // $('#harga_sewa').val(response.harga_sewa);
                    $('#kapasitas').val(response.kapasitas);
                    $('#lokasi').val(response.lokasi);
                    $('#output').attr('src', '{{ asset('storage/img_upload/data_ruangan') }}/' + response
                        .thumbnail);
                    $('#output').show();
                    $("#fasilitas").val(response.fasilitas);
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

        function saveRuangan(action, id_ruangan) {
            const nama_ruangan = $('#nama_ruangan').val();
            // const harga_sewa = $('#harga_sewa').val();
            const kapasitas = $('#kapasitas').val();
            const thumbnail = $('#thumbnail')[0].files[0];
            const lokasi = $('#lokasi').val();
            const fasilitas = $('#fasilitas').val();

            if (!nama_ruangan || !kapasitas || !lokasi) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            const formData = new FormData();
            formData.append('nama_ruangan', nama_ruangan);
            // formData.append('harga_sewa', harga_sewa);
            formData.append('kapasitas', kapasitas);
            formData.append('thumbnail', thumbnail);
            formData.append('lokasi', lokasi);
            formData.append('fasilitas', fasilitas);
            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_data_ruangan') }}" :
                `{{ url('/edit_data_ruangan/${id_ruangan}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tbDataRuangan').DataTable().ajax.reload();
                    $("#add_ruangan").modal("hide");

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
                        title: action === "add" ? "Data ruangan Berhasil Disimpan!" :
                            "Data ruangan Berhasil Diubah!"
                    });

                    $nama_ruangan.val("");
                    $kapasitas.val("");
                    $thumbnail.val("");
                    $lokasi.val("");
                    $fasilitas.val("");
                    $output.hide();

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
    </script>
@endpush
