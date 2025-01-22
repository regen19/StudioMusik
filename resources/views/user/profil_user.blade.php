@extends('partials.main')
@section('MainContent')
    <div class="container">
        <div class="main-body">

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="main-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                </ol>
            </nav>
            <!-- /Breadcrumb -->

            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                    class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4 class="text-uppercase">{{ $user->username }}</h4>
                                    <p class="text-secondary mb-1">Institut Teknologi Sumatera</p>
                                    {{-- <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nama Lengkap</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <span id="username-display">{{ $user->username }}</span>
                                    <input type="text" id="username-edit" class="form-control"
                                        value="{{ $user->username }}" style="display: none;">

                                    <input type="hidden" id="id_user" value="{{ Auth::user()->id_user }}">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <span id="email-display">{{ $user->email }}</span>
                                    <input type="email" id="email-edit" class="form-control" value="{{ $user->email }}"
                                        style="display: none;">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nomor WA</h6>
                                </div>
                                <div class="col-sm-9 text-secondary text-uppercase">
                                    <span id="no-wa-display">{{ $user->no_wa }}</span>
                                    <input type="text" id="no-wa-edit" class="form-control" value="{{ $user->no_wa }}"
                                        style="display: none;">
                                </div>
                            </div>
                            <hr>
                            <div>
                                <button id="edit-button" class="btn btn-info my-2">Edit</button>
                                <button id="save-button" class="btn btn-success my-2" style="display: none;">Save</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function IsEmail(email) {
                var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!regex.test(email)) {
                    return false;
                } else {
                    return true;
                }
            }

            $(document).ready(function() {
                $('#edit-button').click(function() {
                    $('#username-display, #email-display, #no-wa-display').hide();
                    $('#username-edit, #email-edit, #no-wa-edit').show();
                    $('#edit-button').hide();
                    $('#save-button').show();
                });

                $('#save-button').click(function() {

                    let username = $("#username-edit").val();
                    let email = $("#email-edit").val();
                    let no_wa = $("#no-wa-edit").val();
                    let id_user = $("#id_user").val()
                    // let password = $("#register-password").val();

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    if (email == '') {
                        Toast.fire({
                            icon: "warning",
                            title: "Email tidak boleh kosong!"
                        });
                        return false;
                    } else if (IsEmail(email) == false) {
                        Toast.fire({
                            icon: "warning",
                            title: "Gunakan format email yang benar!"
                        });
                        return false;
                    } else if (username == '') {
                        Toast.fire({
                            icon: "warning",
                            title: "Username tidak boleh kosong!"
                        });
                        return false;
                    }

                    console.log(username, email, no_wa, id_user)

                    $.ajax({
                        url: `{{ url('/edit_profile/${id_user}') }}`,
                        method: 'post',
                        dataType: "json",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'email': email,
                            'no_wa': no_wa,
                            'username': username,
                        },
                        success: function(response) {
                            if (response.status !== 200) {
                                Toast.fire({
                                    icon: "warning",
                                    title: response.message,
                                });
                            }

                            if (response.status === 200) {

                                Toast.fire({
                                    icon: "success",
                                    title: response.msg,
                                });

                                $('#username-display').text($('#username-edit').val()).show();
                                $('#email-display').text($('#email-edit').val()).show();
                                $('#no-wa-display').text($('#no-wa-edit').val()).show();
                                $('#username-edit, #email-edit, #no-wa-edit').hide();
                                $('#edit-button').show();
                                $('#save-button').hide();

                                setTimeout(() => {
                                    location.reload()
                                }, 1000);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                            console.error(status);
                            console.error(error);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
