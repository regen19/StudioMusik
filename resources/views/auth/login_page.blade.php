<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN Sistem Informasi Studio Musik ITERA</title>
    <link rel="icon" href="{{ asset('assets/img/logo-itera.png') }}" type="png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin-top: 100px;
            background: #1e1e2d;
        }

        .account-block {
            padding: 0;
            background-image: url('{{ asset('assets/img/ruang-studio.jpg') }}');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
            position: relative;
        }

        .account-block .overlay {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .account-block .account-testimonial {
            text-align: center;
            color: #fff;
            position: absolute;
            margin: 0 auto;
            padding: 0 1.75rem;
            bottom: 3rem;
            left: 0;
            right: 0;
        }

        .text-theme {
            color: #5369f8 !important;
        }

        .btn-theme {
            background-color: #5369f8;
            border-color: #5369f8;
            color: #fff;
        }

        .btn-theme:hover {
            background-color: #435ebe;
            color: #fff;
        }
    </style>
</head>

<body>
    <div id="main-wrapper" class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0 shadow">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-6 d-none d-lg-inline-block">
                                <div class="account-block rounded-left">
                                    <div class="overlay rounded-left"></div>
                                    <div class="account-testimonial">
                                        <h4 class="text-white mb-1">Studio Musik ITERA</h4>
                                        <p class="lead text-white mb-1">Sistem Informasi Manajemen</p>
                                        <p>Gedung D Lantai 3</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div id="form-login-coy">
                                    <div class="p-5">
                                        <div class="mb-3 text-center">
                                            <h3 class="h4 font-weight-bold text-theme">Masuk</h3>
                                            <p class="text-muted mt-2 mb-5">Silahkan Isi Form Dengan Akun Anda!</p>
                                        </div>
                                        <form>
                                            <div class="form-group mb-2">
                                                <label for="login-email">Email address</label>
                                                <input type="email" class="form-control" id="login-email" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="login-password">Password</label>
                                                <input type="password" class="form-control" id="login-password"
                                                    required>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-theme"
                                                    onclick="login()">Masuk</button>
                                            </div>
                                        </form>
                                        <a href="#" onclick="ShowForm('registrasi')"><small>belum punya
                                                akun?</small></a>
                                    </div>
                                </div>

                                <div id="form-register-coy" style="display: none;">
                                    <div class="p-5">
                                        <div class="mb-3 text-center">
                                            <h3 class="h4 font-weight-bold text-theme">Registrasi User</h3>
                                            <p class="text-muted mt-2 mb-5">Silahkan Isi Form Untuk Membuat Akun Anda!
                                            </p>
                                        </div>
                                        <form>
                                            <div class="form-group mb-2">
                                                <label for="register-username">Nama Lengkap</label>
                                                <input type="text" class="form-control" id="register-username"
                                                    required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="register-email">Email address</label>
                                                <input type="email" class="form-control" id="register-email" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="register-no_wa">Nomor WA <small class="text-danger"><i>(ex
                                                            : 08123xxx)</i></small></label>
                                                <input type="number" class="form-control" id="register-no_wa" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="register-password">Password</label>
                                                <input type="password" class="form-control" id="register-password"
                                                    required>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-theme"
                                                    onclick="register()">Register</button>
                                            </div>
                                        </form>
                                        <a href="#" onclick="ShowForm('login')"><small>Kembali ke
                                                login!</small></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).ready(function() {
            $('#login-email, #login-password').on('keypress', function(e) {
                if (e.which === 13) { // 13 kode tombol Enter
                    e.preventDefault()
                    login();
                }
            });

            $('#register-username, #register-email, #register-no_wa, #register-password').on('keypress',
                function(
                    e) {
                    if (e.which === 13) { // 13 kode tombol Enter
                        e.preventDefault()
                        register();
                    }
                });
        });

        function ShowForm(action) {
            if (action === "registrasi") {
                $("#form-login-coy").fadeOut(function() {
                    $("#form-register-coy").fadeIn();
                });
            } else if (action === "login") {
                $("#form-register-coy").fadeOut(function() {
                    $("#form-login-coy").fadeIn();
                });
            }
        }

        function login() {
            let email = $("#login-email").val();
            let password = $("#login-password").val();

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
            } else if (password == '') {
                Toast.fire({
                    icon: "warning",
                    title: "Password tidak boleh kosong!"
                });
                return false;
            }

            $.ajax({
                url: "{{ url('/authenticate') }}",
                method: 'post',
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'email': email,
                    'password': password,
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
                            title: "Login berhasil",
                        });

                        setTimeout(() => {
                            location.href = response.redirect;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                    console.error(status);
                    console.error(error);
                }
            });

        }

        function register() {
            let username = $("#register-username").val();
            let email = $("#register-email").val();
            let no_wa = $("#register-no_wa").val();
            let password = $("#register-password").val();

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


            if (username == '') {
                Toast.fire({
                    icon: "warning",
                    title: "Nama Lengkap tidak boleh kosong!"
                });
                return false;
            } else if (email == '') {
                Toast.fire({
                    icon: "warning",
                    title: "Email tidak boleh kosong!"
                });
                return false;
            } else if (no_wa == '') {
                Toast.fire({
                    icon: "warning",
                    title: "Nomor WA tidak boleh kosong!"
                });
                return false;

            } else if (IsEmail(email) == false) {
                Toast.fire({
                    icon: "warning",
                    title: "Gunakan format email yang benar!"
                });
                return false;
            } else if (password == '') {
                Toast.fire({
                    icon: "warning",
                    title: "Password tidak boleh kosong!"
                });
                return false;
            }

            $.ajax({
                url: "{{ url('/register') }}",
                method: 'post',
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'email': email,
                    'password': password,
                    'username': username,
                    'no_wa': no_wa,
                },
                success: function(response) {
                    if (response.status !== 200) {
                        Toast.fire({
                            icon: "warning",
                            title: response.error,
                        });
                    }


                    if (response.status === 200) {
                        Toast.fire({
                            icon: "success",
                            title: "Registrasi berhasil. Silahkan login ...",
                        });

                        setTimeout(() => {
                            location.href = response.redirect;
                        }, 1500);
                    }
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

                    Toast.fire({
                        icon: "error",
                        title: errorMsg,
                    });
                }
            });

        }

        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }
    </script>
</body>

</html>
