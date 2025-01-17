<div class="modal fade" id="add_paket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Paket</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_paket">Nama Paket</label>
                    <input type="text" class="form-control" id="nama_paket" required>
                </div>
                <div class="form-group">
                    <label for="biaya_paket">Harga Paket</label>
                    <input type="number" class="form-control" name="biaya_paket" id="biaya_paket" required>
                </div>
                <div class="form-group">
                    <label for="rincian_paket">Rincian Paket</label>
                    <textarea type="number" class="form-control" name="rincian_paket" id="rincian_paket" cols="30" rows="5"
                        required></textarea>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="deskripsi" cols="30" rows="5" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" onclick="btnSimpanPaket()">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- UBAH --}}
<div class="modal fade" id="edit_paket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Paket</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="up_nama_paket">Nama Paket</label>
                    <input type="text" class="form-control" id="up_nama_paket" required>
                </div>
                <div class="form-group">
                    <label for="up_biaya_paket">Harga Paket</label>
                    <input type="number" class="form-control" name="up_biaya_paket" id="up_biaya_paket" required>
                </div>
                <div class="form-group">
                    <label for="up_rincian_paket">Rincian Paket</label>
                    <textarea type="number" class="form-control" name="up_rincian_paket" id="up_rincian_paket" cols="30"
                        rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="up_deskripsi">Deskripsi</label>
                    <textarea class="form-control" name="up_deskripsi" id="up_deskripsi" cols="30" rows="5" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnEditPaket">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    function btnSimpanPaket() {
        let nama_paket = $('#nama_paket').val();
        let biaya_paket = $('#biaya_paket').val();
        let rincian_paket = $('#rincian_paket').val();
        let deskripsi = $('#deskripsi').val();
        let id_jasa_musik = "{{ $segments[1] }}"

        if (!nama_paket || !biaya_paket || !rincian_paket || !deskripsi) {
            Swal.fire({
                title: "Gagal simpan.",
                text: "Harap isi semua form!",
                icon: "error"
            });
            return
        }

        $.ajax({
            url: "{{ url('/add_paket_harga') }}",
            method: 'POST',
            data: {
                "id_jasa_musik": id_jasa_musik,
                "nama_paket": nama_paket,
                "biaya_paket": biaya_paket,
                "rincian_paket": rincian_paket,
                "deskripsi": deskripsi,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#tbPaketHarga').DataTable().ajax.reload()
                $("#add_paket").modal("hide")

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

                $('#nama_paket').val("");
                $('#biaya_paket').val("");
                $('#rincian_paket').val("");
                $('#deskripsi').val("");
            }
        });
    }

    function btnEditPaket(id_paket_jasa_musik) {
        let nama_paket = $('#up_nama_paket').val();
        let biaya_paket = $('#up_biaya_paket').val();
        let rincian_paket = $('#up_rincian_paket').val();
        let deskripsi = $('#up_deskripsi').val();
        let id_jasa_musik = "{{ $segments[1] }}"

        if (!nama_paket || !biaya_paket || !rincian_paket || !deskripsi) {
            Swal.fire({
                title: "Gagal simpan.",
                text: "Harap isi semua form!",
                icon: "error"
            });
            return
        }

        $.ajax({
            url: `{{ url('/edit_paket_harga/${id_paket_jasa_musik}') }}`,
            method: 'put',
            data: {
                "id_jasa_musik": id_jasa_musik,
                "nama_paket": nama_paket,
                "biaya_paket": biaya_paket,
                "rincian_paket": rincian_paket,
                "deskripsi": deskripsi,
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#tbPaketHarga').DataTable().ajax.reload()
                $("#edit_paket").modal("hide")

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
                    title: "Data Berhasil Diubah!"
                });
            }
        });
    }

    function show_byId(id_paket_jasa_musik) {
        $("#btnEditPaket").off();
        $("#btnEditPaket").on('click', function() {
            btnEditPaket(id_paket_jasa_musik);
        })

        $.ajax({
            url: `{{ url('/showByID_paket_harga/${id_paket_jasa_musik}') }}`,
            method: 'POST',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                $('#up_nama_paket').val(response[0].nama_paket);
                $('#up_biaya_paket').val(response[0].biaya_paket);
                $('#up_rincian_paket').val(response[0].rincian_paket);
                $('#up_deskripsi').val(response[0].deskripsi);
            }
        });
    }
</script>
