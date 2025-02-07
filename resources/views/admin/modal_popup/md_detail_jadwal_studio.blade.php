{{-- DETAIL JASA --}}
<div class="modal fade" id="detail_studio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Pesanan Jadwal Studio</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6" id="tgl_pengajuan"></p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Peminjam</th>
                                <th>No. Whatsapp</th>
                                <th>Email</th>
                                <th>Tanggal & Jam Pinjam</th>
                                <th>Keperluan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td id="nama_user1"></td>
                                <td id="no_wa_detail"></td>
                                <td id="email"></td>
                                <td id="tanggal"></td>
                                <td id="catatan"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive m-3">
                    <table>
                        <thead>
                            <tr>
                                <td>Status Persetujuan</td>
                                <td>:</td>
                                <td id="status_setuju"></td>
                            </tr>

                            <tr>
                                <td>Status Peminjaman</td>
                                <td>:</td>
                                <td id="status_pinjam"></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="form-group">
                    <label for="catatan_admin">Keterangan Admin :</label>
                    <textarea class="form-control" name="catatan_admin" id="catatan_admin" cols="30" rows="3" readonly></textarea>
                </div>


                <div class="table-responsive">
                    <table class="table table-bordered" id="tbLampiran">
                        <thead>
                            <tr>
                                <th>Jaminan</th>
                                <th>KONDISI RUANGAN SEBELUM DIGUNAKAN</th>
                                <th>KONDISI RUANGAN SETELAH DIGUNAKAN</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <p class="my-3 "><img id="img_jaminan1"
                                            style="max-width: 200px; max-height: 200px;" />
                                    </p>
                                </td>
                                <!-- Kondisi Awal -->
                                <td id="show_kondisi_awal" style="display:none;">
                                    <a id="link-img-kondisi-awal" target="_blank" href="" class="my-3">
                                        <img id="img_kondisi_awal" style="max-width: 200px; max-height: 200px;" />
                                    </a>
                                </td>

                                <!-- Kondisi Akhir -->
                                <td id="show_kondisi_akhir" style="display:none;">
                                    <a id="link-img-kondisi-akhir" target="_blank" href="" class="my-3">
                                        <img id="img_kondisi_akhir" style="max-width: 200px; max-height: 200px;" />
                                    </a>
                                </td>

                                <!-- Form Kondisi Awal -->
                                <form id="form_kondisi_awal" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <td id="show_form_kondisi_awal" style="display:none;">
                                        <input type="file" class="image-preview-filepond form-control"
                                            name="kondisi_awal" id="input_kondisi_awal" required>
                                        <p class="my-3">
                                            <img id="img_kondisi_awal_preview"
                                                style="display: none; max-width: 200px; max-height: 200px;" />
                                        </p>
                                        <p>
                                            <button class="btn btn-success" id="btn_simpan_awal"
                                                data-type="awal">Simpan</button>
                                        </p>
                                    </td>
                                </form>

                                <!-- Form Kondisi Akhir -->
                                <form id="form_kondisi_akhir" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <td id="show_form_kondisi_akhir" style="display:none;">
                                        <input type="file" class="image-preview-filepond form-control"
                                            name="kondisi_akhir" id="input_kondisi_akhir" required>
                                        <p class="my-3">
                                            <img id="img_kondisi_akhir_preview"
                                                style="display: none; max-width: 200px; max-height: 200px;" />
                                        </p>
                                        <p>
                                            <button class="btn btn-success" id="btn_simpan_akhir"
                                                data-type="akhir">Simpan</button>
                                        </p>
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group" id="hasil_review" style="display: none">
                    <label for="rating">Review Anda :</label>
                    <h5><span id="show_rating">
                            <div class="rating">
                                <input type="radio" id="star55" name="rating2" value="5" />
                                <label for="star5" title="5 stars">&#9733;</label>
                                <input type="radio" id="star44" name="rating2" value="4" />
                                <label for="star4" title="4 stars">&#9733;</label>
                                <input type="radio" id="star33" name="rating2" value="3" />
                                <label for="star3" title="3 stars">&#9733;</label>
                                <input type="radio" id="star22" name="rating2" value="2" />
                                <label for="star2" title="2 stars">&#9733;</label>
                                <input type="radio" id="star11" name="rating2" value="1" />
                                <label for="star1" title="1 star">&#9733;</label>
                            </div>
                            - <span id="show_review"></span>
                        </span>
                    </h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <span id="btnProses"></span>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        $("#img_jaminan").on("change", function() {
            previewImg(this, '#output');
        });

        $("#input_kondisi_awal").on("change", function() {
            previewImg(this, '#img_kondisi_awal_preview');
        });

        // Pratinjau gambar untuk input file 'input_kondisi_akhir'
        $("#input_kondisi_akhir").on("change", function() {
            previewImg(this, '#img_kondisi_akhir_preview');
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

        let harga
        let no_wa
        let id_pesanan_studio
        let id_user_login = "{{ Auth::user()->id_user }}"

        function show_byID(id_pesanan_jadwal_studio) {
            id_pesanan_studio = id_pesanan_jadwal_studio
            $.ajax({
                url: `{{ url('/showById_pesanan_jadwal_studio/${id_pesanan_jadwal_studio}') }}`,
                method: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response.no_wa);

                    $("#no_wa_detail").text(`${response.no_wa}`)
                    $("#email").text(response.email)
                    $("#tgl_pengajuan").text("Pengajuan pada : " + response.tgl_pinjam)
                    $("#nama_user1").text(response.username)
                    $("#tanggal").text(response.tgl_pinjam + " / " + response.waktu_mulai + " - " + response
                        .waktu_selesai)

                    $("#show_review").text(response.review)
                    $('input[type="radio"][name="rating2"][value="' + response.rating + '"]').prop('checked',
                        true);
                    $("#id_pesanan_jadwal_studio1").val(response.id_pesanan_jadwal_studio)




                    if (response.review != null && response.rating != null) {
                        $("#hasil_review").show()
                    } else {
                        $("#hasil_review").hide()
                    }

                    let status1 = "";
                    let color1 = "";
                    if (response.status_persetujuan === "P") {
                        $("#btnProses").html(`<button type="button" class="btn btn-success" onclick="data_status(${response.id_pesanan_jadwal_studio})"
                    data-bs-toggle="modal" data-bs-target="#status_jadwal_studio">Proses</button>`)
                        status1 = "Pengajuan"
                        color1 = "warning"
                    } else if (response.status_persetujuan === "Y") {
                        status1 = "Disetujui"
                        color1 = "success"
                    } else if (response.status_persetujuan === "N") {
                        status1 = "Ditolak"
                        color1 = "danger"
                    }
                    $("#status_setuju").html(`<span class="badge bg-${color1}">${status1}</span>`)

                    let status2 = "";
                    let color2 = "";

                    if (response.status_peminjaman === "Y") {
                        status2 = "Telah Selesai"
                        color2 = "success"
                    } else if (response.status_peminjaman === "N") {
                        status2 = "Proses"
                        color2 = "warning"
                    }

                    $("#status_pinjam").html(`<span class="badge bg-${color2}">${status2}</span>`)

                    $("#catatan").text(response.ket_keperluan)
                    $("#catatan_admin").text(response.ket_admin)
                    $('#img_jaminan1').attr('src', '{{ asset('storage/img_upload/pesanan_jadwal') }}/' +
                        response.img_jaminan);

                    if (response.img_kondisi_awal == null) {
                        $('#show_kondisi_awal').hide();
                        $('#show_form_kondisi_awal').show();
                    } else {
                        $('#show_form_kondisi_awal').hide();
                        $('#show_kondisi_awal img').attr('src',
                                '{{ asset('storage/img_upload/kondisi/awal') }}/' + response.img_kondisi_awal)
                            .show();
                        $("#link-img-kondisi-awal").attr("href",
                            "{{ asset('storage/img_upload/kondisi/awal') }}/" + response.img_kondisi_awal);
                        $('#show_kondisi_awal').show();
                    }

                    if (response.img_kondisi_akhir == null) {
                        $('#show_kondisi_akhir').hide();
                        $('#show_form_kondisi_akhir').show();
                    } else {
                        $('#show_form_kondisi_akhir').hide();
                        $('#show_kondisi_akhir img').attr('src',
                                '{{ asset('storage/img_upload/kondisi/akhir') }}/' + response.img_kondisi_akhir)
                            .show();
                        $("#link-img-kondisi-akhir").attr("href",
                            "{{ asset('storage/img_upload/kondisi/akhir') }}/" + response.img_kondisi_akhir);
                        $('#show_kondisi_akhir').show();
                    }
                }
            });
        }

        // function get_snap_token(id_pesanan_jadwal_studio) {
        //     let nama_user = $("#nama_user1").val()


        //     $.ajax({
        //         url: `{{ url('/get_snap_token') }}`,
        //         method: 'get',
        //         dataType: 'json',
        //         data: {
        //             'no_wa': no_wa,
        //             'harga_perawatan': harga,
        //             "nama_user": nama_user,
        //             "_token": "{{ csrf_token() }}"
        //         },
        //         success: function(response) {
        //             window.snap.pay(response, {
        //                 onSuccess: function(result) {
        //                     alert("payment success!");

        //                     location.href = '/jadwal_studio_saya'

        //                     $.ajax({
        //                         url: `{{ url('/pembayaran_studio_sukses') }}`,
        //                         method: 'post',
        //                         dataType: 'json',
        //                         data: {
        //                             'id_pesanan_jadwal_studio': id_pesanan_jadwal_studio,
        //                             "_token": "{{ csrf_token() }}"
        //                         },
        //                         success: function(response) {}
        //                     })

        //                 },
        //                 onPending: function(result) {
        //                     /* You may add your own implementation here */
        //                     alert("wating your payment!");
        //                     console.log(result);
        //                 },
        //                 onError: function(result) {
        //                     /* You may add your own implementation here */
        //                     alert("payment failed!");
        //                     console.log(result);
        //                 },
        //                 onClose: function() {
        //                     /* You may add your own implementation here */
        //                     alert('you closed the popup without finishing the payment');
        //                     return
        //                 }
        //             })
        //         }
        //     })
        // }

        $(document).ready(function() {
            $('#form_kondisi_awal').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: `{{ url('/simpan_img_kondisi_ruangan/${id_pesanan_studio}') }}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btn_simpan_awal').prop('disabled', true).text('Menyimpan...');
                    },
                    success: function(response) {
                        alert('Kondisi awal berhasil disimpan.');
                        $('#btn_simpan_awal').prop('disabled', false).hide();
                        $('#input_kondisi_awal').hide();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                        $('#btn_simpan_awal').prop('disabled', false).text('Simpan');
                    }
                });
            });

            $('#form_kondisi_akhir').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: `{{ url('/simpan_img_kondisi_ruangan/${id_pesanan_studio}') }}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btn_simpan_akhir').prop('disabled', true).text('Menyimpan...');
                    },
                    success: function(response) {
                        alert('Kondisi akhir berhasil disimpan.');
                        $('#btn_simpan_akhir').prop('disabled', false).hide();
                        $('#input_kondisi_akhir').hide();

                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
                        $('#btn_simpan_akhir').prop('disabled', false).text('Simpan');
                    }
                });
            });
        });
    </script>
@endpush
