{{-- DETAIL JASA --}}
<div class="modal fade" id="detail_pesanan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                                <th>Nama</th>
                                <th>Tanggal & Jam Produksi</th>
                                <th>Tenggat Produksi</th>
                                <th>Keterangan</th>
                                {{-- <th>Biaya Perawatan</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td id="nama_user"></td>
                                <td id="tanggal"></td>
                                <td id="tenggat"></td>
                                <td id="catatan"></td>
                                {{-- <td id="biaya"></td> --}}
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive m-3">
                    <table>
                        <thead>
                            {{-- <tr>
                                <td>Metode Pembayaran</td>
                                <td>:</td>
                                <td>Qris</td>
                            </tr> --}}
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
                            {{-- <tr>
                                <td>Status Pembayaran</td>
                                <td>:</td>
                                <td id="status_bayar"></td>
                            </tr> --}}
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
                {{-- <button type="button" class="btn btn-info btn-lg">Bayar </button> --}}
            </div>
        </div>
    </div>
</div>

<script>
    // function formatRupiah(angka) {
    //     var reverse = angka.toString().split('').reverse().join('');
    //     var ribuan = reverse.match(/\d{1,3}/g);
    //     var formatted = ribuan.join('.').split('').reverse().join('');
    //     return 'Rp ' + formatted;
    // }

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

                $("#tgl_pengajuan").text("Pengajuan pada : " + response.tgl_produksi)
                $("#nama_user").text(response.username)
                $("#tanggal").text(response.tgl_produksi)
                $("#tenggat").text(response.tenggat_produksi)
                // $("#biaya").text(formatRupiah(response.biaya_paket))
                $("#komentar_rating").text(response.review)
                $("#id_pesanan_jasa_musik").val(id_pesanan_jasa_musik)
                $('input[type="radio"][name="rating"][value="' + response.rating + '"]').prop('checked',
                    true);

                // STATUS PEMBAYARAN
                // let status = "";
                // let color = "";

                // if (response.status_pembayaran === "Y") {
                //     status = "Lunas"
                //     color = "success"
                // } else if (response.status_pembayaran === "N") {
                //     status = "Belum Bayar"
                //     color = "warning"
                // }
                // $("#status_bayar").html(`<span class="badge bg-${color}">${status}</span>`)

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

                // btn bayar
                // if (response.status_persetujuan === "Y" && response.status_pembayaran === "N") {
                //     $("#BtnBayar").show().html(
                //         ` <button type="button" class="btn btn-info btn-lg text-white" onclick=get_snap_token(${id_pesanan_jasa_musik})>Bayar </button>`
                //     )
                // } else {
                //     $("#BtnBayar").hide()
                // }
            }
        });
    }

    // function show_byID(id_pesanan_jasa_musik) { 
    //     $.ajax({
    //         url: `{{ url('/showById_pesanan_jasa_musik/${id_pesanan_jasa_musik}') }}`,
    //         method: 'POST',
    //         dataType: 'json',
    //         data: {
    //             "_token": "{{ csrf_token() }}"
    //         },
    //         success: function(response) {
    //             $("#tgl_pengajuan").text("Pengajuan pada : " + response.tgl_produksi)
    //             $("#nama_user").text(response.username)
    //             $("#tanggal").text(response.tgl_produksi)
    //             $("#tenggat").text(response.tenggat_produksi)
    //             $("#biaya").text(formatRupiah(response.biaya_produksi))

    //             // STATUS PEMBAYARAN
    //             let status = "";
    //             let color = "";

    //             if (response.status_pembayaran === "Y") {
    //                 status = "Lunas"
    //                 color = "success"
    //             } else if (response.status_pembayaran === "N") {
    //                 status = "Belum Bayar"
    //                 color = "warning"
    //             }
    //             $("#status_bayar").html(`<span class="badge bg-${color}">${status}</span>`)

    //             // STATUS PERSETUJUAN
    //             let status1 = "";
    //             let color1 = "";
    //             if (response.status_persetujuan === "P") {
    //                 status1 = "Pengajuan"
    //                 color1 = "warning"
    //             } else if (response.status_persetujuan === "Y") {
    //                 status1 = "Disetujui"
    //                 color1 = "success"
    //             } else if (response.status_persetujuan === "N") {
    //                 status1 = "Ditolak"
    //                 color1 = "danger"
    //             }
    //             $("#status_setuju").html(`<span class="badge bg-${color1}">${status1}</span>`)

    //             // STATUS PRODUKSI
    //             let status2 = "";
    //             let color2 = "";
    //             if (response.status_produksi === "P") {
    //                 status2 = "Diproses"
    //                 color2 = "primary"
    //             } else if (response.status_produksi === "Y") {
    //                 status2 = "Selesai"
    //                 color2 = "success"
    //             } else if (response.status_produksi === "N") {
    //                 status2 = "Diajukan"
    //                 color2 = "warning"
    //             }
    //             $("#status_produksi").html(`<span class="badge bg-${color2}">${status2}</span>`)


    //             $("#catatan").text(response.keterangan)
    //             $("#catatan_admin").text(response.keterangan_admin)
    //         }
    //     });
    // }
</script>
