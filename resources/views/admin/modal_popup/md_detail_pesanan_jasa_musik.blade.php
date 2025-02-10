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
                                <th>Tanggal & Jam Produksi</th>
                                <th>Tenggat Produksi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td id="nama_user"></td>
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <span id="btnProses"></span>
                <span id="btnSelesaiProduksi"></span>
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

                no_wa = response.no_wa
                nama_user = response.username
                biaya_paket = response.biaya_paket

                $("#tgl_pengajuan").text("Pengajuan pada : " + waktu(response.created_at))
                $("#nama_user").text(response.users.username)
                $("#tanggal").html(
                    response.tgl_produksi ? response.tgl_produksi.split(" ")[0] :
                    `<div>
                        <a type="button" class="badge bg-warning">
                            Diajukan
                        </a>
                    <div>`
                )
                $("#tenggat").html(response.tenggat_produksi.split(" ")[0])
                $("#komentar_rating").text(response.review)
                $("#id_pesanan_jasa_musik").val(id_pesanan_jasa_musik)
                $('input[type="radio"][name="rating"][value="' + response.rating + '"]').prop('checked',
                    true);

                $("#btnProses").html(`<button type="button" class="btn btn-success" onclick="data_status(${response.id_pesanan_jasa_musik})"
                    data-bs-toggle="modal" data-bs-target="#status_persetujuan">Proses</button>`)
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
                                        value.value_field
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
