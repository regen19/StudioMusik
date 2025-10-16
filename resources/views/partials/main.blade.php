{{-- resources/views/layouts/app.blade.php (FINAL) --}}
<!doctype html>
<html lang="id">
<head>
  @include('partials.header') {{-- meta, title, css --}}
  {{-- DataTables CSS (kalau dipakai di banyak halaman, taruh di layout) --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
</head>
<body>
  @include('sweetalert::alert')

  {{-- Loader (opsional) --}}
  <!-- <div id="loader" class="loader-container" style="display:none;">
    <img src="{{ asset('assets/img/ball-triangle.svg') }}" class="loader" alt="loading" width="200" height="200">
  </div> -->

  <div id="app">
    {{-- Sidebar --}}
    <div id="sidebar">
      @include('partials.sidebar')
    </div>

<<<<<<< Updated upstream
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <div id="app">
        <!-- SIDEBAR -->
        <div id="sidebar">
            @include('partials.sidebar')
=======
    <div id="main">
      <header class="mb-3">
        <div class="d-flex align-items-center">
          <a href="#" class="burger-btn d-block d-xl-none me-2">
            <i class="bi bi-justify fs-3"></i>
          </a>

          <div class="ms-auto d-flex align-items-center gap-2">
            @include('partials.notification-bell')
          </div>
>>>>>>> Stashed changes
        </div>
      </header>

      {{-- Konten halaman --}}
      @yield('MainContent')

<<<<<<< Updated upstream
            @yield('MainContent')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>2024 &copy; Studio Musik ITERA</p>
                    </div>
                    <div class="float-end">
                        <p>
                            Develop by <a href="#">Eben</a>
                        </p>
                    </div>
                </div>
            </footer>
=======
      <footer>
        <div class="footer clearfix mb-0 text-muted">
          <div class="float-start">
            <p>2024 &copy; Studio Musik ITERA</p>
          </div>
          <div class="float-end">
            <p>
              Develop by <a href="#">Eben</a>
            </p>
          </div>
>>>>>>> Stashed changes
        </div>
      </footer>
    </div>
  </div>

<<<<<<< Updated upstream
    @include('partials.footer')

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("loader").style.display = "block";

            window.addEventListener("load", function() {
                setTimeout(function() {
                    document.getElementById("loader").style.display = "none";
                }, 700);
            });
        });
    </script> --}}
=======
  @include('partials.footer')

  {{-- ================== GLOBAL JS (load sekali & urut) ================== --}}
  {{-- jQuery (PASTIKAN HANYA INI YANG DILOAD) --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"
          integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
          crossorigin="anonymous"></script>

  {{-- DataTables (kalau dipakai) --}}
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

  {{-- jQuery Repeater (jika ada elemen .repeater di halaman) --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

  {{-- Bootstrap JS (setelah jQuery) --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  {{-- Semua @push('scripts') dari view akan dirender di sini --}}
  @stack('scripts')
  {{-- ================== END GLOBAL JS ================== --}}

  {{-- Loader show/hide aman --}}
  <script>
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function(el){
        if (!bootstrap.Dropdown.getInstance(el)) {
            new bootstrap.Dropdown(el);
        }
        });

        document.getElementById('btnNotif')?.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        const dd = bootstrap.Dropdown.getInstance(this) || new bootstrap.Dropdown(this);
        dd.toggle();
        });
    });
  </script>
>>>>>>> Stashed changes
</body>
</html>