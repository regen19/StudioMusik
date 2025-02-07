@include('partials.header')


<body>
    @include('sweetalert::alert')
    <div id="loader" class="loader-container">
        <img src="{{ asset('assets/img/ball-triangle.svg') }}" class="loader" alt="audio" width="200px" height="200px">
    </div>

    <div id="app">
        <!-- SIDEBAR -->
        <div id="sidebar">
            @include('partials.sidebar')
        </div>

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('MainContent')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>Copyright &copy; Studio Musik ITERA</p>
                    </div>
                    <div class="float-end">
                        <p>
                            Develop by <a href="#">Regen</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

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
</body>

</html>
