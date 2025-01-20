{{-- TAMBAH LAPORAN --}}
<div class="modal fade" id="add_laporan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Laporan</h1>
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
                    <label for="gambar">Gambar <small class="text-danger fst-italic">(max: 1
                            mb)</small></small></label>
                    <input type="file" class="image-preview-filepond form-control" id="gambar" multiple>

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="btnLaporan()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $("#gambar").on("change", function() {
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

        function btnLaporan() {
            let tgl_laporan = $('#tgl_laporan').val();
            let jenis_laporan = $('#jenis_laporan').val();
            let gambar_files = $('#gambar')[0].files;
            let keterangan = $('#keterangan').val();

            if (!tgl_laporan || !jenis_laporan || !gambar || !keterangan) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            let formData = new FormData();
            formData.append('tgl_laporan', tgl_laporan);
            formData.append('jenis_laporan', jenis_laporan);
            formData.append('gambar', gambar);
            formData.append('keterangan', keterangan);
            formData.append('_token', "{{ csrf_token() }}");

            for (let i = 0; i < gambar_files.length; i++) {
                formData.append('gambar[]', gambar_files[i]);
            }

            // $.ajax({
            //     url: "{{ url('/add_laporan_masalah') }}",
            //     method: 'POST',
            //     data: formData,
            //     contentType: false,
            //     processData: false,
            //     success: function(response) {
            //         $('#tbLaporan').DataTable().ajax.reload();
            //         $("#add_laporan").modal("hide");

            //         const Toast = Swal.mixin({
            //             toast: true,
            //             position: "top-end",
            //             showConfirmButton: false,
            //             timer: 3000,
            //             timerProgressBar: true,
            //             didOpen: (toast) => {
            //                 toast.onmouseenter = Swal.stopTimer;
            //                 toast.onmouseleave = Swal.resumeTimer;
            //             }
            //         });

            //         Toast.fire({
            //             icon: "success",
            //             title: "Data Berhasil Disimpan!"
            //         });

            //         $('#tgl_laporan').val("");
            //         $('#jenis_laporan').val("");
            //         $('#gambar').val("");
            //         $('#keterangan').val("");
            //         $('#output').hide();
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('Terjadi kesalahan:', error);
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Oops...',
            //             text: 'Terjadi kesalahan saat memproses data.',
            //         });
            //     }
            // });
        }
    </script>
@endpush
