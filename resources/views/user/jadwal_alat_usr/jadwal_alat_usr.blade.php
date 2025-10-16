@extends('partials.main')
@section('MainContent')
    <div class="page-heading">
        <h3>Alat Dipinjam</h3>
    </div>

    <div class="mb-3">
<<<<<<< Updated upstream
        <a href=""><button class="btn btn-info icon icon-left text-white"><i class="bi bi-arrow-repeat"></i>
                Refresh</button>
        </a>

        @if ($cek_pesanan)
            <button type="button" class="btn btn-primary icon icon-left" onclick="btnJadwalGagal()"><i
                    class="bi bi-plus-lg"></i>
                Pinjam Alat
            </button>
        @else
            <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')"><i
                    class="bi bi-plus-lg"></i>
                Pinjam Alat
=======
        <button id="btnRefresh" type="button" class="btn btn-info icon icon-left text-white">
            <i class="bi bi-arrow-repeat"></i> Refresh
        </button>

        @if ($cek_pesanan)
            <button type="button" class="btn btn-primary icon icon-left" onclick="btnJadwalGagal()">
                <i class="bi bi-plus-lg"></i> Pinjam Alat
            </button>
        @else
            <button type="button" class="btn btn-primary icon icon-left" onclick="openModal('add')">
                <i class="bi bi-plus-lg"></i> Pinjam Alat
>>>>>>> Stashed changes
            </button>
        @endif
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tableJadwalAlat">
                        <thead>
                            <tr>
<<<<<<< Updated upstream
                                <th>No.</th>
                                <th>Foto Alat</th>
                                <th>Nama Alat</th>
                                <th>Jumlah Alat</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Dikembalikan</th>
                                
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
=======
                            <th>No.</th>
                            <th>Foto Alat</th>
                            <th>Nama Alat</th>
                            <th>Jumlah Alat</th>
                            <th>Biaya Perawatan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Dikembalikan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
>>>>>>> Stashed changes
                    </table>
                </div>
            </div>
        </div>
    </section>

<<<<<<< Updated upstream
    @include('user.jadwal_alat_usr.md_add_pinjam_alat_usr')

    @push('script')
        <script>
            $(document).ready(function() {
                $('#tableJadwalAlat').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    searching: true,
                    "columnDefs": [{
                        "width": "100%",
                    }],
                    layout: {
                        topStart: {
                            buttons: ['excel', 'pdf']
                        }
                    },
                    ajax: {
                        url: "{{ url('/fetch_alat_dipinjam') }}",
                        type: 'GET',
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: null,
                            render: function(data) {
                                return `<a target="_blank" href="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}"><img src="{{ asset('storage/img_upload/data_alat') }}/${data.foto_alat}" class="foto_alat" max-width="90px" max-height="60px"></a>`;
                            }
                        },
    
                        {
                            data: 'nama_alat',
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'tgl_pinjam'
                        },
                        {
                            data: 'tgl_kembali'
                        },
                        
                        {
                            data: null,
                            render: function(data) {

                                let statusPersetujuan = "";
                                let colorPersetujuan = "";

                                if (data.status_persetujuan === "P") {
                                    statusPersetujuan = "Pengajuan"
                                    colorPersetujuan = "warning"
                                } else if (data.status_persetujuan === "Y") {
                                    statusPersetujuan = "Disetujui"
                                    colorPersetujuan = "success"
                                } else if (data.status_persetujuan === "N") {
                                    statusPersetujuan = "Ditolak"
                                    colorPersetujuan = "danger"
                                }

                                return `
                                    <div>
                                        <a type="button" class="badge bg-${colorPersetujuan}">
                                            ${statusPersetujuan}
                                        </a>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: null,
                            render: function(data) {

                                let textPersetujuan = "Detail";
                                let colorBtn = "primary"

                                if (data.status_persetujuan === "Y" && data.status_peminjaman === "Y" &&
                                    data.review === null && data.rating === null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-warning icon icon-left text-white"
                                                    data-bs-toggle="modal" data-bs-target="#rating" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Beri Rating
                                                </button>
                                            </div>

                                             <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    ${textPersetujuan}
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                } else if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                    "Y" &&
                                    data.review !== null && data.rating !== null) {
                                    return `
                                        <td>
                                            <div style="margin-rigth=20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Selesai
                                                </button>
                                            </div>
                                        </td>
                                    `;
                                }

                                // <button type="button" class="btn btn-info icon icon-left text-white" onclick="openModal('edit', '${data.id_pesanan_pinjam_alat}')">
                                //                     <i class="bi bi-pencil-square"></i>
                                // </button>

                                if (data.status_persetujuan === "P" && data.status_pengajuan !== "X") {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                            
                                                <button type="button" class="btn btn-danger icon icon-left text-white" onclick="hapus_jadwal(${data.id_pesanan_pinjam_alat})">
                                                    Batalkan
                                                </button>

                                                <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Detail
                                                </button>
                                            </div>
                                        </td>
                                    `;

                                } else if (data.status_persetujuan === "Y" && data.status_peminjaman ===
                                    "N") {
                                    return `
                                            <td>
                                                <div style="margin-right: 20px;">
                                                    <button type="button" class="btn btn-warning icon icon-left text-white" onclick="PengembalianAlat(${data.id_pesanan_pinjam_alat})">
                                                        Pengembalian
                                                    </button>

                                                    <button type="button" class="btn btn-${colorBtn} icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                        ${textPersetujuan}
                                                    </button>
                                                </div>
                                            </td>
                                        `;
                                } else if (data.status_pengajuan === "X" && data.status_persetujuan ===
                                    "P") {
                                    return `
                                    <td><i class="text-danger">Dibatalkan</i></td>
                                    `;
                                } else {
                                    return `
                                        <td>
                                            <div style="margin-right: 20px;">
                                                <button type="button" class="btn btn-success icon icon-left text-white" data-bs-toggle="modal" data-bs-target="#detail_alat" onclick="show_byID(${data.id_pesanan_pinjam_alat})">
                                                    Selesai
                                                </button>
                                            </div>
                                            
                                        </td>
                                    `;

                                }
                            }
                        }
                    ],
                });
            })


        </script>
        
    @endpush

        

   
