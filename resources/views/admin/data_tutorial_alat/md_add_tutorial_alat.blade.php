{{-- TAMBAH LAPORAN --}}
<div class="modal fade" id="add_tutorial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_alat">Nama Alat</label>
                    <input type="text" class="form-control" name="nama_alat" id="nama_alat">
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar <small class="text-danger fst-italic">(max: 1
                            mb)</small></small></label>
                    <input type="file" class="image-preview-filepond form-control" id="gambar">

                    <p class="my-3 output"><img id="output"
                            style="display: none; max-width: 200px; max-height: 200px;" />
                    </p>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Alat</label>
                    <textarea class="form-control" id="deskripsi" placeholder="Enter the Description" rows="10" name="deskripsi"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="BtnTutorial">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        let editorInstance;
        ClassicEditor
            .create(document.querySelector('#deskripsi'))
            .then(editor => {
                editorInstance = editor;
            })
            .catch(error => {
                console.error(error);
            });


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

        // function btnTutorial() {
        //     let nama_alat = $('#nama_alat').val();
        //     let deskripsi = editorInstance.getData();
        //     let gambar = $('#gambar')[0].files[0];

        //     if (!nama_alat || !deskripsi || !gambar) {
        //         Swal.fire({
        //             title: "Gagal simpan.",
        //             text: "Harap isi semua form!",
        //             icon: "error"
        //         });
        //         return;
        //     }

        //     let formData = new FormData();
        //     formData.append('nama_alat', nama_alat);
        //     formData.append('deskripsi', deskripsi);
        //     formData.append('gambar', gambar);
        //     formData.append('_token', "{{ csrf_token() }}");

        //     $.ajax({
        //         url: "{{ url('/add_tutorial_alat') }}",
        //         method: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             $('#tbTutorial').DataTable().ajax.reload();
        //             $("#add_tutorial").modal("hide");

        //             const Toast = Swal.mixin({
        //                 toast: true,
        //                 position: "top-end",
        //                 showConfirmButton: false,
        //                 timer: 3000,
        //                 timerProgressBar: true,
        //                 didOpen: (toast) => {
        //                     toast.onmouseenter = Swal.stopTimer;
        //                     toast.onmouseleave = Swal.resumeTimer;
        //                 }
        //             });

        //             Toast.fire({
        //                 icon: "success",
        //                 title: "Data Berhasil Disimpan!"
        //             });

        //             $('#nama_alat').val("");
        //             editorInstance.val("");
        //             $('#gambar').val("");
        //             $('#output').hide();
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Terjadi kesalahan:', error);
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Oops...',
        //                 text: 'Terjadi kesalahan saat memproses data.',
        //             });
        //         }
        //     });
        // }

        function openModal(action, id_tutorial = null) {
            $("#add_tutorial").modal("show");

            const $title_header = $("#title_header");
            const $BtnTutorial = $("#BtnTutorial");

            const $nama_alat = $('#nama_alat');
            const $deskripsi = $('#deskripsi');

            if (action === 'add') {
                $title_header.text("Tambah Tutorial Alat");
                $BtnTutorial.text("Simpan");

                $nama_alat.val("");
                $deskripsi.val("");

                $BtnTutorial.off('click').on("click", function() {
                    saveLaporan("add", id_tutorial);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Tutorial Alat");
                $BtnTutorial.text("Ubah");

                show_byid_tutorial(id_tutorial);

                $BtnTutorial.off('click').on("click", function() {
                    saveLaporan("edit", id_tutorial);
                });
            }
        }

        function show_byid_tutorial(id_tutorial) {
            $.ajax({
                url: `{{ url('/show_byId_tutorial_alat/${id_tutorial}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#id_tutorial').val(response.id_tutorial);
                    $('#nama_alat').val(response.nama_alat);
                    editorInstance.setData(response.deskripsi);

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

        function saveLaporan(action, id_tutorial) {
            const nama_alat = $('#nama_alat').val();
            let deskripsi = editorInstance.getData();
            const gambarFiles = $('#gambar')[0].files[0];

            if (!nama_alat || !deskripsi) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            // if (gambarFiles.size > 1048576) { // 1MB = 1048576 bytes
            //     Swal.fire({
            //         title: "Gagal simpan.",
            //         text: `File ${gambarFiles.name} terlalu besar! Maksimal 1MB.`,
            //         icon: "error"
            //     });
            //     return;
            // }

            const formData = new FormData();
            formData.append('nama_alat', nama_alat);
            formData.append('deskripsi', deskripsi);
            formData.append('gambar_alat', gambarFiles);
            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_tutorial_alat') }}" :
                `{{ url('/edit_tutorial_alat/${id_tutorial}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#tbTutorial').DataTable().ajax.reload();
                    $("#add_tutorial").modal("hide");

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
                        text: errorMsg = "Harap isi file gambar!",
                    });
                }
            });
        }
    </script>
@endpush
