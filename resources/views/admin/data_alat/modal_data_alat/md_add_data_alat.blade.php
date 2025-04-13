{{-- TAMBAH LAPORAN --}}
<div class="modal fade" id="add_alat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_alat">Nama alat</label>
                    <input type="text" id="nama_alat" class="form-control mb-3" required>
                </div>

                <div class="form-group">
                    <label for="tipe_alat">Tipe Alat</label>
                    <input type="text" class="form-control" name="tipe_alat" id="tipe_alat">
                </div>

                <div class="form-group">
                    <label for="jumlah_alat">Jumlah Alat</label>
                    <input type="number" class="form-control" name="jumlah_alat" id="jumlah_alat" min="1" required>
                </div>

                <div class="form-group">
                    <label for="biaya_perawatan">Biaya Perawatan</label>
                    <input type="number" class="form-control" name="biaya_perawatan" id="biaya_perawatan">
                </div>

                <div class="form-group">
                    <label for="foto_alat">Foto Alat <small class="text-danger fst-italic">(max: 1
                            mb)</small></small></label>
                    <input type="file" class="image-preview-filepond form-control" id="foto_alat" required>

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="BtnDataAlat"></button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#foto_alat").on("change", function() {
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

        function openModal(action, id_alat = null) {
            $("#add_alat").modal("show");

            const $title_header = $("#title_header");
            const $btnDataAlat = $("#BtnDataAlat");

            // const $harga_sewa = $('#harga_sewa');
            const $nama_alat = $('#nama_alat');
            const $tipe_alat = $('#tipe_alat');
            const $foto_alat = $('#foto_alat');
            const $jumlah_alat = $('#jumlah_alat');
            const $biaya_perawatan = $('#biaya_perawatan');
            const $output = $('#output');

            if (action === 'add') {
                $title_header.text("Tambah Data alat");
                $btnDataAlat.text("Simpan");

                $nama_alat.val("");
                $tipe_alat.val("");
                $foto_alat.val("");
                $jumlah_alat.val("");
                $biaya_perawatan.val("");
                $output.hide();

                $btnDataAlat.off('click').on("click", function() {
                    savealat("add", id_alat);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Data alat");
                $btnDataAlat.text("Ubah");
                show_byId_alat(id_alat);

                $btnDataAlat.off('click').on("click", function() {
                    savealat("edit", id_alat);
                });
            }
        }

        function show_byId_alat(id_alat) {
            $.ajax({
                url: `{{ url('/showById_data_alat/${id_alat}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#nama_alat').val(response.nama_alat);
                    // $('#harga_sewa').val(response.harga_sewa);
                    $('#tipe_alat').val(response.tipe_alat);
                    $('#jumlah_alat').val(response.jumlah_alat);
                    $('#output').attr('src', '{{ asset('storage/img_upload/data_alat') }}/' + response
                        .foto_alat);
                    $('#output').show();
                    $("#biaya_perawatan").val(response.biaya_perawatan);
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

        function savealat(action, id_alat) {
            const nama_alat = $('#nama_alat').val();
            // const harga_sewa = $('#harga_sewa').val();
            const tipe_alat = $('#tipe_alat').val();
            const foto_alat = $('#foto_alat')[0].files[0];
            const jumlah_alat = $('#jumlah_alat').val();
            const biaya_perawatan = $('#biaya_perawatan').val();

            if (!nama_alat || !tipe_alat || !jumlah_alat) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            const formData = new FormData();
            formData.append('nama_alat', nama_alat);
            // formData.append('harga_sewa', harga_sewa);
            formData.append('tipe_alat', tipe_alat);
            formData.append('foto_alat', foto_alat);
            formData.append('jumlah_alat', jumlah_alat);
            formData.append('biaya_perawatan', biaya_perawatan);
            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_data_alat') }}" :
                `{{ url('/edit_data_alat/${id_alat}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tbDataAlat').DataTable().ajax.reload();
                    $("#add_alat").modal("hide");

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
                        title: action === "add" ? "Data alat Berhasil Disimpan!" :
                            "Data alat Berhasil Diubah!"
                    });

                    $nama_alat.val("");
                    $tipe_alat.val("");
                    $foto_alat.val("");
                    $jumlah_alat.val("");
                    $biaya_perawatan.val("");
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
