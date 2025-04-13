<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative mb-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                {{-- <a href="/"><img src="{{ asset('assets/img/logo-itera.png') }}" width="50px" height="500px"
                        alt="Logo Itera" /></a> --}}
                <p class="fs-6 m-0">Sistem Informasi Manajemen</p>
                <p class="fs-6">Studio Musik ITERA</p>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                        role="img" class="iconify iconify--system-uicons" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20" height="20"
                        preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="sidebar-toggler x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>

    <!-- sidebar menu -->
    <div class="sidebar-menu mt-0">
        <ul class="menu">
            <li class="sidebar-title">MENU</li>


            {{-- ADMIN --}}
            @canany(['isAdmin', 'isK3l'])
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                    <a href="{{ url('/dashboard') }}" class="sidebar-link" id="dashboard">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li
                    class="sidebar-item has-sub {{ request()->is('data_ruangan') || request()->is('data_tutorial_alat') || request()->is('data_peminjam_ruangan') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-buildings"></i>
                        <span>Studio Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('data_ruangan') ? 'active' : '' }}">
                            <a href="{{ url('/data_ruangan') }}" class="submenu-link">Data Ruangan</a>
                        </li>

                        <li class="submenu-item {{ request()->is('data_peminjam_ruangan') ? 'active' : '' }}">
                            <a href="{{ url('/data_peminjam_ruangan') }}" class="submenu-link">Data Peminjam Studio</a>
                        </li>

                        <li class="submenu-item {{ request()->is('data_tutorial_alat') ? 'active' : '' }}">
                            <a href="{{ url('/data_tutorial_alat') }}" class="submenu-link">Tutorial Penggunaan Alat</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item has-sub {{ request()->is('data_alat') || request()->is('data_tutorial_alat') || request()->is('data_peminjam_alat') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                    <i class="bi bi-boombox-fill"></i>
                        <span>Alat Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('data_alat') ? 'active' : '' }}">
                            <a href="{{ url('/data_alat') }}" class="submenu-link">Data Alat</a>
                        </li>

                        <li class="submenu-item {{ request()->is('data_peminjam_alat') ? 'active' : '' }}">
                            <a href="{{ url('/data_peminjam_alat') }}" class="submenu-link">Data Peminjam Alat</a>
                        </li>

                        <!-- <li class="submenu-item {{ request()->is('data_tutorial_alat') ? 'active' : '' }}">
                            <a href="{{ url('/data_tutorial_alat') }}" class="submenu-link">Tutorial Penggunaan Alat</a>
                        </li> -->
                    </ul>
                </li>

                <li
                    class="sidebar-item has-sub {{ request()->is('master_jasa_musik') || request()->is('pesanan_jasa_musik') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-file-music"></i>
                        <span>Jasa Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('master_jasa_musik') ? 'active' : '' }}">
                            <a href="{{ url('/master_jasa_musik') }}" class="submenu-link">Daftar Jasa Musik</a>
                        </li>

                        <li class="submenu-item {{ request()->is('pesanan_jasa_musik') ? 'active' : '' }}">
                            <a href="{{ url('/pesanan_jasa_musik') }}" class="submenu-link">Pesanan Jasa Musik</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ request()->is('laporan_admin') ? 'active' : '' }}">
                    <a href="{{ url('/laporan_admin') }}" class="sidebar-link">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            @endcanany


            {{-- USER --}}
            @can('isUser')
                <li class="sidebar-item {{ request()->is('dashboard_user') ? 'active' : '' }}">
                    <a href="{{ url('/dashboard_user') }}" class="sidebar-link" id="dashboard">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard User</span>
                    </a>
                </li>



                <li
                    class="sidebar-item has-sub {{ request()->is('data_ruangan_studio') || request()->is('jadwal_studio_saya') || request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-calendar2-week"></i>
                        <span>Studio Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('data_ruangan_studio') ? 'active' : '' }}">
                            <a href="{{ url('/data_ruangan_studio') }}" class="submenu-link">
                                <span>Ruangan Studio</span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('jadwal_studio_saya') ? 'active' : '' }}">
                            <a href="{{ url('/jadwal_studio_saya') }}" class="submenu-link">Jadwal Saya</a>
                        </li>

                        <li class="submenu-item {{ request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                            <a href="{{ url('/tutorial_penggunaan_alat') }}" class="submenu-link">Tutorial Penggunaan
                                Alat</a>
                        </li>
                    </ul>
                </li>

                <li
                    class="sidebar-item has-sub {{ request()->is('data_ruangan_studio') || request()->is('jadwal_studio_saya') || request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                    <i class="bi bi-boombox-fill"></i>
                        <span>Alat Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('data_ruangan_studio') ? 'active' : '' }}">
                            <a href="{{ url('/data_ruangan_studio') }}" class="submenu-link">
                                <span>Alat Musik</span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('jadwal_studio_saya') ? 'active' : '' }}">
                            <a href="{{ url('/jadwal_studio_saya') }}" class="submenu-link">Alat Dipinjam</a>
                        </li>

                        <li class="submenu-item {{ request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                            <a href="{{ url('/tutorial_penggunaan_alat') }}" class="submenu-link">Tutorial Penggunaan
                                Alat</a>
                        </li>
                    </ul>
                </li>

                @php
                    $menu_jasa = DB::table('master_jasa_musik')->get();
                @endphp

                <li class="sidebar-item has-sub {{ request()->is('pesanan_jasa_musik_saya') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-music-note-list"></i>
                        <span>Jasa Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('pesanan_jasa_musik_saya') ? 'active' : '' }}">
                            <a href="{{ url('/pesanan_jasa_musik_saya') }}" class="submenu-link">Pesanan Saya</a>
                        </li>

                        @foreach ($menu_jasa as $menu)
                            <li
                                class="submenu-item {{ request()->is('pembuatan_jasa_musik/' . $menu->id_jasa_musik) ? 'active' : '' }}">
                                <a href="{{ url('/pembuatan_jasa_musik/' . $menu->id_jasa_musik) }}"
                                    class="submenu-link">{{ $menu->nama_jenis_jasa }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @endcan

            <li class="sidebar-title">AKUN USER</li>

            <li class="sidebar-item {{ request()->is('ManageAkunUser') ? 'active' : '' }}">
                    <a href="{{ url('/ManageAkunUser') }}" class="sidebar-link">
                        <i class="bi bi-person-circle"></i>
                        <span>Manage Akun User</span>
                    </a>
            </li>

            <li class="sidebar-item has-sub">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-person-circle"></i>
                    <span>User</span>
                </a>

                <ul class="submenu">
                    <li class="submenu-item {{ request()->is('profile_user') ? 'active' : '' }}">
                        <a href="{{ url('/profile_user') }}" class="submenu-link">Profil</a>
                    </li>
                    <li class="submenu-item">
                        <a href="#" onclick="btnLogout()" class="submenu-link pe-auto">Keluar</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>


<script>
    function btnLogout() {
        Swal.fire({
            title: "Anda ingin keluar halaman?",
            // text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yaa, keluar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/logout') }}",
                    method: 'get',
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil logout!",
                            text: "Meluncur kelogin...",
                            icon: "success"
                        });

                        setTimeout(() => {
                            location.href = response.redirect;
                        }, 1000);
                    }
                })

            }
        });


    }
</script>
