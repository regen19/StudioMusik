<div class="modal fade" id="add_jasa_musik" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Pesanan Jasa Musik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_produksi">Tanggal Pesanan</label>
                        <input type="datetime-local" class="form-control" name="tgl_produksi" id="tgl_produksi"
                            value="{{ date('Y-m-d\TH:i', strtotime(now())) }}" required>
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

                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="btnSimpan()">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            list_jasa_musik()
        })

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
            let id_jasa_musik = $('#id_jasa_musik').val();
            let tgl_produksi = $('#tgl_produksi').val();
            let id_user = "{{ Auth::user()->id_user }}";
            let no_wa = $('#no_wa').val();
            let keterangan = $('#keterangan').val();

            if (!id_jasa_musik || !tgl_produksi || !id_user || !no_wa || !keterangan) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return
            }

            $.ajax({
                url: "{{ url('/add_pesanan_jasa_musik') }}",
                method: 'POST',
                data: {
                    "id_jasa_musik": id_jasa_musik,
                    "tgl_produksi": tgl_produksi,
                    "id_user": id_user,
                    "no_wa": no_wa,
                    "keterangan": keterangan,
                    "_token": "{{ csrf_token() }}"
                },
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
                    $('#no_wa').val("");
                    $('#keterangan').val("");
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
