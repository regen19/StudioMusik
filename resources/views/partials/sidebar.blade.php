<div class="sidebar-wrapper active">
  <div class="sidebar-header position-relative mb-0">
    <div class="d-flex justify-content-between align-items-center">
      <div class="logo">
        <p class="fs-6 m-0">Sistem Informasi Manajemen</p>
        <p class="fs-6">Studio Musik ITERA</p>
        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
          <div class="form-check form-switch fs-6">
            <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer" />
            <label class="form-check-label"></label>
          </div>
        </div>
      </div>
      <div class="sidebar-toggler x">
        <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
      </div>
    </div>
  </div>

  <div class="sidebar-menu mt-0">
    <ul class="menu">
      <li class="sidebar-title">MENU</li>

      {{-- DASHBOARD: Admin + K3L + UKMBS --}}
      @canany(['isAdmin','isK3l','isUkmbs'])
      <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}" class="sidebar-link">
          <i class="bi bi-grid-fill"></i><span>Dashboard</span>
        </a>
      </li>
      @endcanany

<<<<<<< Updated upstream
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
                    class="sidebar-item has-sub {{ request()->is('data_alat') ||  request()->is('data_peminjam_alat') ? 'active' : '' }}">
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
                    class="sidebar-item has-sub {{ request()->is('data_alat_user') || request()->is('alat_dipinjam') || request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                    <i class="bi bi-boombox-fill"></i>
                        <span>Alat Musik</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ request()->is('data_alat_user') ? 'active' : '' }}">
                            <a href="{{ url('/data_alat_user') }}" class="submenu-link">
                                <span>Alat Musik</span>
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->is('alat_dipinjam') ? 'active' : '' }}">
                            <a href="{{ url('/alat_dipinjam') }}" class="submenu-link">Alat Dipinjam</a>
                        </li>

                        <!-- <li class="submenu-item {{ request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
                            <a href="{{ url('/tutorial_penggunaan_alat') }}" class="submenu-link">Tutorial Penggunaan
                                Alat</a>
                        </li> -->
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

        @canany(['isAdmin'])
            <li class="sidebar-item {{ request()->is('data_user') ? 'active' : '' }}">
                    <a href="{{ url('data_user') }}" class="sidebar-link">
                        <i class="bi bi-person-circle"></i>
                        <span>Manage Akun User</span>
                    </a>
            </li>
        @endcan
        
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
=======
      {{-- STUDIO MUSIK, JASA MUSIK, LAPORAN: ADMIN ONLY --}}
      @can('isAdmin')
        {{-- Studio Musik --}}
        <li class="sidebar-item has-sub {{ request()->is('data_ruangan') || request()->is('data_peminjam_ruangan') ? 'active' : '' }}">
          <a href="#" class="sidebar-link">
            <i class="bi bi-buildings"></i>
            <span>Studio Musik</span>
          </a>
          <ul class="submenu">
            <li class="submenu-item {{ request()->is('data_ruangan') ? 'active' : '' }}">
              <a href="{{ url('/data_ruangan') }}" class="submenu-link">Data Ruangan</a>
