{{-- MODAL EDIT USER --}}
<div class="modal fade" id="add_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="title_header"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control mb-3" required>
                </div>

                <div class="form-group">
                    <label for="no_wa">No.WA</label>
                    <input type="text" class="form-control" name="no_wa" id="no_wa" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" name="password" id="password" required>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="BtnDataUser"></button>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>

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

        function openModal(action, id_user = null) {
            $("#add_user").modal("show");

            const $title_header = $("#title_header");
            const $btnDataUser = $("#BtnDataUser");

            const $username = $('#usename');
            const $no_wa = $('#no_wa');
            const $email = $('#email');
            const $password = $('#password');
            const $output = $('#output');

            if (action === 'add') {
                $title_header.text("Tambah Data User");
                $btnDataUser.text("Simpan");

                $username.val("");
                $no_wa.val("");
                $email.val("");
                $password.val("");
                $output.hide();

                $btnDataUser.off('click').on("click", function() {
                    saveuser("add", id_user);
                });
            } else if (action === 'edit') {
                $title_header.text("Edit Data user");
                $btnDataUser.text("Ubah");
                show_byId_user(id_user);

                $btnDataUser.off('click').on("click", function() {
                    saveuser("edit", id_user);
                });
            }
        }

        function show_byId_user(id_user) {
            $.ajax({
                url: `{{ url('/showById_data_user/${id_user}') }}`,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    $('#username').val(response.uername);
                   
                    $('#no_wa').val(response.no_wa);
                    $('#email').val(response.email);
                    $('#password').val(response.password);
                    $('#output').show();
                    
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memproses data.',
                    });
                }
            });
        }

        function savealat(action, id_alat) {
            const username = $('#username').val();
            
            const no_wa = $('#no_wa').val();
            const email = $('#email').val();
            const password = $('#password').val();

            if (!username || !no_wa || !email|| !password) {
                Swal.fire({
                    title: "Gagal simpan.",
                    text: "Harap isi semua form!",
                    icon: "error"
                });
                return;
            }

            const formData = new FormData();
            formData.append('username', username);
          
            formData.append('no_wa', no_wa);
           
            formData.append('email', email);
            formData.append('password', password);
            formData.append('_token', "{{ csrf_token() }}");

            const ajaxUrl = action === "add" ? "{{ url('/add_data_user') }}" :
                `{{ url('/edit_data_user/${id_user}') }}`;

            $.ajax({
                url: ajaxUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#UserTable').DataTable().ajax.reload();
                    $("#add_user").modal("hide");

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
                        title: action === "add" ? "Data User Berhasil Disimpan!" :
                            "Data User Berhasil Diubah!"
                    });

                    $username.val("");
                    $no_wa.val("");
                    $foto_alat.val("");
                    $email.val("");
                    $password.val("");
                    $output.hide();

                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat memproses data.',
                    });
                }
            });
        }
    </script>
@endpush
