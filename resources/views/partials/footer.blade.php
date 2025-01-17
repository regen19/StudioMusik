{{-- Jquery --}}
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

<script src="{{ asset('assets/js/dark.js') }}"></script>
<script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Need: Apexcharts -->
{{-- <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script> --}}
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

{{-- DataTables --}}
{{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> --}}
<script src="{{ asset('assets/dataTable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/dataTable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/dataTable/datatables.js') }}"></script>

{{-- DatePicker --}}
<script src="{{ asset('assets/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/flatpickr/date-picker.js') }}"></script>

{{-- Choices --}}
<script src="{{ asset('assets/choices/choices.js') }}"></script>
<script src="{{ asset('assets/choices/form-element-select.js') }}"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Quill --}}
<script src="{{ asset('assets/quill/quill.min.js') }}"></script>
<script src="{{ asset('assets/quill/quill.js') }}"></script>

@stack('script')
