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
                    <div class="form-group">
                        <label for="nama_peminjam">Nama Peminjam</label>
                        <input type="text" class="form-control" name="nama_peminjam" id="nama_peminjam"
                            value="{{ Auth::user()->username }}" readonly required>
                        <input type="hidden" class="form-control" name="id_user" id="id_user"
                            value="{{ Auth::user()->id_user }}" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="id_jenis_jasa">Jenis Jasa</label>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-select" id="id_jenis_jasa">
                                    <option selected disabled>Pilih Jenis</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group paket-list" style="display: none">
                        <label for="id_paket_jasa_musik">Paket Jasa</label>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <select class="form-select id_paket_jasa_musik" id="id_paket_jasa_musik">
                                    <option selected disabled>Pilih Paket</option>
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="form-group">
                        <label for="no_wa">Nomor WhatsApp <small class="text-danger fst-italic">(ex :
                                0821234*****)</small></label>
                        <input type="number" class="form-control" name="no_wa" id="no_wa" required>
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
            $('#add_jasa_musik').on('shown.bs.modal', function() {
                // var element2 = document.getElementById('id_jenis_jasa');
                // var choices2 = new Choices(element2);

                // var element1 = document.getElementById('id_paket_jasa_musik');
                // var choices1 = new Choices(element1);

                // choices2.setValue()
                // choices1.setValue()
            });

            list_jenis_jasa()

            // breakdown list paket harga jasa
            $("#add_jasa_musik").on("change", "#id_jenis_jasa", function() {
                $(".paket-list").show()

                let id_jenis_jasa = $("#id_jenis_jasa").val()

                // $.ajax({
                //     url: `{{ url('/select_paket_jasa/') }}/${id_jenis_jasa}`,
                //     method: "post",
                //     data: {
                //         "_token": "{{ csrf_token() }}"
                //     },
                //     success: function(response) {
                //         $("#id_paket_jasa_musik").empty()

                //         $.each(response, function(key, value) {
                //             $("#id_paket_jasa_musik").append(
                //                 `<option value="${value.id_paket_jasa_musik}">${value.nama_paket} - Rp${value.biaya_paket}</option>`
                //             )
                //         })
                //     }
                // })
            })
        })

        function list_jenis_jasa() {
            $.ajax({
                url: `{{ url('/fetch_master_jenis_jasa') }}`,
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    let list_jenis = response.data;

                    $.each(list_jenis, function(key, value) {
                        $("#id_jenis_jasa").append(
                            `<option value="${value.id_jenis_jasa}">${value.nama_jenis_jasa}</option>`
                        )
                    })
                }
            });
        }

        function btnSimpan() {
            let id_jenis_jasa = $('#id_jenis_jasa').val();
            let tgl_produksi = $('#tgl_produksi').val();
            let id_user = "{{ Auth::user()->id_user }}";
            let no_wa = $('#no_wa').val();
            let keterangan = $('#keterangan').val();
            // let id_paket_jasa_musik = $("#id_paket_jasa_musik").val();

            if (!id_jenis_jasa || !tgl_produksi || !id_user || !no_wa || !keterangan) {
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
                    // "id_paket_jasa_musik": id_paket_jasa_musik,
                    "id_jenis_jasa": id_jenis_jasa,
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

                    $('#id_jenis_jasa').val("");
                    $('#tgl_produksi').val("");
                    $('#no_wa').val("");
                    $('#keterangan').val("");
                }
            });
        }
    </script>
@endpush