>>>>>>> Stashed changes
            </li>
            <li class="submenu-item {{ request()->is('data_peminjam_ruangan') ? 'active' : '' }}">
              <a href="{{ url('/data_peminjam_ruangan') }}" class="submenu-link">Data Peminjam Studio</a>
            </li>
            <li class="submenu-item {{ request()->is('data_tutorial_alat') ? 'active' : '' }}">
              <a href="{{ url('/data_tutorial_alat') }}" class="submenu-link">Tutorial Penggunaan Alat</a>
            </li>
          </ul>
        </li>

        {{-- Jasa Musik --}}
        <li class="sidebar-item has-sub {{ request()->is('master_jasa_musik') || request()->is('pesanan_jasa_musik') ? 'active' : '' }}">
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

        {{-- Laporan --}}
        <li class="sidebar-item {{ request()->is('laporan_admin') ? 'active' : '' }}">
          <a href="{{ url('/laporan_admin') }}" class="sidebar-link">
            <i class="bi bi-bar-chart-line-fill"></i>
            <span>Laporan</span>
          </a>
        </li>
      @endcan

      {{-- ALAT MUSIK: Admin + K3L + UKMBS (view only utk K3L/UKMBS) --}}
      @canany(['isAdmin','isK3l','isUkmbs'])
      <li class="sidebar-item has-sub {{ request()->routeIs('alat.index') || request()->routeIs('alat.peminjam') ? 'active' : '' }}">
        <a href="#" class="sidebar-link">
          <i class="bi bi-boombox-fill"></i><span>Alat Musik</span>
        </a>
        <ul class="submenu">
          <li class="submenu-item {{ request()->routeIs('alat.index') ? 'active' : '' }}">
            <a href="{{ route('alat.index') }}" class="submenu-link">Data Alat</a>
          </li>
          <li class="submenu-item {{ request()->routeIs('alat.peminjam') ? 'active' : '' }}">
            <a href="{{ route('alat.peminjam') }}" class="submenu-link">Jadwal &amp; Peminjam Alat</a>
          </li>
        </ul>
      </li>
      @endcanany

      {{-- == K3L == --}}
      @can('isK3l')
      <li class="sidebar-item has-sub {{ request()->routeIs('k3l.*') ? 'active' : '' }}">
        <a href="#" class="sidebar-link">
          <i class="bi bi-shield-check"></i>
          <span>Approval</span>
        </a>
        <ul class="submenu">
          <li class="submenu-item {{ request()->routeIs('k3l.alat') ? 'active' : '' }}">
            <a href="{{ route('k3l.alat') }}" class="submenu-link">Approval Alat</a>
          </li>
          <li class="submenu-item {{ request()->routeIs('k3l.jadwal') ? 'active' : '' }}">
            <a href="{{ route('k3l.jadwal') }}" class="submenu-link">Jadwal Disetujui</a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- == UKMBS == --}}
      @can('isUkmbs')
      <li class="sidebar-item has-sub {{ request()->routeIs('ukmbs.*') ? 'active' : '' }}">
        <a href="#" class="sidebar-link">
          <i class="bi bi-box-arrow-in-down"></i>
          <span>UKMBS</span>
        </a>
        <ul class="submenu">
          <li class="submenu-item {{ request()->routeIs('ukmbs.peminjaman.index') ? 'active' : '' }}">
            <a href="{{ route('ukmbs.peminjaman.index') }}" class="submenu-link">Peminjaman & Pengembalian</a>
          </li>
        </ul>
      </li>
      @endcan

      {{-- USER --}}
      @can('isUser')
        <li class="sidebar-item {{ request()->is('dashboard_user') ? 'active' : '' }}">
          <a href="{{ url('/dashboard_user') }}" class="sidebar-link">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard User</span>
          </a>
        </li>

        <li class="sidebar-item has-sub {{ request()->is('data_ruangan_studio') || request()->is('jadwal_studio_saya') ? 'active' : '' }}">
          <a href="#" class="sidebar-link">
            <i class="bi bi-calendar2-week"></i>
            <span>Studio Musik</span>
          </a>
          <ul class="submenu">
            <li class="submenu-item {{ request()->is('data_ruangan_studio') ? 'active' : '' }}">
              <a href="{{ url('/data_ruangan_studio') }}" class="submenu-link">Ruangan Studio</a>
            </li>
            <li class="submenu-item {{ request()->is('jadwal_studio_saya') ? 'active' : '' }}">
              <a href="{{ url('/jadwal_studio_saya') }}" class="submenu-link">Jadwal Saya</a>
            </li>
            <li class="submenu-item {{ request()->is('tutorial_penggunaan_alat') ? 'active' : '' }}">
              <a href="{{ url('/tutorial_penggunaan_alat') }}" class="submenu-link">Tutorial Penggunaan Alat</a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item has-sub {{ request()->is('data_alat_user') || request()->is('alat_dipinjam') ? 'active' : '' }}">
          <a href="#" class="sidebar-link">
            <i class="bi bi-boombox-fill"></i>
            <span>Alat Musik</span>
          </a>
          <ul class="submenu">
            <li class="submenu-item {{ request()->is('data_alat_user') ? 'active' : '' }}">
              <a href="{{ url('/data_alat_user') }}" class="submenu-link">Alat Musik</a>
            </li>
            <li class="submenu-item {{ request()->is('alat_dipinjam') ? 'active' : '' }}">
              <a href="{{ url('/alat_dipinjam') }}" class="submenu-link">Alat Dipinjam</a>
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
              <li class="submenu-item {{ request()->is('pembuatan_jasa_musik/'.$menu->id_jasa_musik) ? 'active' : '' }}">
                <a href="{{ url('/pembuatan_jasa_musik/'.$menu->id_jasa_musik) }}" class="submenu-link">
                  {{ $menu->nama_jenis_jasa }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>
      @endcan

      <li class="sidebar-title">AKUN USER</li>

      {{-- hanya admin penuh yang bisa kelola akun --}}
      @can('isAdmin')
      <li class="sidebar-item {{ request()->is('data_user') ? 'active' : '' }}">
        <a href="{{ url('data_user') }}" class="sidebar-link">
          <i class="bi bi-person-circle"></i>
          <span>Manage Akun User</span>
        </a>
      </li>
      @endcan

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
            <a href="#" onclick="btnLogout()" class="submenu-link">Keluar</a>
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
              text: "Meluncur ke login...",
              icon: "success"
            });
            setTimeout(() => { location.href = response.redirect; }, 1000);
          }
        })
      }
    });
  }
</script>