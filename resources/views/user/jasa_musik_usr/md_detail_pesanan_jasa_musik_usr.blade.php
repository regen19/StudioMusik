{{-- DETAIL JASA --}}
<div class="modal fade" id="detail_pesanan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Pesanan Jasa Musik</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fs-6" id="tgl_pengajuan"></p>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Jasa Musik</th>
                                <th>Mulai Produksi</th>
                                <th>Tenggat Produksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td id="nama_user"></td>
                                <td id="nama_jasa_musik"></td>
                                <td id="tanggal"></td>
                                <td id="tenggat"></td>
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
                                <td>Status Produksi</td>
                                <td>:</td>
                                <td id="status_produksi"></td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="table-responsive m-3">
                    <table>
                        <thead id="detail_informasi_pesanan">

                        </thead>
                    </table>
                </div>
                <div class="form-group">
                    <label for="catatan_admin">Keterangan Admin :</label>
                    <textarea class="form-control" name="catatan_admin" id="catatan_admin" cols="30" rows="3" readonly></textarea>
                </div>

                <div class="form-group" id="hasil_review" style="display: none">
                    <label for="rating">Review Anda :</label>
                    <h5><span id="show_rating">
                            <div class="rating">
                                <input type="radio" id="star55" name="rating1" value="5" />
                                <label for="star5" title="5 stars">&#9733;</label>
                                <input type="radio" id="star44" name="rating1" value="4" />
                                <label for="star4" title="4 stars">&#9733;</label>
                                <input type="radio" id="star33" name="rating1" value="3" />
                                <label for="star3" title="3 stars">&#9733;</label>
                                <input type="radio" id="star22" name="rating1" value="2" />
                                <label for="star2" title="2 stars">&#9733;</label>
                                <input type="radio" id="star11" name="rating1" value="1" />
                                <label for="star1" title="1 star">&#9733;</label>
                            </div>
                            - <span id="show_review"></span>
                        </span>
                    </h5>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function show_byID(id_pesanan_jasa_musik) {
        $.ajax({
            url: `{{ url('/showById_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
            method: 'POST',
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log(response)

                $("#tgl_pengajuan").text("Pengajuan pada : " + waktu(response.created_at))
                $("#nama_user").text(response.users.username)
                $("#nama_jasa_musik").text(response.master_jasa_musik.nama_jenis_jasa)
                $("#tanggal").html(
                    response.tgl_produksi ? response.tgl_produksi.split(" ")[0] :
                    `<div>
                        <a type="button" class="badge bg-warning">
                            Diajukan
                        </a>
                    <div>`
                )
                $("#tenggat").html(response.tenggat_produksi.split(" ")[0])
                $("#show_review").text(response.review)
                $("#id_pesanan_jasa_musik").val(id_pesanan_jasa_musik)
                $('input[type="radio"][name="rating1"][value="' + response.rating + '"]').prop('checked',
                    true);

                if (response.review != null && response.rating != null) {
                    $("#hasil_review").show()
                } else {
                    $("#hasil_review").hide()
                }

                // STATUS PERSETUJUAN
                let status1 = "";
                let color1 = "";
                if (response.status_persetujuan === "P") {
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

                // STATUS PRODUKSI
                let status2 = "";
                let color2 = "";
                if (response.status_produksi === "P") {
                    status2 = "Diproses"
                    color2 = "primary"
                } else if (response.status_produksi === "Y") {
                    status2 = "Selesai"
                    color2 = "success"
                } else if (response.status_produksi === "N") {
                    status2 = "Diajukan"
                    color2 = "warning"
                }
                $("#status_produksi").html(`<span class="badge bg-${color2}">${status2}</span>`)


                $("#catatan").text(response.keterangan)
                $("#catatan_admin").text(response.keterangan_admin)
                $.ajax({
                    url: `{{ url('/informasi_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $("#detail_informasi_pesanan").empty();
                        const baseUrl = "{{ url('/') }}";
                        response.forEach((value, index) => {
                            $("#detail_informasi_pesanan").append(`
                            <tr>
                                <td>${value.nama_field}</td>
                                <td>:</td>
                                <td>${value.tipe_field == "file" ?
                                        `<a href="${baseUrl}/download_file_pesanan/${value.value_field}" class="btn btn-primary">
                                            Download File
                                        </a>`
                                        :
                                        `<p style="white-space: pre-wrap;" class = "mb-0 ">${value.value_field}</p>`

                                     }
                                </td>
                            </tr>
                        `)
                        });
                    }
                });
            }
        });
    }
</script>
