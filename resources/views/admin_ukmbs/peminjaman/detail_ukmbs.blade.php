<div class="modal fade" id="detail_alat_ukmbs" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Detail Pesanan Pinjam Alat</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- header sama seperti user --}}
        <p class="fs-6" id="k_tgl_pengajuan"></p>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead><tr><th>No.</th><th>Nama Peminjam</th><th>Tanggal & Jam Pinjam</th><th>Keperluan</th></tr></thead>
            <tbody><tr>
              <td>1</td><td id="k_nama_user"></td><td id="k_tanggal"></td><td id="k_catatan"></td>
            </tr></tbody>
          </table>
        </div>

        <div class="table-responsive m-3">
          <table>
            <tbody>
              <tr><td>Status Persetujuan</td><td class="px-2">:</td><td id="k_status_persetujuan"></td></tr>
              <tr><td>Status Peminjaman</td> <td class="px-2">:</td><td id="k_status_pinjam"></td></tr>
            </tbody>
          </table>
        </div>

        <div class="mb-3">
          <label class="form-label">Keterangan Admin :</label>
          <textarea class="form-control" id="k_ket_admin" rows="2" readonly></textarea>
        </div>

        {{-- upload UKMBS --}}
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Jaminan</th>
                <th>KONDISI ALAT SEBELUM DIGUNAKAN</th>
                <th>KONDISI ALAT SETELAH DIGUNAKAN</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <a id="k_link_jaminan" target="_blank" class="my-3">
                    <img id="k_img_jaminan" style="max-width:200px;max-height:200px"/>
                  </a>
                </td>

                {{-- awal --}}
                <td>
                  <div id="k_awal_view" class="mb-2" style="display:none">
                    <a id="k_link_awal" target="_blank" class="my-3"><img id="k_img_awal" style="max-width:200px;max-height:200px"/></a>
                  </div>
                  <form id="k_form_awal" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="kondisi_awal" id="k_input_awal" class="form-control" accept="image/*">
                    <img id="k_prev_awal" style="display:none;max-width:200px;max-height:200px" class="my-2"/>
                    <button type="button" class="btn btn-success" id="k_btn_awal">Simpan</button>
                  </form>
                </td>

                {{-- akhir --}}
                <td>
                  <div id="k_akhir_view" class="mb-2" style="display:none">
                    <a id="k_link_akhir" target="_blank" class="my-3"><img id="k_img_akhir" style="max-width:200px;max-height:200px"/></a>
                  </div>
                  <form id="k_form_akhir" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="kondisi_akhir" id="k_input_akhir" class="form-control" accept="image/*">
                    <img id="k_prev_akhir" style="display:none;max-width:200px;max-height:200px" class="my-2"/>
                    <button type="button" class="btn btn-success" id="k_btn_akhir">Simpan</button>
                  </form>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        {{-- tidak ada form review di UKMBS --}}
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function($){
  const $m = $('#detail_alat_ukmbs');

  function badge(txt, type){ return `<span class="badge bg-${type}">${txt}</span>`; }
  function prev(input, imgSel){ const f=input?.files?.[0]; if(!f) return; const r=new FileReader(); r.onload=e=>$(imgSel).attr('src',e.target.result).show(); r.readAsDataURL(f); }
  $('#k_input_awal').on('change', function(){ prev(this,'#k_prev_awal'); });
  $('#k_input_akhir').on('change',function(){ prev(this,'#k_prev_akhir'); });

  window.openDetailUkmbs = function(id){
    $.getJSON("{{ route('pinjam-alat.detail',['id'=>'__ID__']) }}".replace('__ID__', id))
      .done(res => {
        $("#k_tgl_pengajuan").text(`Pengajuan pada : ${res.tgl_pinjam}`);
        $("#k_nama_user").text(res.username);
        $("#k_tanggal").text(`${res.tgl_pinjam} / ${res.waktu_mulai} - ${res.waktu_selesai}`);
        $("#k_catatan").text(res.ket_keperluan);
        $("#k_ket_admin").val(res.ket_admin || '');

        const sPers = res.status_persetujuan==='Y' ? badge('Disetujui','success')
                   : res.status_persetujuan==='N' ? badge('Ditolak','danger')
                   : badge('Pengajuan','warning');
        $("#k_status_persetujuan").html(sPers);
        const sPinj = res.status_pengembalian==='Y' ? badge('Selesai','success') : badge('Proses','warning');
        $("#k_status_pinjam").html(sPinj);

        if (res.url_foto_jaminan) {
          $("#k_img_jaminan").attr('src',res.url_foto_jaminan).show();
          $("#k_link_jaminan").attr('href',res.url_foto_jaminan).show();
        } else { $("#k_img_jaminan,#k_link_jaminan").hide(); }

        if (res.url_kondisi_awal) {
          $("#k_img_awal").attr('src',res.url_kondisi_awal); $("#k_link_awal").attr('href',res.url_kondisi_awal);
          $("#k_awal_view").show(); $("#k_form_awal")[0].reset(); $("#k_prev_awal").hide();
        } else { $("#k_awal_view").hide(); }

        if (res.url_kondisi_akhir) {
          $("#k_img_akhir").attr('src',res.url_kondisi_akhir); $("#k_link_akhir").attr('href',res.url_kondisi_akhir);
          $("#k_akhir_view").show(); $("#k_form_akhir")[0].reset(); $("#k_prev_akhir").hide();
        } else { $("#k_akhir_view").hide(); }

        $m.data('id', id);
        bootstrap.Modal.getOrCreateInstance($m[0]).show();
      })
      .fail(()=> Swal.fire({icon:'error',title:'Oops...',text:'Gagal memuat detail.'}));
  };

  function upload(formSel, btnSel, fieldName){
    const id = $m.data('id');
    const url = "{{ route('ukmbs.peminjaman.kondisi.upload',['id'=>'__ID__']) }}".replace('__ID__', id);
    const fd = new FormData($(formSel)[0]); fd.append('_token', "{{ csrf_token() }}");
    $(btnSel).prop('disabled',true).text('Menyimpan...');
    $.ajax({url, method:'POST', data:fd, processData:false, contentType:false})
      .done(()=> openDetailUkmbs(id))
      .fail(xhr => alert(xhr.responseJSON?.message || 'Gagal upload'))
      .always(()=> $(btnSel).prop('disabled',false).text('Simpan'));
  }
  $('#k_btn_awal').on('click',  ()=> upload('#k_form_awal',  '#k_btn_awal',  'kondisi_awal'));
  $('#k_btn_akhir').on('click', ()=> upload('#k_form_akhir', '#k_btn_akhir', 'kondisi_akhir'));
})(jQuery);
</script>
@endpush