@endsection
=======
    @include('user.jadwal_alat_usr.md_detail_pinjam_alat_usr')
    @include('user.jadwal_alat_usr.md_add_pinjam_alat_usr', ['alat' => $alat ?? collect()])

    @push('script')
    <script>
        
        function sleep(ms){ return new Promise(r=>setTimeout(r,ms)); }

        function setReadOnly(isRO) {
            const $m = $('#add_pinjam_alat');

            $m.find('input, select, textarea').prop('disabled', isRO);

            $m.find('[data-repeater-create]').toggle(!isRO);
            $m.find('[data-repeater-delete]').toggle(!isRO);

            $('#BtnJadwalAlat').toggle(!isRO);
            }
            function clearRepeater() {
            const $list = $('.repeater [data-repeater-list]');
            $list.empty();
            }

            async function fillRepeaterReadOnly(details){
                const $modal  = $('#add_pinjam_alat');
                const $create = $modal.find('[data-repeater-create]');
                const $list   = $modal.find('[data-repeater-list]');

                clearRepeater();

                if (!details || !details.length){
                    $create.trigger('click');
                    await sleep(200);
                    const $it = $list.children('[data-repeater-item]').last();
                    $it.find('select, input').prop('disabled', true);
                    $it.find('[data-repeater-delete]').hide();
                    $create.hide();
                    return;
                }

                for (const d of details){
                    $create.trigger('click');
                    await sleep(250);

                    const $it = $list.children('[data-repeater-item]').last();

                    $it.find('select[name$="[id_alat]"], select[name="id_alat"]').val(String(d.id_alat)).trigger('change');
                    $it.find('input[name$="[jumlah]"], input[name="jumlah"]').val(d.jumlah);

                    $it.find('select, input').prop('disabled', true);
                    $it.find('[data-repeater-delete]').hide();
                }

            $create.hide();

            $('#id_alat, #jumlah').closest('.form-group, .col-12, .row').hide();
        }
        
        function clearRepeater(){
            const $list = $('.repeater [data-repeater-list]');
            $list.children('[data-repeater-item]').remove();
        }

        (function () {
        const CSRF = "{{ csrf_token() }}";
        let currentPinjamId = null;
        let currentNoWA = null;

        $(function () {
            const $tbl = $('#tableJadwalAlat');

            function badgePersetujuan(code){
            let text='Pengajuan', cls='warning';
            if(code==='Y'){ text='Disetujui'; cls='success'; }
            if(code==='N'){ text='Ditolak';   cls='danger';  }
            return `<span class="badge bg-${cls}">${text}</span>`;
            }

            function actionButtons(row){
            if(row.status_persetujuan==='P'){
                return `<div>
                <button class="btn btn-danger text-white" onclick="hapus_jadwal(${row.id_pesanan_pinjam_alat})">Batalkan</button>
                <button class="btn btn-primary text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Detail</button>
                </div>`;
            }
            if(row.status_persetujuan==='Y' && row.status_pengembalian==='N'){
                return `<div>
                <button class="btn btn-primary text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Detail</button>
                </div>`;
            }
            return `<div><button class="btn btn-success text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Selesai</button></div>`;
            }

            $('#tableJadwalAlat').DataTable({
                processing:true,
                serverSide:true,
                paging:true,
                searching:true,
                ajax:{ url:"{{ url('/fetch_alat_dipinjam') }}", type:"GET" },
                columns:[
                    { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
                        {
                        data:null, orderable:false, searchable:false,
                        render:function(row){
                            const foto = row.foto_alat || (row.details?.[0]?.alat?.foto_alat) || null;
                            const fallback = "{{ asset('images/no-image.png') }}";
                            if(!foto) return `<img src="${fallback}" style="max-width:90px;max-height:60px">`;
                            const src = "{{ asset('storage/img_upload/data_alat') }}/"+foto;
                            return `<a target="_blank" href="${src}">
                                    <img src="${src}" style="max-width:90px;max-height:60px"
                                        onerror="this.onerror=null;this.src='${fallback}'">
                                    </a>`;
                        }
                    },
                        {
                        data:null, searchable:true,
                        render:function(row){
                            if(row.nama_alat) return row.nama_alat;
                            const list=(row.details||[]).map(d=>d.alat?.nama_alat ?? d.nama_alat).filter(Boolean);
                            return list.length?list.join('<br>'):'-';
                        }
                    },
                        {
                        data:null, searchable:false,
                        render:function(row){
                            if(row.details?.length){
                            const total=row.details.reduce((s,d)=>s+parseInt(d.jumlah||0),0);
                            return total;
                            }
                            return row.jumlah ?? '-';
                        }
                    },
                        {
                        data: 'total_biaya_perawatan',
                        name: 'total_biaya_perawatan',
                        searchable: false,
                        className: 'text-end',
                        render: function(val){
                            const n = Number(val || 0);
                            return 'Rp' + new Intl.NumberFormat('id-ID').format(n);
                        }
                    },

                    { data:'tgl_pinjam',  name:'tgl_pinjam' },
                    { data:'tgl_kembali', name:'tgl_kembali' },
                    { data:'status_persetujuan', name:'status_persetujuan',
                    render:function(code){
                        const map={P:['Pengajuan','warning'],Y:['Disetujui','success'],T:['Ditolak','danger'],N:['Ditolak','danger']};
                        const [txt,cls]=map[code]??['-','secondary'];
                        return `<span class="badge bg-${cls}">${txt}</span>`;
                    }
                    },
                    { data:null, orderable:false, searchable:false,
                    render:function(row){
                        if(row.status_persetujuan==='P'){
                        return `<div class="d-flex gap-1">
                                    <button class="btn btn-danger btn-sm text-white" onclick="hapus_jadwal(${row.id_pesanan_pinjam_alat})">Batalkan</button>
                                    <button class="btn btn-primary btn-sm text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Detail</button>
                                </div>`;
                        }
                        if(row.status_persetujuan==='Y' && row.status_pengembalian==='N'){
                        return `<div class="d-flex gap-1">
                                    <button class="btn btn-primary btn-sm text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Detail</button>
                                </div>`;
                        }
                        return `<button class="btn btn-success btn-sm text-white" onclick="show_byID(${row.id_pesanan_pinjam_alat})">Selesai</button>`;
                    }
                    }
                ],
                columnDefs:[{ targets:'_all', defaultContent:'-' }]
                });
            $('#btnRefresh').on('click', function(e){
                e.preventDefault();
                $('#tableJadwalAlat').DataTable().ajax.reload(null, false);
            });
        });

        window.show_byID = function (id) {
            currentPinjamId = id;
            $.ajax({
            url: "{{ url('/showById_pesanan_pinjam_alat') }}/" + id,
            method: "POST",
            dataType: "json",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                currentNoWA = res.no_wa || null;

                $("#tgl_pengajuan").text("Pengajuan pada : " + (res.tgl_pinjam ?? "-"));
                $("#nama_user1").text(res.username ?? "-");
                $("#tanggal").text(`${res.tgl_pinjam ?? "-"} / ${res.waktu_mulai ?? "-"} - ${res.waktu_selesai ?? "-"}`);
                $("#catatan").text(res.ket_keperluan ?? "-");
                $("#catatan_admin").val(res.ket_admin ?? "");

                $('input[name=\"rating2\"]').prop('checked', false);
                if (res.rating) $('input[name=\"rating2\"][value=\"'+res.rating+'\"]').prop('checked', true);
                $("#show_review").text(res.review ?? "");
                $("#hasil_review").toggle(!!(res.review || res.rating));

                const st = { P:['Pengajuan','warning'], Y:['Disetujui','success'], N:['Ditolak','danger'] }[res.status_persetujuan] ?? ['-','secondary'];
                $("#status_persetujuan").html(`<span class="badge bg-${st[1]}">${st[0]}</span>`);

                const sp = res.status_pengembalian === "Y" ? ['Telah Selesai','success'] : ['Proses','warning'];
                $("#status_pinjam").html(`<span class="badge bg-${sp[1]}">${sp[0]}</span>`);

                if (res.foto_jaminan) {
                const srcJ = "{{ asset('storage/img_upload/pesanan_jadwal') }}/" + res.foto_jaminan;
                $("#foto_jaminan1").attr("src", srcJ).show();
                $("#link-foto-jaminan").attr("href", srcJ).show();
                } else {
                $("#foto_jaminan1").attr("src","").hide();
                $("#link-foto-jaminan").attr("href","").hide();
                }

                if (res.img_kondisi_awal) {
                const a = "{{ asset('storage/img_upload/kondisi/awal') }}/" + res.img_kondisi_awal;
                $("#img_kondisi_awal").attr("src", a).show();
                $("#link-img-kondisi-awal").attr("href", a);
                $("#show_kondisi_awal").show();
                $("#show_form_kondisi_awal").hide();
                } else {
                $("#show_kondisi_awal").hide();
                $("#show_form_kondisi_awal").show();
                }

                if (res.img_kondisi_akhir) {
                    const b = "{{ asset('storage/img_upload/kondisi/akhir') }}/" + res.img_kondisi_akhir;
                    $("#img_kondisi_akhir").attr("src", b).show();
                    $("#link-img-kondisi-akhir").attr("href", b);
                    $("#show_kondisi_akhir").show();
                    $("#show_form_kondisi_akhir").hide();
                } else {
                    $("#show_kondisi_akhir").hide();
                    $("#show_form_kondisi_akhir").show();
                }

                const el = document.getElementById('detail_alat');
                if (!el) { console.error('Modal #detail_alat tidak ditemukan'); return; }

                let inst = bootstrap.Modal.getInstance(el);
                if (inst) inst.dispose();

                inst = new bootstrap.Modal(el, { backdrop: 'static', keyboard: false });
                inst.show();
                },
                error: function (xhr) {
                const msg = xhr.status === 404 ? 'Data tidak ditemukan.' : 'Terjadi kesalahan saat memproses data.';
                Swal.fire({ icon:"error", title:"Oops...", text: msg });
                }
            });
        };

        window.PengembalianAlat = function (id) {
            Swal.fire({
                title: 'Konfirmasi pengembalian?',
                text: 'Pastikan alat sudah kembali.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, kembalikan'
            }).then(res => {
                if (!res.isConfirmed) return;

                $.ajax({
                url: "{{ url('/pengembalian_alat') }}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}", id_pesanan_pinjam_alat: id },
                success: () => {
                    $('#tableJadwalAlat').DataTable().ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Pengembalian dicatat', timer: 1300, showConfirmButton: false });
                },
                error: (xhr) => {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message || 'Server error' });
                }
                });
            });
        };

        window.hapus_jadwal = function(id){
            Swal.fire({ title:'Batalkan pengajuan ini?', icon:'warning', showCancelButton:true, confirmButtonText:'Ya, batalkan' })
            .then(res => {
                if (!res.isConfirmed) return;
                $.post("{{ url('/pesanan-pinjam-alat') }}/" + id, { _token:"{{ csrf_token() }}", _method:'DELETE' })
                .done(() => {
                    $('#tableJadwalAlat').DataTable().ajax.reload(null,false);
                    Swal.fire({ icon:'success', title:'Dibatalkan', timer:1200, showConfirmButton:false });
                })
                .fail(xhr => Swal.fire({ icon:'error', title:'Oops...', text: (xhr.responseJSON?.message || 'Gagal membatalkan.') }));
            });
        };
        })();
        </script>
    @endpush
@endsection
>>>>>>> Stashed changes
